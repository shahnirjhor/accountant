<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Session;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendors = $this->filter($request)->paginate(10)->withQueryString();
        return view('vendors.index',compact('vendors'));
    }

    private function filter(Request $request)
    {
        $query = Vendor::orderBy('id','DESC');
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
        return view('vendors.create',compact('currencies'));
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
        Vendor::create($data);
        return redirect()->route('vendor.index')->with('success', trans('Vendor Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        $currencies = Currency::where('company_id', Session::get('company_id'))->where('enabled', 1)->pluck('name', 'code');
        return view('vendors.edit', compact('vendor', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        $this->validation($request, $vendor->id);
        $data = $request->only(['name','email','tax_number','phone','address','website','currency_code','enabled','reference']);
        $vendor->update($data);
        return redirect()->route('vendor.index')->with('success', trans('Vendor Edit Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', trans('Vendor Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:vendors,email,'.$id, 'max:255'],
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
