<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Category;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Item;
use App\Models\Tax;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Session;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $bills = $this->filter($request)->paginate(10)->withQueryString();
        return view('bills.index',compact('company','bills'));
    }

    private function filter(Request $request)
    {
        $query = Bill::with('vendor:id,name')->where('company_id', session('company_id'))->latest();
        // if ($request->invoice_number)
        //     $query->where('invoice_number', 'like', $request->invoice_number.'%');
        // if($request->amount)
        //     $query->where('amount', 'like', $request->amount.'%');
        // if($request->invoiced_at)
        //     $query->where('invoiced_at', 'like', $request->invoiced_at.'%');

        return $query;
    }

    /**
     * Generate next invoice number
     *
     * @return string
     */
    public function getNextBillNumber($company)
    {
        $prefix = $company->invoice_number_prefix;
        $next = $company->invoice_number_next;
        $digit = $company->invoice_number_digit;
        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
        return $number;
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
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        $currency = Currency::where('company_id', Session::get('company_id'))->where('code', '=', $company->default_currency)->first();
        $items = Item::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', Session::get('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('title', 'id');
        $categories = Category::where('company_id', Session::get('company_id'))->where('enabled', 1)->where('type', 'expense')->orderBy('name')->pluck('name', 'id');
        $number = $this->getNextBillNumber($company);

        return view('bill.create', compact('company','vendors', 'currencies', 'currency', 'items', 'taxes', 'categories','number'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
