@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">🛠 Manufacturing Order Detail</h1>

    <div class="bg-white p-6 rounded shadow space-y-4">
        <div>
            <strong>Reference:</strong> MO0001
        </div>
        <div>
            <strong>Product:</strong> Table
        </div>
        <div>
            <strong>Quantity:</strong> 5
        </div>
        <div>
            <strong>Status:</strong> <span class="text-green-600">Confirmed</span>
        </div>
    </div>
</div>
@endsection
