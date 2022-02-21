<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceHistory;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemTax;
use App\Models\InvoiceTotal;
use App\Models\Item;
use App\Models\Tax;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        $currency = Currency::where('company_id', Session::get('company_id'))->where('code', '=', $company->default_currency)->first();
        $items = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('title', 'id');
        $categories = Category::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $number = $this->getNextInvoiceNumber($company);

        return view('invoices.create', compact('company','customers', 'currencies', 'currency', 'items', 'taxes', 'categories','number'));
    }

    public function generateItemData(Request $request)
    {
        $this->validate($request,[
            'itemId' => 'required'
        ]);
        $item = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('id', $request->itemId)->first();
        if($item) {
            $response['status']  = '1';
            $response['quantity'] = 1;
        } else {
            $response['status']  = '0';
            $response['quantity'] = 0;
        }
        return $response;
    }

    public function getItems(Request $request)
    {
        $q = $request->q;
        $q_a = explode('_', $request->item_array);

        $data = Item::with('tax:id,rate,type')->where('company_id', Session::get('company_id'))
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                      ->orWhere('sku', 'like', '%' . $q . '%');
        })
        ->whereNotIn('id', $q_a)
        ->get();
        return response()->json($data);
    }



        /**
     * Generate next invoice number
     *
     * @return string
     */
    public function getNextInvoiceNumber($company)
    {
        $prefix = $company->invoice_number_prefix;
        $next = $company->invoice_number_next;
        $digit = $company->invoice_number_digit;
        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
        return $number;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => ['required', 'integer'],
            'currency_code' => ['required', 'string'],
            'invoiced_at' => ['required', 'date'],
            'due_at' => ['required', 'date'],
            'invoice_number' => ['required', 'string'],
            'order_number' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer'],
            'grand_total' => ['required', 'numeric'],
            'total_discount' => ['nullable', 'numeric'],
            'total_tax' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string', 'max:1000'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);


        $request->validate([
            "product"    => "required|array",
            "product.*"  => "required",
            "product.order_row_id.*"  => "required",
            "product.order_quantity.*"  => "required",
        ]);

        $customerInfo = Customer::findOrFail($request->customer_id);
        $currencyInfo = Currency::where('company_id', Session::get('company_id'))->where('code', $request->currency_code)->first();

        $data = $request->only(['invoice_number','order_number','invoiced_at','due_at','currency_code','category_id']);
        $data['company_id'] = session('company_id');
        $data['invoice_status_code'] = 'draft';
        $data['amount'] = $request->grand_total;
        $data['currency_rate'] = $currencyInfo->rate;
        $data['customer_id'] = $request->customer_id;
        $data['customer_name'] = $customerInfo->name;
        $data['customer_email'] = $customerInfo->email;
        $data['customer_tax_number'] = $customerInfo->tax_number;
        $data['customer_phone'] = $customerInfo->phone;
        $data['customer_adress'] = $customerInfo->address;
        $data['notes'] = $request->description;
        if ($request->picture) {
            $data['attachment'] = $request->picture->store('invoice');
        }

        DB::transaction(function () use ($data , $request) {
            $invoice = Invoice::create($data);
            $taxes = [];
            $tax_total = 0;
            $sub_total = 0;
            if($request->product) {
                $order_row_id = $keys = $request->product['order_row_id'];
                $order_quantity = $request->product['order_quantity'];
                foreach ($keys as $id => $key) {
                    $item = Item::with('tax:id,rate,type')->where('company_id', session('company_id'))->where('id', $order_row_id[$id])->first();
                    $item_sku = '';
                    $item_id = !empty($item->id) ? $item->id : 0;
                    $item_amount = (double) $item->sale_price * $order_quantity;
                    if (!empty($item_id)) {
                        $item_object = Item::find($item_id);
                        $item_sku = $item_object->sku;
                        // Decrease stock (item sold)
                        $item_object->quantity -= (double) $order_quantity;
                        $item_object->save();
                    } elseif ($item->sku) {
                        $item_sku = $item->sku;
                    }
                    $tax_amount = 0;
                    $item_taxes = [];
                    if (!empty($item->tax_id)) {
                        $taxType = $item->tax->type;
                        $taxRate = $item->tax->rate;
                        if ($taxRate !== null && $taxRate != 0) {
                            if ($taxType == "inclusive") {
                                $tax_amount = (double) (($item->sale_price * $taxRate) / (100 + $taxRate));
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_amount -= $tax_amounts;
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'invoice_id' => $invoice->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            } else {
                                $tax_amount = (double) (($item->sale_price * $taxRate) / 100);
                                $tax_amounts = (double) ($tax_amount * $order_quantity);
                                $item_taxes[] = [
                                    'company_id' => session('company_id'),
                                    'invoice_id' => $invoice->id,
                                    'tax_id' => $item->tax_id,
                                    'name' => $item->tax->name,
                                    'amount' => $tax_amounts,
                                ];
                            }
                        }
                    }

                    $invoice_item = InvoiceItem::create([
                        'company_id' => session('company_id'),
                        'invoice_id' => $invoice->id,
                        'item_id' => $item_id,
                        'name' => Str::limit($item->name, 180, ''),
                        'sku' => $item_sku,
                        'quantity' => (double) $order_quantity,
                        'price' => (double) $item->sale_price,
                        'tax' => $tax_amounts,
                        'total' => $item_amount,
                    ]);

                    $invoice_item->item_taxes = false;

                    // set item_taxes for
                    if (!empty($item->tax_id)) {
                        $invoice_item->item_taxes = $item_taxes;
                    }

                    if ($item_taxes) {
                        foreach ($item_taxes as $item_tax) {
                            $item_tax['invoice_item_id'] = $invoice_item->id;
                            InvoiceItemTax::create($item_tax);

                            // Set taxes
                            if (isset($taxes) && array_key_exists($item_tax['tax_id'], $taxes)) {
                                $taxes[$item_tax['tax_id']]['amount'] += $item_tax['amount'];
                            } else {
                                $taxes[$item_tax['tax_id']] = [
                                    'name' => $item_tax['name'],
                                    'amount' => $item_tax['amount']
                                ];
                            }
                        }
                    }

                    // Calculate totals
                    $tax_total += $invoice_item->tax;
                    $sub_total += $invoice_item->total;

                    if ($invoice_item->item_taxes) {
                        foreach ($invoice_item->item_taxes as $item_tax) {
                            if (isset($taxes) && array_key_exists($item_tax['tax_id'], $taxes)) {
                                $taxes[$item_tax['tax_id']]['amount'] += $item_tax['amount'];
                            } else {
                                $taxes[$item_tax['tax_id']] = [
                                    'name' => $item_tax['name'],
                                    'amount' => $item_tax['amount']
                                ];
                            }
                        }
                    }
                }
            }

            $s_total = $sub_total;
            // Apply discount to total
            if ($request->total_discount) {
                $s_discount = $request->total_discount;
                $s_total = $s_total - $s_discount;
            }
            $amount = $s_total + $tax_total;
            $invoiceData['amount'] = $amount;
            $invoice->update($invoiceData);

            // Add invoice totals
            $this->addTotals($invoice, $request, $taxes, $sub_total, $request->total_discount, $tax_total);
            // Add invoice history
            InvoiceHistory::create([
                'company_id' => session('company_id'),
                'invoice_id' => $invoice->id,
                'status_code' => 'draft',
                'notify' => 0,
                'description' => $invoice->invoice_number." added!",
            ]);

            return redirect()->route('invoice.show', $invoice->id)->with('success', trans('Invoice Added Successfully'));

        });

        //$items = Item::with('tax:id,rate,type')->where('company_id', session('company_id'))->whereIn('id', $request->product['order_row_id'])->get();


    }

    public function addTotals($invoice, $request, $taxes, $sub_total, $discount_total, $tax_total)
    {
        $sort_order = 1;
        // Added invoice sub total
        InvoiceTotal::create([
            'company_id' => session('company_id'),
            'invoice_id' => $invoice->id,
            'code' => 'sub_total',
            'name' => 'invoices.sub_total',
            'amount' => $sub_total,
            'sort_order' => $sort_order,
        ]);
        $sort_order++;
        // Added invoice discount
        if ($discount_total) {
            InvoiceTotal::create([
                'company_id' => session('company_id'),
                'invoice_id' => $invoice->id,
                'code' => 'discount',
                'name' => 'invoices.discount',
                'amount' => $discount_total,
                'sort_order' => $sort_order,
            ]);
            // This is for total
            $sub_total = $sub_total - $discount_total;
            $sort_order++;
        }
        // Added invoice taxes
        if (isset($taxes)) {
            foreach ($taxes as $tax) {
                InvoiceTotal::create([
                    'company_id' => session('company_id'),
                    'invoice_id' => $invoice->id,
                    'code' => 'tax',
                    'name' => $tax['name'],
                    'amount' => $tax['amount'],
                    'sort_order' => $sort_order,
                ]);
                $sort_order++;
            }
        }
        // Added invoice total
        InvoiceTotal::create([
            'company_id' => session('company_id'),
            'invoice_id' => $invoice->id,
            'code' => 'total',
            'name' => 'invoices.total',
            'amount' => $sub_total + $tax_total,
            'sort_order' => $sort_order,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
