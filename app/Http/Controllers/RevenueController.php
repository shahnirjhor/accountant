<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Revenue;
use App\Models\Category;
use App\Models\OfflinePayment;
use Illuminate\Http\Request;
use Session;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $Categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        return view('revenues.create', compact('company','accounts','customers','Categories','payment_methods'));
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
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function show(Revenue $revenue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function edit(Revenue $revenue)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $Categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        return view('revenues.edit', compact('company','accounts','customers','Categories','payment_methods','revenue'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revenue $revenue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Revenue  $revenue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revenue $revenue)
    {
        //
    }
}
