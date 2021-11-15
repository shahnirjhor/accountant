<?php

namespace App\Http\Controllers;

use App\Models\OfflinePayment;
use Illuminate\Http\Request;

class OfflinePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offlinePayments = $this->filter($request)->paginate(10)->withQueryString();
        return view('offline-payments.index',compact('offlinePayments'));
    }

    private function filter(Request $request)
    {
        $query = OfflinePayment::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if($request->code)
            $query->where('code', 'like', '%'.$request->code.'%');

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('offline-payments.create');
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
        $data = $request->only(['name','code','order','show_to_customer','description']);
        $data['company_id'] = session('company_id');
        OfflinePayment::create($data);
        return redirect()->route('offline-payment.index')->with('success', trans('Item Added Successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OfflinePayment  $offlinePayment
     * @return \Illuminate\Http\Response
     */
    public function edit(OfflinePayment $offlinePayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OfflinePayment  $offlinePayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OfflinePayment $offlinePayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OfflinePayment  $offlinePayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(OfflinePayment $offlinePayment)
    {
        //
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'numeric'],
            'show_to_customer' => ['required', 'numeric'],
            'description' => ['nullable', 'string']
        ]);
    }
}
