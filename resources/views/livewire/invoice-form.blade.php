<div>
    <h2 class="text-2xl font-bold mb-4">Buat Faktur</h2>

    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label>No. Faktur</label>
            <input wire:model="invoice_number" type="text" class="form-control">
            @error('invoice_number') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Pelanggan</label>
            <select wire:model="customer_id" class="form-control">
                <option value="">-- pilih --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            @error('customer_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Tanggal Faktur</label>
            <input wire:model="invoice_date" type="date" class="form-control">
            @error('invoice_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <h4 class="mt-4">Item</h4>

        @foreach($items as $index => $item)
            <div class="row mb-2">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Deskripsi"
                           wire:model="items.{{ $index }}.description">
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="Qty"
                           wire:model="items.{{ $index }}.quantity" min="1">
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="Harga Satuan"
                           wire:model="items.{{ $index }}.unit_price" min="0">
                </div>
                <div class="col">
                    <button type="button" class="btn btn-danger" wire:click="removeItem({{ $index }})">Hapus</button>
                </div>
            </div>
        @endforeach

        <button type="button" class="btn btn-secondary mb-3" wire:click="addItem">+ Tambah Item</button>

        <div class="mb-3">
            <strong>Total: </strong> Rp {{ number_format($this->total, 0, ',', '.') }}
        </div>

        <button type="submit" class="btn btn-primary">Simpan Faktur</button>
    </form>
</div>
