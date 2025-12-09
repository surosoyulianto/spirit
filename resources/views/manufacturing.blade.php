<section class="bg-white p-4 rounded-lg shadow-sm mt-6">
    <h3 class="font-semibold mb-4">🛠 Manufacturing - Work Orders</h3>
    <table class="min-w-full border rounded-lg">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Order #</th>
                <th class="px-4 py-2 text-left">Product</th>
                <th class="px-4 py-2 text-left">Qty</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-4 py-2">WO-0001</td>
                <td class="px-4 py-2">Produk A</td>
                <td class="px-4 py-2">100</td>
                <td class="px-4 py-2"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">In Progress</span></td>
                <td class="px-4 py-2">
                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">View</button>
                    <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Complete</button>
                </td>
            </tr>
            <tr>
                <td class="px-4 py-2">WO-0002</td>
                <td class="px-4 py-2">Produk B</td>
                <td class="px-4 py-2">50</td>
                <td class="px-4 py-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded">Done</span></td>
                <td class="px-4 py-2">
                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">View</button>
                </td>
            </tr>
            <tr>
                <td class="px-4 py-2">WO-0003</td>
                <td class="px-4 py-2">Produk C</td>
                <td class="px-4 py-2">200</td>
                <td class="px-4 py-2"><span class="bg-red-100 text-red-800 px-2 py-1 rounded">Waiting</span></td>
                <td class="px-4 py-2">
                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">View</button>
                    <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Start</button>
                </td>
            </tr>
        </tbody>
    </table>
</section>