@extends('layouts.app')

@section('content')
<!-- Success Message -->
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

@if(session('info'))
<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('info') }}</span>
</div>
@endif

<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Requisition List</h1>

        <button onclick="openNewRequestModal()" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            + New Request
        </button>
    </div>

    <!-- Requisition List Table -->
    <div class="bg-white shadow rounded-md overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left border-b">Request #</th>
                    <th class="p-3 text-left border-b">Requested By</th>
                    <th class="p-3 text-left border-b">Request Date</th>
                    <th class="p-3 text-left border-b">Required Date</th>
                    <th class="p-3 text-left border-b">Status</th>
                    <th class="p-3 text-left border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisitions as $requisition)
                <tr>
                    <td class="p-3 border-b">{{ $requisition->req_num }}</td>
                    <td class="p-3 border-b">{{ $requisition->employee->f_name }} {{ $requisition->employee->l_name }}</td>
                    <td class="p-3 border-b">{{ $requisition->request_date->format('M d, Y') }}</td>
                    <td class="p-3 border-b">{{ $requisition->require_date->format('M d, Y') }}</td>
                    <td class="p-3 border-b">
                        <span class="px-3 py-1 rounded-full text-sm 
                            @if($requisition->req_status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($requisition->req_status == 'approved') bg-green-100 text-green-700
                            @elseif($requisition->req_status == 'declined') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($requisition->req_status) }}
                        </span>
                    </td>
                    <td class="p-3 border-b text-center">
                        <button onclick="viewRequisition({{ $requisition->req_id }})" 
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 mr-2">
                            View
                        </button>
                        @if($requisition->req_status == 'pending' && (auth()->user()->position == 'owner' || auth()->user()->position == 'purchasor'))
                        <button onclick="convertToPO({{ $requisition->req_id }})" 
                                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                            Convert to PO
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        No requisitions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($requisitions->hasPages())
    <div class="mt-4">
        {{ $requisitions->links() }}
    </div>
    @endif
</div>

<!-- New Request Modal -->
<div id="newRequestModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gray-800 text-white p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold">REQUISITION</h2>
                <button onclick="closeNewRequestModal()" class="text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <form id="requisitionForm" action="{{ route('requisition.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <!-- Requests List Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Requests List</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Request No.</label>
                            <input type="text" id="requestNumber" name="req_num" readonly
                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 font-mono font-semibold" 
                                value="REQ-{{ now()->format('YmdHis') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <select id="supplierSelect" name="supplier_id" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->s_name }} - {{ $supplier->s_contact }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Request Details -->
                    <div class="space-y-3 mb-6 p-4 border border-gray-200 rounded-md">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium">Requested By:</span>
                                <span id="loggedInEmployee" class="text-sm ml-2 font-semibold text-blue-600">
                                    {{ $employeeName }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-medium">Supplier:</span>
                                <span id="selectedSupplierDisplay" class="text-sm ml-2">Please select a supplier</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium">Required Date:</label>
                                <input type="date" name="require_date" id="requireDate" 
       class="border border-gray-300 px-2 py-1 text-sm rounded" 
       min="{{ now()->format('Y-m-d') }}"
       value="{{ now()->addDays(7)->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium">Remarks:</label>
                                <input type="text" name="remarks" class="border-b border-gray-300 px-2 py-1 text-sm w-full" placeholder="Optional remarks">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requested Items Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Requested Items</h3>
                        <button type="button" onclick="openAddItemModal()" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition text-sm">
                            + Add Item
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-left">Item Name</th>
                                    <th class="border border-gray-300 p-2 text-left">Quantity</th>
                                    <th class="border border-gray-300 p-2 text-left">Unit</th>
                                    <th class="border border-gray-300 p-2 text-left">Remarks</th>
                                    <th class="border border-gray-300 p-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="requestedItemsTable">
                                <tr id="noItemsRow">
                                    <td colspan="5" class="border border-gray-300 p-4 text-center text-gray-500">
                                        No items added yet. Click "Add Item" to get started.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Hidden input for items data -->
                <input type="hidden" name="items_data" id="itemsData">

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <button type="button" onclick="submitRequisition()" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                        Submit Requisition
                    </button>
                    
                    <div class="text-right">
                        <div class="mb-2">
                            <span class="font-medium">STATUS:</span>
                            <span class="ml-2 text-yellow-600 font-semibold">Draft</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-2xl">
        <!-- Modal Header -->
        <div class="bg-gray-800 text-white p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold">Add New Item</h2>
                <button onclick="closeAddItemModal()" class="text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Add Item Form -->
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                    <select id="itemNameSelect" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Select Item</option>
                        @foreach($products as $product)
                            <option value="{{ $product->prod_id }}" data-unit="{{ $product->unit }}">
                                {{ $product->prod_name }} ({{ $product->category }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" id="itemQuantity" min="1" value="1" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <select id="itemUnit" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="pcs">Pieces</option>
                        <option value="reams">Reams</option>
                        <option value="boxes">Boxes</option>
                        <option value="units">Units</option>
                        <option value="kg">Kilograms</option>
                        <option value="bags">Bags</option>
                        <option value="liters">Liters</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <input type="text" id="itemRemarks" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2" 
                           placeholder="Optional remarks">
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeAddItemModal()" 
                        class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                    Cancel
                </button>
                <button type="button" onclick="addItemToList()" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Add Item
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Request Modal -->
<div id="viewRequestModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gray-800 text-white p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold">View Requisition</h2>
                <button onclick="closeViewRequestModal()" class="text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6" id="viewModalContent">
            <!-- Loading state will be shown here initially -->
            <div class="text-center py-12" id="viewModalLoading">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-lg font-medium">Loading Requisition Details</p>
                <p class="text-sm text-gray-600 mt-2">Please wait...</p>
            </div>

            <!-- Actual content (hidden by default) -->
            <div id="viewModalData" class="hidden">
                <!-- Request Details Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Request Details</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">Request Number:</span>
                            <span id="viewReqNumber" class="text-sm ml-2 font-semibold"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Supplier:</span>
                            <span id="viewSupplier" class="text-sm ml-2 font-semibold"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">Requested By:</span>
                            <span id="viewRequestedBy" class="text-sm ml-2 font-semibold"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Request Date:</span>
                            <span id="viewRequestDate" class="text-sm ml-2"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">Required Date:</span>
                            <span id="viewRequiredDate" class="text-sm ml-2"></span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Status:</span>
                            <span id="viewStatus" class="text-sm ml-2 px-2 py-1 rounded-full"></span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <span class="text-sm font-medium text-gray-600">Remarks:</span>
                        <span id="viewRemarks" class="text-sm ml-2"></span>
                    </div>
                </div>

                <!-- Requested Items Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Requested Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-left">Item Name</th>
                                    <th class="border border-gray-300 p-2 text-left">Quantity</th>
                                    <th class="border border-gray-300 p-2 text-left">Unit</th>
                                    <th class="border border-gray-300 p-2 text-left">Remarks</th>
                                    <th class="border border-gray-300 p-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody id="viewItemsTable">
                                <!-- Items will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SMS Logs Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">SMS Logs</h3>
                    <div id="smsLogsContent" class="space-y-4">
                        <!-- SMS logs will be populated here -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button onclick="closeViewRequestModal()" 
                            class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
                        Close
                    </button>
                    <button id="convertToPOBtn" onclick="convertCurrentToPO()" 
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition hidden">
                        Convert to PO
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let requestedItems = [];
let itemCounter = 0;
let currentRequisitionId = null;

// View Requisition Functions
async function viewRequisition(reqId) {
    console.log('View button clicked for requisition ID:', reqId);
    currentRequisitionId = reqId;
    
    // Show modal immediately with loading state
    document.getElementById('viewRequestModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Show loading, hide data
    document.getElementById('viewModalLoading').classList.remove('hidden');
    document.getElementById('viewModalData').classList.add('hidden');
    
    try {
        const url = `/requisition/${reqId}/details`;
        console.log('Fetching from URL:', url);
        
        const response = await fetch(url);
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const requisition = await response.json();
        console.log('Received requisition data:', requisition);
        
        populateViewModal(requisition);
        
    } catch (error) {
        console.error('Error loading requisition details:', error);
        
        // Show error message
        document.getElementById('viewModalLoading').innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-600 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-red-700">Error Loading Details</h3>
                <p class="text-gray-600 mb-4">${error.message}</p>
                <button onclick="closeViewRequestModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Close
                </button>
            </div>
        `;
    }
}

function populateViewModal(requisition) {
    console.log('Populating modal with:', requisition);
    
    // Populate basic details
    document.getElementById('viewReqNumber').textContent = requisition.req_num || 'N/A';
    document.getElementById('viewSupplier').textContent = requisition.supplier?.s_name || 'N/A';
    document.getElementById('viewRequestedBy').textContent = `${requisition.employee?.f_name || ''} ${requisition.employee?.l_name || ''}`.trim() || 'N/A';
    document.getElementById('viewRequestDate').textContent = formatDate(requisition.request_date);
    document.getElementById('viewRequiredDate').textContent = formatDate(requisition.require_date);
    document.getElementById('viewRemarks').textContent = requisition.remarks || 'No remarks';
    
    // Populate status with appropriate styling
    const statusElement = document.getElementById('viewStatus');
    const status = requisition.req_status || 'pending';
    statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    
    // Remove existing classes and add appropriate ones
    statusElement.className = 'text-sm ml-2 px-3 py-1 rounded-full text-sm';
    if (status === 'pending') {
        statusElement.classList.add('bg-yellow-100', 'text-yellow-700');
    } else if (status === 'approved') {
        statusElement.classList.add('bg-green-100', 'text-green-700');
    } else if (status === 'declined') {
        statusElement.classList.add('bg-red-100', 'text-red-700');
    } else {
        statusElement.classList.add('bg-gray-100', 'text-gray-700');
    }
    
    // Populate items table
    const itemsTable = document.getElementById('viewItemsTable');
    if (requisition.items && requisition.items.length > 0) {
        itemsTable.innerHTML = requisition.items.map(item => `
            <tr class="border-b border-gray-300">
                <td class="p-2 border border-gray-300">${item.product?.prod_name || 'N/A'}</td>
                <td class="p-2 border border-gray-300">${item.quantity || 0}</td>
                <td class="p-2 border border-gray-300">${item.unit || 'N/A'}</td>
                <td class="p-2 border border-gray-300">${item.remarks || ''}</td>
                <td class="p-2 border border-gray-300">
                    <span class="px-2 py-1 rounded-full text-xs 
                        ${(item.status === 'available') ? 'bg-green-100 text-green-700' : 
                          (item.status === 'out_of_stock') ? 'bg-red-100 text-red-700' : 
                          'bg-gray-100 text-gray-700'}">
                        ${item.status ? item.status.replace('_', ' ').charAt(0).toUpperCase() + item.status.slice(1) : 'Pending'}
                    </span>
                </td>
            </tr>
        `).join('');
    } else {
        itemsTable.innerHTML = `
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">
                    No items found for this requisition.
                </td>
            </tr>
        `;
    }
    
    // Populate SMS logs
    const smsLogsContent = document.getElementById('smsLogsContent');
    if (requisition.sms_logs && requisition.sms_logs.length > 0) {
        smsLogsContent.innerHTML = requisition.sms_logs.map(log => `
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-700">Last SMS Sent:</span>
                    <span class="text-sm text-gray-600">${formatDate(log.created_at, true)}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="text-sm ml-2 ${log.status === 'delivered' ? 'text-green-600' : 'text-yellow-600'}">
                        ${(log.status || '').charAt(0).toUpperCase() + (log.status || '').slice(1)}
                    </span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-700">Message:</span>
                    <p class="text-sm mt-1 bg-white p-2 rounded border">${log.message || 'No message'}</p>
                </div>
                ${log.response ? `
                <div>
                    <span class="text-sm font-medium text-gray-700">Response:</span>
                    <p class="text-sm mt-1 bg-blue-50 p-2 rounded border">${log.response}</p>
                </div>
                ` : ''}
            </div>
        `).join('');
    } else {
        smsLogsContent.innerHTML = `
            <div class="text-center text-gray-500 py-4">
                No SMS logs available for this requisition.
            </div>
        `;
    }
    
    // Show/hide Convert to PO button based on permissions and status
    const convertBtn = document.getElementById('convertToPOBtn');
    const userPosition = '{{ auth()->user()->position }}';
    const canConvert = (userPosition === 'owner' || userPosition === 'purchasor') && (requisition.req_status === 'pending');
    
    if (canConvert) {
        convertBtn.classList.remove('hidden');
    } else {
        convertBtn.classList.add('hidden');
    }
    
    // Hide loading and show data
    document.getElementById('viewModalLoading').classList.add('hidden');
    document.getElementById('viewModalData').classList.remove('hidden');
}

function formatDate(dateString, includeTime = false) {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return 'Invalid Date';
    
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    };
    
    if (includeTime) {
        options.hour = '2-digit';
        options.minute = '2-digit';
    }
    
    return date.toLocaleDateString('en-US', options);
}

function closeViewRequestModal() {
    document.getElementById('viewRequestModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    currentRequisitionId = null;
    
    // Reset modal state for next open
    document.getElementById('viewModalLoading').classList.remove('hidden');
    document.getElementById('viewModalData').classList.add('hidden');
    
    // Reset loading content
    document.getElementById('viewModalLoading').innerHTML = `
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-lg font-medium">Loading Requisition Details</p>
        <p class="text-sm text-gray-600 mt-2">Please wait...</p>
    `;
}

function convertCurrentToPO() {
    if (currentRequisitionId) {
        convertToPO(currentRequisitionId);
        closeViewRequestModal();
    }
}

// Existing functions from your code
function convertToPO(reqId) {
    if (confirm('Are you sure you want to convert this requisition to a Purchase Order?')) {
        window.location.href = '/requisition/' + reqId + '/convert-to-po';
    }
}

function openNewRequestModal() {
    document.getElementById('newRequestModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    generateRequestNumber();
    resetForm();
}

function closeNewRequestModal() {
    document.getElementById('newRequestModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openAddItemModal() {
    console.log('Opening Add Item Modal - requestedItems count:', requestedItems.length);
    document.getElementById('addItemModal').classList.remove('hidden');
}

function closeAddItemModal() {
    console.log('Closing Add Item Modal');
    document.getElementById('addItemModal').classList.add('hidden');
    resetAddItemForm();
}

function resetAddItemForm() {
    console.log('Resetting Add Item Form');
    document.getElementById('itemNameSelect').value = '';
    document.getElementById('itemQuantity').value = '1';
    document.getElementById('itemUnit').value = 'pcs';
    document.getElementById('itemRemarks').value = '';
}

function generateRequestNumber() {
    const timestamp = new Date().getTime();
    const random = Math.floor(Math.random() * 1000);
    const requestNumber = 'REQ-' + timestamp + random;
    document.getElementById('requestNumber').value = requestNumber;
}

function updateSupplierDisplay() {
    const supplierSelect = document.getElementById('supplierSelect');
    const displaySpan = document.getElementById('selectedSupplierDisplay');
    const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
    
    if (supplierSelect.value) {
        displaySpan.textContent = selectedOption.text;
        displaySpan.className = 'text-sm ml-2 font-semibold text-blue-600';
    } else {
        displaySpan.textContent = 'Please select a supplier';
        displaySpan.className = 'text-sm ml-2 text-gray-500';
    }
}

// Auto-set unit when product is selected
document.getElementById('itemNameSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const unit = selectedOption.getAttribute('data-unit');
    if (unit) {
        document.getElementById('itemUnit').value = unit;
    }
});

function resetAddItemForm() {
    document.getElementById('itemNameSelect').value = '';
    document.getElementById('itemQuantity').value = '1';
    document.getElementById('itemUnit').value = 'pcs';
    document.getElementById('itemRemarks').value = '';
}
// Item Management
function addItemToList() {
    const itemSelect = document.getElementById('itemNameSelect');
    const productId = itemSelect.value;
    const productName = itemSelect.options[itemSelect.selectedIndex].text;
    const quantity = document.getElementById('itemQuantity').value;
    const unit = document.getElementById('itemUnit').value;
    const remarks = document.getElementById('itemRemarks').value;

    if (!productId) {
        alert('Please select an item');
        return;
    }

    if (!quantity || quantity < 1) {
        alert('Please enter a valid quantity');
        return;
    }

    const newItem = {
        id: itemCounter++,
        product_id: productId,
        product_name: productName,
        quantity: parseInt(quantity),
        unit: unit,
        remarks: remarks || ''
    };

    requestedItems.push(newItem);
    updateItemsTable();
    
    // Only call closeAddItemModal() - it will handle resetting the form
    closeAddItemModal();
}

function removeItem(itemId) {
    console.log('Removing item with ID:', itemId);
    console.log('Items before removal:', requestedItems);
    
    requestedItems = requestedItems.filter(function(item) {
        return item.id !== itemId;
    });
    
    console.log('Items after removal:', requestedItems);
    updateItemsTable();
}

function updateItemsTable() {
    const tableBody = document.getElementById('requestedItemsTable');
    const noItemsRow = document.getElementById('noItemsRow');
    
    console.log('Updating items table, requestedItems count:', requestedItems.length);
    console.log('Table body found:', !!tableBody);
    console.log('No items row found:', !!noItemsRow);
    
    // Check if elements exist
    if (!tableBody) {
        console.error('Requested items table body not found');
        return;
    }
    
    if (requestedItems.length === 0) {
        if (noItemsRow) {
            noItemsRow.style.display = '';
        }
        // Clear any existing rows except the noItemsRow
        tableBody.innerHTML = '<tr id="noItemsRow"><td colspan="5" class="border border-gray-300 p-4 text-center text-gray-500">No items added yet. Click "Add Item" to get started.</td></tr>';
        return;
    }
    
    // Hide the "no items" row if it exists
    if (noItemsRow) {
        noItemsRow.style.display = 'none';
    }
    
    let tableHTML = '';
    requestedItems.forEach(function(item) {
        tableHTML += '<tr class="border-b border-gray-300">';
        tableHTML += '<td class="p-2 border border-gray-300">' + (item.product_name || 'N/A') + '</td>';
        tableHTML += '<td class="p-2 border border-gray-300">' + (item.quantity || 0) + '</td>';
        tableHTML += '<td class="p-2 border border-gray-300">' + (item.unit || 'N/A') + '</td>';
        tableHTML += '<td class="p-2 border border-gray-300">' + (item.remarks || '') + '</td>';
        tableHTML += '<td class="p-2 border border-gray-300">';
        tableHTML += '<button type="button" onclick="removeItem(' + item.id + ')" ';
        tableHTML += 'class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition text-xs">';
        tableHTML += 'Remove</button>';
        tableHTML += '</td>';
        tableHTML += '</tr>';
    });
    
    tableBody.innerHTML = tableHTML;
    console.log('Table updated with HTML:', tableHTML);
}

function resetAddItemForm() {
    document.getElementById('itemNameSelect').value = '';
    document.getElementById('itemQuantity').value = '1';
    document.getElementById('itemUnit').value = 'pcs';
    document.getElementById('itemRemarks').value = '';
}

function resetForm() {
    requestedItems = [];
    itemCounter = 0;
    document.getElementById('supplierSelect').value = '';
    document.getElementById('itemsData').value = '';
    updateSupplierDisplay();
    updateItemsTable();
}

function submitRequisition() {
    if (requestedItems.length === 0) {
        alert('Please add at least one item before submitting the requisition.');
        return;
    }

    if (!document.getElementById('supplierSelect').value) {
        alert('Please select a supplier before submitting the requisition.');
        return;
    }

    // Validate required date
    const requireDateInput = document.getElementById('requireDate');
    const requireDate = new Date(requireDateInput.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (requireDate < today) {
        alert('The required date cannot be earlier than today. Please select a valid date.');
        requireDateInput.focus();
        return;
    }

    // Prepare items data for form submission - FIXED FIELD NAMES
    const itemsData = requestedItems.map(function(item) {
        return {
            prod_id: item.product_id, // Make sure this matches your database column name
            quantity: item.quantity,   // This should be 'quantity' not 'qty'
            unit: item.unit,
            remarks: item.remarks
        };
    });

    console.log('Submitting items:', itemsData); // Debug log

    // Set the hidden input value
    document.getElementById('itemsData').value = JSON.stringify(itemsData);

    // Submit the form
    document.getElementById('requisitionForm').submit();
}

// Event Listeners
document.getElementById('supplierSelect').addEventListener('change', updateSupplierDisplay);

// Modal close event listeners
document.getElementById('newRequestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNewRequestModal();
    }
});

document.getElementById('addItemModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddItemModal();
    }
});

document.getElementById('viewRequestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewRequestModal();
    }
});

// Escape key listener
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNewRequestModal();
        closeAddItemModal();
        closeViewRequestModal();
    }
});
</script>

<style>
.overflow-hidden {
    overflow: hidden;
}
</style>
@endsection