<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\RequisitionModel;
use App\Models\RequisitionItemModel;
use App\Models\SupplierModel;
use App\Models\ProductModel;
use App\Models\SMSLogModel;

class RequisitionController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        // Get the authenticated employee using default guard
        $employee = Auth::user();
        
        // Get suppliers and products
        $suppliers = SupplierModel::all();
        $products = ProductModel::all();
        
        // Get requisitions - handle case where there are no requisitions yet
        try {
            if (in_array($employee->position, ['owner', 'purchasor'])) {
                $requisitions = RequisitionModel::with('employee')->latest()->paginate(10);
            } else {
                $requisitions = RequisitionModel::with('employee')->where('req_by', $employee->employee_id)->latest()->paginate(10);
            }
        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), set empty collection
            $requisitions = collect();
        }
        
        return view('pages.requisition', [
            'employeeName' => $employee->f_name . ' ' . $employee->l_name,
            'employee' => $employee,
            'suppliers' => $suppliers,
            'products' => $products,
            'requisitions' => $requisitions
        ]);
    }

    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'req_num' => 'required|unique:requisitions,req_num',
        'supplier_id' => 'required|exists:suppliers,supplier_id',
        'require_date' => 'required|date|after_or_equal:today',
        'items_data' => 'required|json'
    ], [
        'require_date.after_or_equal' => 'The required date cannot be earlier than today.',
        'items_data.required' => 'Please add at least one item to the requisition.',
        'items_data.json' => 'Invalid items data format.'
    ]);

    $employee = Auth::user();

    // Create the requisition
    $requisition = RequisitionModel::create([
        'req_num' => $request->req_num,
        'req_by' => $employee->employee_id,
        'request_date' => now(),
        'require_date' => $request->require_date,
        'req_status' => 'pending'
    ]);

    // Create requisition items - FIXED FIELD NAMES
    $items = json_decode($request->items_data, true);
    
    foreach ($items as $item) {
        RequisitionItemModel::create([
            'req_id' => $requisition->req_id,
            'prod_id' => $item['prod_id'], // Make sure this matches JavaScript
            'quantity' => $item['quantity'], // Make sure this matches JavaScript
            'unit' => $item['unit'],
            'remarks' => $item['remarks'] ?? null
        ]);
    }

    return redirect()->route('requisition')->with('success', 'Requisition submitted successfully!');
}

    public function show($id)
    {
        // For separate detail page if needed later
        return redirect()->route('requisition')->with('info', 'Use the View button to see requisition details.');
    }

    public function getRequisitionDetails($id)
    {
        try {
            $requisition = RequisitionModel::find($id);
            
            if (!$requisition) {
                return response()->json(['error' => 'Requisition not found'], 404);
            }

            $employee = Auth::user();
            if (!in_array($employee->position, ['owner', 'purchasor']) && $requisition->req_by != $employee->employee_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $requisition->load('employee', 'supplier');

            $response = [
                'req_id' => $requisition->req_id,
                'req_num' => $requisition->req_num,
                'req_status' => $requisition->req_status,
                'request_date' => $requisition->request_date,
                'require_date' => $requisition->require_date,
                'remarks' => $requisition->remarks,
                'employee' => [
                    'f_name' => $requisition->employee->f_name ?? 'Unknown',
                    'l_name' => $requisition->employee->l_name ?? 'User',
                ],
                'supplier' => [
                    's_name' => $requisition->supplier->s_name ?? 'No Supplier',
                ],
                'items' => [],
                'sms_logs' => []
            ];

            $items = RequisitionItemModel::with('product')->where('req_id', $id)->get();
            foreach ($items as $item) {
                $response['items'][] = [
                    'product' => [
                        'prod_name' => $item->product->prod_name ?? 'Unknown Product'
                    ],
                    'quantity' => $item->qty ?? $item->quantity ?? 0, // Handle different column names
                    'unit' => $item->unit,
                    'remarks' => $item->remarks,
                    'status' => $item->status ?? 'pending'
                ];
            }
            
            /*
            // Get SMS logs if they exist
            if (class_exists('App\Models\SMSLogModel')) {
                $smsLogs = SMSLogModel::where('req_id', $id)->get();
                foreach ($smsLogs as $log) {
                    $response['sms_logs'][] = [
                        'created_at' => $log->created_at,
                        'status' => $log->status,
                        'message' => $log->message,
                        'response' => $log->response
                    ];
                }
            }*/


            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'req_id' => $id,
                'req_num' => 'REQ-' . $id,
                'req_status' => 'pending',
                'request_date' => now()->format('Y-m-d H:i:s'),
                'require_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
                'remarks' => 'Error loading details',
                'employee' => ['f_name' => 'Error', 'l_name' => 'User'],
                'supplier' => ['s_name' => 'Error Supplier'],
                'items' => [],
                'sms_logs' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    public function convertToPO($id)
    {
        try {
            $requisition = RequisitionModel::findOrFail($id);
            
            
            $employee = Auth::user();
            if (!in_array($employee->position, ['owner', 'purchasor'])) {
                return redirect()->route('requisition')->with('error', 'You do not have permission to convert requisitions to purchase orders.');
            }

            // Check if requisition is in pending status
            if ($requisition->req_status !== 'pending') {
                return redirect()->route('requisition')->with('error', 'Only pending requisitions can be converted to purchase orders.');
            }

            // Update requisition status to approved
            $requisition->update([
                'req_status' => 'approved'
            ]);

            // Here you would typically create a purchase order
            // For now, we'll just update the status and show a success message
            
            return redirect()->route('requisition')->with('success', 'Requisition has been converted to purchase order successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('requisition')->with('error', 'Requisition not found.');
        } catch (\Exception $e) {
            return redirect()->route('requisition')->with('error', 'An error occurred while converting to purchase order.');
        }
    }
    */
    
}