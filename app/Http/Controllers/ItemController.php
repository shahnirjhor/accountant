<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Company;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Tax;
use Illuminate\Http\Request;
use Session;

class ItemController extends Controller
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
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'item')->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('title', 'id');
        $currency = Currency::where('code', '=', $company->default_currency)->first();
        return view('items.create', compact('categories', 'taxes', 'currency'));
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
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}
