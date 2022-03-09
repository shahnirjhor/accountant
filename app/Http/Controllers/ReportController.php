<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\DateTime;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use Session;

class ReportController extends Controller
{

    use DateTime;

    public function income(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $incomes = $incomes_graph = $categories = [];
        // $status = request('status');
        $status = 'paid';
        $year = request('year', Carbon::now()->year);
        // check and assign year start
        $financial_start = $this->getFinancialStart();
        if ($financial_start->month != 1) {
            // check if a specific year is requested
            if (!is_null(request('year'))) {
                $financial_start->year = $year;
            }

            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');

        if ($categories_filter = request('categories')) {
            $cats = collect($categories)->filter(function ($value, $key) use ($categories_filter) {
                return in_array($key, $categories_filter);
            });
        } else {
            $cats = $categories;
        }

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->format('F');
            $incomes_graph[Carbon::parse($ym_string)->format('F-Y')] = 0;
            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($cats as $category_id => $category_name) {
                $incomes[$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                ];
            }
        }

        // $financial_ssstart = $this->monthsOfYear('paid_at');

        $revenues = Revenue::monthsOfYear('paid_at')->where('account_id',request('accounts'))->where('customer_id', request('customers'))->isNotTransfer()->get();

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                //$invoices = InvoicePayment::monthsOfYear('paid_at')->where('account_id',request('accounts'))->get(); //need work
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'paid_at');

                // Revenues
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;

            default:
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->where('customer_id', request('customers'))->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'invoiced_at');
                break;
        }

        $statuses = collect([
            'all' => 'All',
            'paid' => 'Paid',
        ]);

        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();

        $myMonth = json_encode(array_values($dates));
        $myIncomesGraph = json_encode(array_values($incomes_graph));


        return view('report.income', compact('dates', 'categories', 'statuses', 'accounts', 'customers', 'incomes', 'totals', 'company', 'myMonth','myIncomesGraph'));
    }

    private function setAmount(&$graph, &$totals, &$incomes, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if ($item->getTable() == 'invoice_payments') {
                $invoice = $item->invoice;

                if ($customers = request('customers')) {
                    if (!in_array($invoice->customer_id, $customers)) {
                        continue;
                    }
                }

                $item->category_id = $invoice->category_id;
            }

            if ($item->getTable() == 'invoices') {
                if ($accounts = request('accounts')) {
                    foreach ($item->payments as $payment) {
                        if (!in_array($payment->account_id, $accounts)) {
                            continue 2;
                        }
                    }
                }
            }

            $month = Carbon::parse($item->$date_field)->format('F');
            $month_year = Carbon::parse($item->$date_field)->format('F-Y');

            if (!isset($incomes[$item->category_id]) || !isset($incomes[$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if (($type == 'invoice') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $incomes[$item->category_id][$month]['amount'] += $amount;
            $incomes[$item->category_id][$month]['currency_code'] = $item->currency_code;
            $incomes[$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            $graph[$month_year] += $amount;

            $totals[$month]['amount'] += $amount;
        }
    }
}
