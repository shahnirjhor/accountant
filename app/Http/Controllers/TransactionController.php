<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Revenue;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
        $transactions = $this->filter($request);
        return view('transactions.index',compact('transactions','company'));
    }

    private function filter()
    {
        $type = null;
        $paymentTransactions = [];
        if ($type != 'income') {
            $payments = Payment::orderBy('id', 'DESC')->where('company_id', Session::get('company_id'))->paginate(5);
            $paymentTransactions = $this->addTransactions($payments, "Expense");
        }

        $revenueTransactions = [];
        if ($type != 'expense') {
            $revenues = Revenue::orderBy('id', 'DESC')->where('company_id', Session::get('companyInfo'))->paginate(5);
            $revenueTransactions = $this->addTransactions($revenues, "Income");
        }

        $myTransactions = array_merge($paymentTransactions,$revenueTransactions);

        return $myTransactions;
    }

    protected function addTransactions($items, $type)
    {
        $transactions = [];
        foreach ($items as $item) {
            $transactions[] = [
                'paid_at'           => date("d M Y", strtotime($item->paid_at)),
                'account_name'      => $item->account->name,
                'type'              => $type,
                'description'       => $item->description,
                'amount'            => $item->amount,
                'currency_code'     => $item->currency_code,
                'category_name'     => $item->category->name
            ];
        }
        return $transactions;
    }
}
