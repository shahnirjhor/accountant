<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Company;
use App\Models\Currency;
use App\Models\OfflinePayment;
use App\Models\Payment;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Session;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $revenues = $this->filter($request)->paginate(10);
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        return view('payments.index',compact('revenues','vendors','categories','accounts','company'));
    }

    private function filter(Request $request)
    {
        $query = Payment::where('company_id', session('company_id'))->latest();

        if ($request->paid_at)
            $query->where('paid_at', 'like', $request->paid_at.'%');

        if ($request->vendor_id)
            $query->where('vendor_id', $request->vendor_id);

        if ($request->category_id)
            $query->where('category_id', $request->category_id);

        if ($request->account_id)
            $query->where('account_id', $request->account_id);

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
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        $account_currency_code = Account::where('id', $company->default_account)->pluck('currency_code')->first();
        $currency = Currency::where('code', $account_currency_code)->first();
        return view('payments.create', compact('company','accounts','vendors','categories','payment_methods','account_currency_code','currency'));
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
        $data = $request->only(['paid_at','amount','account_id','vendor_id','category_id','payment_method','description','attachment','currency_code','currency_rate']);
        $data['company_id'] = session('company_id');
        Payment::create($data);
        return redirect()->route('payment.index')->with('success', trans('Payment Added Successfully'));
    }

    public function validation(Request $request, $id = 0)
    {
        $request->validate([
            'paid_at' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'account_id' => ['required', 'integer'],
            'vendor_id' => ['required', 'integer'],
            'category_id' => ['required', 'integer'],
            'payment_method' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'currency_code' => ['required', 'string', 'max:255'],
            'currency_rate' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->pluck('name', 'id');
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');
        $payment_methods = OfflinePayment::where('company_id', session('company_id'))->orderBy('name')->pluck('name', 'code');
        $account_currency_code = Account::where('id', $company->default_account)->pluck('currency_code')->first();
        $currency = Currency::where('code', $account_currency_code)->first();
        return view('payments.edit', compact('company','accounts','vendors','categories','payment_methods','account_currency_code','currency','payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $this->validation($request);
        $data = $request->only(['paid_at','amount','account_id','vendor_id','category_id','payment_method','description','attachment','currency_code','currency_rate']);
        $payment->update($data);
        return redirect()->route('payment.index')->with('success', trans('Payment Edit Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payment.index')->withSuccess(trans('Your Payment Has Been Deleted Successfully'));
    }
}
