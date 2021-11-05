<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $taxes = $this->filter($request)->paginate(10)->withQueryString();
        return view('tax.index',compact('taxes'));
    }

    private function filter(Request $request)
    {
        $query = Tax::where('company_id', session('company_id'))->latest();
        if ($request->name)
            $query->where('name', 'like', $request->name.'%');

        if (isset($request->type))
            $query->where('type', $request->type);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tax.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'rate' => 'required|numeric',
            'type' => 'required',
            'enabled' => 'required',
        ]);

        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $data = new Tax;
            $data->company_id = session('company_id');
            $data->name = $request->name;
            $data->rate = $request->rate;
            $data->type = $request->type;
            $data->enabled = $request->enabled;
            $data->save();

            DB::commit();
            return redirect()->route('tax.index')->withSuccess(trans('tax.tax information inserted successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function show(Tax $tax)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Tax $tax)
    {
        return view('tax.edit', ['data' => $tax]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tax $tax)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'rate' => 'required|numeric',
            'type' => 'required',
            'enabled' => 'required',
        ]);

        /**
         * Method to call db transaction
         */
        DB::beginTransaction();
        try {
            $data = $tax;
            $data->company_id = session('company_id');
            $data->name = $request->name;
            $data->rate = $request->rate;
            $data->type = $request->type;
            $data->enabled = $request->enabled;
            $data->save();

            DB::commit();
            return redirect()->route('tax.index')->withSuccess(trans('tax.tax information updated successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tax  $tax
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tax $tax)
    {
        $tax->delete();
        return redirect()->route('tax.index')->withSuccess(trans('tax.your tax info has been deleted successfully'));
    }

    // TODO: 'items' => 'items',
            // 'invoice_items' => 'invoices',
            // 'bill_items' => 'bills',

            // public function destroy(Tax $tax)
            // {
            //     $relationships = $this->countRelationships($tax, [
            //         'items' => 'items',
            //         'invoice_items' => 'invoices',
            //         'bill_items' => 'bills',
            //     ]);
        
            //     if (empty($relationships)) {
            //         $tax->delete();
        
            //         $message = trans('messages.success.deleted', ['type' => trans_choice('general.taxes', 1)]);
        
            //         return response()->json([
            //             'success' => true,
            //             'error' => false,
            //             'message' => $message,
            //             'data' => $tax,
            //         ]);
            //     } else {
            //         $message = trans('messages.warning.deleted', ['name' => $tax->name, 'text' => implode(', ', $relationships)]);
        
            //         return response()->json([
            //             'success' => false,
            //             'error' => true,
            //             'message' => $message,
            //             'data' => $tax,
            //         ]);
            //     }
            // }
}
