<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Add Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.3/dist/cdn.min.js" defer></script>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 gap-8">
            
            <!-- Section 1: Shipping -->
            <div class="w-full">
                <h2 class="text-2xl font-semibold mb-4">Shipping</h2>
                
                <!-- Add Shipping Form -->
                <form action="{{ route('shippings.store') }}" method="POST" class="space-y-4 mb-8">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                        <div class="col-span-1 sm:col-span-1">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="product_id" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label for="picked" class="block text-sm font-medium text-gray-700">Picked</label>
                            <input type="number" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="picked" required>
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label for="rto" class="block text-sm font-medium text-gray-700">RTO</label>
                            <input type="number" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="rto" required>
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="date" required>
                        </div>
                        <div class="col-span-1 sm:col-span-1 flex items-end">
                            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add Shipping</button>
                        </div>
                    </div>
                </form>

                <!-- Shipping List -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Picked</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RTO</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($shippings as $shipping)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shipping->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shipping->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shipping->picked }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shipping->rto }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shipping->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-data="{ open: false }">
                                    <button @click="open = true" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('shippings.destroy', $shipping->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900" onclick="return confirmDelete()">Delete</button>
                                    </form>
                                    
                                    <!-- Edit Shipping Modal -->
                                    <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 flex items-center justify-center z-50">
                                        <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>
                                        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
                                            <div class="modal-header p-4 border-b">
                                                <h5 class="text-lg font-semibold">Edit Shipping</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600" @click="open = false">&times;</button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="{{ route('shippings.update', $shipping->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4">
                                                        <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                                                        <select name="product_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}" {{ $shipping->product_id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="picked" class="block text-sm font-medium text-gray-700">Picked</label>
                                                        <input type="number" name="picked" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $shipping->picked }}" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="rto" class="block text-sm font-medium text-gray-700">RTO</label>
                                                        <input type="number" name="rto" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $shipping->rto }}" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                                        <input type="date" name="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $shipping->date }}" required>
                                                    </div>
                                                    <div class="modal-footer p-4 border-t">
                                                        <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                                                        <button type="submit" class="bg-black text-white font-semibold py-2 px-4 border border-gray-400 rounded shadow ">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Section 2: Purchase -->
            <div class="w-full mt-10">
                <h2 class="text-2xl font-semibold mb-4">Purchase</h2>
                
                <!-- Add Purchase Form -->
                <form action="{{ route('purchases.store') }}" method="POST" class="space-y-4 mb-8">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                        <div class="col-span-1 sm:col-span-1">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="product_id" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="quantity" required>
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="price" step="0.01" required>
                        </div>
                        <div class="col-span-1 sm:col-span-1">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="date" required>
                        </div>
                        <div class="col-span-1 sm:col-span-1 flex items-end">
                            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add Purchase</button>
                        </div>
                    </div>
                </form>

                <!-- Purchase List -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($purchases as $purchase)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-data="{ open: false }">
                                    <button @click="open = true" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900" onclick="return confirmDelete()">Delete</button>
                                    </form>
                                    
                                    <!-- Edit Purchase Modal -->
                                    <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 flex items-center justify-center z-50">
                                        <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>
                                        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
                                            <div class="modal-header p-4 border-b">
                                                <h5 class="text-lg font-semibold">Edit Purchase</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600" @click="open = false">&times;</button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4">
                                                        <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                                                        <select name="product_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}" {{ $purchase->product_id == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                                        <input type="number" name="quantity" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $purchase->quantity }}" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                                        <input type="number" name="price" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $purchase->price }}" step="0.01" required>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                                        <input type="date" name="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $purchase->date }}" required>
                                                    </div>
                                                    <div class="modal-footer p-4 border-t">
                                                        <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                                                        <button type="submit" class="btn btn-primary bg-black text-white font-semibold py-2 px-4 border border-gray-400 rounded shadow ">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Section 3: Product -->
            <div class="w-full mt-10">
                <h2 class="text-2xl font-semibold mb-4">Product</h2>
                
                <!-- Add Product Form -->
                <form action="{{ route('products.store') }}" method="POST" class="space-y-4 mb-8">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                        <div class="col-span-1 sm:col-span-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="name" required>
                        </div>
                        <div class="col-span-1 sm:col-span-1 flex items-end">
                            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Add Product</button>
                        </div>
                    </div>
                </form>

                <!-- Product List -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div x-data="{ open: false }">
                                    <button @click="open = true" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900" onclick="return confirmDelete()">Delete</button>
                                    </form>
                                    
                                    <!-- Edit Product Modal -->
                                    <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 flex items-center justify-center z-50">
                                        <div class="fixed inset-0 bg-black bg-opacity-50" @click="open = false"></div>
                                        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
                                            <div class="modal-header p-4 border-b">
                                                <h5 class="text-lg font-semibold">Edit Product</h5>
                                                <button type="button" class="text-gray-400 hover:text-gray-600" @click="open = false">&times;</button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="{{ route('products.update', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-4">
                                                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                                                        <input type="text" name="name" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $product->name }}" required>
                                                    </div>
                                                    <div class="modal-footer p-4 border-t">
                                                        <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                                                        <button type="submit" class="btn btn-primary bg-black text-white font-semibold py-2 px-4 border border-gray-400 rounded shadow ">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Section 4: Report -->
            <div class="w-full mt-10">
    <h2 class="text-2xl font-semibold mb-4">Report</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $product->name }}</h3>
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Total Sold: {{ $product->total_sold }}</p>
                <p class="text-sm font-medium text-gray-500">Total RTO: {{ $product->total_rto }}</p>
                <p class="text-sm font-medium text-gray-500">Total Product: {{ $product->total_product }}</p>
                <p class="text-sm font-medium text-gray-500">Total Remain Product: {{ $product->total_remain }}</p>
                <p class="text-sm font-medium text-gray-500">Total Amount Paid: ₹{{ number_format($product->total_amount_paid, 2) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-10">
        <h3 class="text-2xl font-semibold mb-4">Aggregate Report</h3>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <p class="text-sm font-medium text-gray-500">Total Sold: {{ $total_sold_all }}</p>
            <p class="text-sm font-medium text-gray-500">Total RTO: {{ $total_rto_all }}</p>
            <p class="text-sm font-medium text-gray-500">Total Product: {{ $total_product_all }}</p>
            <p class="text-sm font-medium text-gray-500">Total Remain Product: {{ $total_remain_all }}</p>
            <p class="text-sm font-medium text-gray-500">Total Amount Paid: ₹{{ number_format($total_amount_paid_all, 2) }}</p>
        </div>
    </div>
</div>
<div class="w-full mt-10">
    <h2 class="text-2xl font-semibold mb-4">Change Logs</h2>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 h-96 overflow-y-auto">
        @foreach($logs as $log)
        <div class="mb-4">
            <p class="text-sm font-medium text-gray-700">
                {{ $log->user->name }} 
                <span class="text-gray-500">({{ $log->user->email }})</span> 
                made a {{ $log->action }} on {{ $log->model }} (ID: {{ $log->model_id }})
                on {{ \Carbon\Carbon::parse($log->created_at)->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}
            </p>
            
            <!-- Display Changes -->
            <div class="overflow-auto text-xs bg-gray-100 p-2 rounded">
                @php
                    $changes = json_decode($log->changes);
                    $before = $changes->before ?? null;
                    $after = $changes->after ?? null;
                @endphp

                @if($before && $after)
                    @foreach($before as $key => $value)
                        @if($key !== 'created_at' && $key !== 'updated_at')
                            @if($value != $after->$key)
                                <p><strong>{{ ucfirst($key) }}:</strong> Changed from "{{ $value }}" to "{{ $after->$key }}"</p>
                            @else
                                <p><strong>{{ ucfirst($key) }}:</strong> No change (remained "{{ $value }}")</p>
                            @endif
                        @endif
                    @endforeach
                @elseif($before)
                    <p><strong>Data before deletion:</strong></p>
                    @foreach($before as $key => $value)
                        <p><strong>{{ ucfirst($key) }}:</strong> "{{ $value }}"</p>
                    @endforeach
                @else
                    <p>No before and after data available for this log entry.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this item? This action cannot be undone.');
        }
    </script>
</x-app-layout>
