<x-filament::page>
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Add New Product</h2>
        {{ $this->form }}
    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h2 class="text-lg font-semibold mb-4">Recently Added Products</h2>
        <table class="min-w-full bg-white border rounded-lg shadow">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Product Name</th>
                    <th class="px-4 py-2 border">Barcode</th>
                    <th class="px-4 py-2 border">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentProducts as $product)
                    <tr>
                        <td class="px-4 py-2 border">{{ $product->id }}</td>
                        <td class="px-4 py-2 border">{{ $product->name }}</td>
                        <td class="px-4 py-2 border">{{ $product->barcode }}</td>
                        <td class="px-4 py-2 border">{{ $product->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament::page>
