<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Tax;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\DB;

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


        $validatedData = $request->validate([
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

        DB::transaction(function () use ($data) {
            $invoice = Invoice::create($data);
        });

        //$items = Item::with('tax:id,rate,type')->where('company_id', session('company_id'))->whereIn('id', $request->product['order_row_id'])->get();


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
