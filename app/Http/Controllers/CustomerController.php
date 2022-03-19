<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Currency;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = $this->filter($request)->paginate(10)->withQueryString();
        return view('customers.index',compact('customers'));
    }

    private function filter(Request $request)
    {
        $query = Customer::orderBy('id','DESC');
        if ($request->name)
            $query->where('name', 'like', $request->name.'%');

        if ($request->phone)
            $query->where('phone', 'like', $request->phone.'%');

        if ($request->email)
            $query->where('email', 'like', $request->email.'%');

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        return view('customers.create',compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);
        $data = $request->only(['name','email','tax_number','phone','address','website','currency_code','enabled','reference']);
        $data['company_id'] = session('company_id');
        Customer::create($data);
        return redirect()->route('customer.index')->with('success', trans('Customer Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        return view('customers.edit', compact('customer', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $this->validation($request, $customer->id);
        $data = $request->only(['name','email','tax_number','phone','address','website','currency_code','enabled','reference']);
        $customer->update($data);
        return redirect()->route('customer.index')->with('success', trans('Customer Edit Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customer.index')->with('success', trans('Customer Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:customers,email,'.$id, 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:14'],
            'address' => ['nullable', 'string', 'max:255'],
            'currency_code' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:14'],
            'enabled' => ['required', 'in:0,1'],
            'reference' => ['nullable', 'string', 'max:255']
        ]);
    }
}
