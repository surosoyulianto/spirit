<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class InvoiceForm extends Component
{
    public $invoice_number;
    public $customer_id;
    public $invoice_date;
    public $items = [];

    public function mount()
    {
        $this->invoice_date = Carbon::now()->format('Y-m-d');
        $this->items = [
            ['description' => '', 'quantity' => 1, 'unit_price' => 0],
        ];
    }

    public function addItem()
    {
        $this->items[] = ['description' => '', 'quantity' => 1, 'unit_price' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // reindex
    }

    public function getTotalProperty()
    {
        return collect($this->items)->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
    }

    public function save()
    {
        $this->validate([
            'invoice_number' => 'required|unique:invoices',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $this->invoice_number,
            'customer_id' => $this->customer_id,
            'invoice_date' => $this->invoice_date,
            'total' => $this->total,
            'status' => 'draft',
        ]);

        foreach ($this->items as $item) {
            $invoice->items()->create($item);
        }

        session()->flash('success', 'Invoice berhasil disimpan.');
        return redirect()->route('invoices.index'); // pastikan route ini tersedia
    }

    public function render()
    {
        return view('livewire.invoice-form', [
            'customers' => Customer::all(),
        ]);
    }
}

