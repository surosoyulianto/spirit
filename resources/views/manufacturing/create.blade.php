@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">🛠 Create Manufacturing Order</h1>

    <form action="{{ route('manufacturing.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium">ID Order</label>
            <input type="text" name="id_order" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Product</label>
            <input type="text" name="product" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Quantity</label>
            <input type="number" name="quantity" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="mt-1 block w-full border rounded px-3 py-2" required>
                <option value="draft">Draft</option>
                <option value="confirmed">Confirmed</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
    </form>
</div>
@endsection
