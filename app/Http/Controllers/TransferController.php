<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Revenue;
use App\Models\Payment;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Category;
use App\Models\Transfer;
use App\Models\OfflinePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PayUService\Exception;
use Session;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transfers = $this->filter($request)->paginate(10)->withQueryString();
        return view('transfers.index',compact('transfers'));
    }

    private function filter(Request $request)
    {
        $query = Transfer::with(['payment', 'payment.account', 'revenue', 'revenue.account'])->where('transfers.company_id', session('company_id'))->latest();

        return $query;
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
        $currency = Currency::where('code', '=', $company->default_currency)->first();
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        return view('transfers.create', compact('company', 'currency', 'accounts', 'payment_methods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $currencies = Currency::where('company_id', session('company_id'))->where('enabled', 1)->pluck('rate', 'code')->toArray();

        $payment_currency_code = Account::where('id', $request->from_account)->pluck('currency_code')->first();
        $revenue_currency_code = Account::where('id', $request->to_account)->pluck('currency_code')->first();

        $payment_request = [
            'company_id' => Session::get('company_id'),
            'account_id' => $request->from_account,
            'paid_at' => $request->date,
            'currency_code' => $payment_currency_code,
            'currency_rate' => $currencies[$payment_currency_code],
            'amount' => $request->amount,
            'vendor_id' => 0,
            'description' => $request->description,
            'category_id' => Category::transfer(),
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
        ];
        $payment = Payment::create($payment_request);

        if ($payment_currency_code != $revenue_currency_code) {

            $default_currency = $company->default_currency;
            $default_amount = $request->amount;
            if ($default_currency != $payment_currency_code) {
                $default_amount_model = new Transfer();
                $default_amount_model->default_currency_code = $default_currency;
                $default_amount_model->amount = $request->amount;
                $default_amount_model->currency_code = $payment_currency_code;
                $default_amount_model->currency_rate = $currencies[$payment_currency_code];
                $default_amount = $default_amount_model->getDivideConvertedAmount();
            }
            $transfer_amount = new Transfer();
            $transfer_amount->default_currency_code = $payment_currency_code;
            $transfer_amount->amount = $default_amount;
            $transfer_amount->currency_code = $revenue_currency_code;
            $transfer_amount->currency_rate = $currencies[$revenue_currency_code];
            $amount = $transfer_amount->getDynamicConvertedAmount();

        } else {
            $amount = $request->amount;
        }

        $revenue_request = [
            'company_id' => Session::get('company_id'),
            'account_id' => $request->to_account,
            'paid_at' => $request->date,
            'currency_code' => $revenue_currency_code,
            'currency_rate' => $currencies[$revenue_currency_code],
            'amount' => $amount,
            'customer_id' => 0,
            'description' => $request->description,
            'category_id' => Category::transfer(),
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
        ];
        $revenue = Revenue::create($revenue_request);

        $transfer_request = [
            'company_id' => Session::get('company_id'),
            'payment_id' => $payment->id,
            'revenue_id' => $revenue->id,
        ];
        Transfer::create($transfer_request);
        return redirect()->route('transfer.index')->withSuccess(trans('Transfers Information Inserted Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function edit(Transfer $transfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        //
    }
}
