<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Payment;
use App\Models\Revenue;
use App\Models\Tax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\DateTime;
use Session;

class ReportController extends Controller
{

    use DateTime;

    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:income-report-read', ['only' => ['income']]);
        $this->middleware('permission:expense-report-read', ['only' => ['expense']]);
        $this->middleware('permission:income-expense-report-read', ['only' => ['incomeVsexpense']]);
        $this->middleware('permission:tax-report-read', ['only' => ['tax']]);
        $this->middleware('permission:profit-loss-report-read', ['only' => ['profitAndloss']]);
    }

    public function income(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $incomes = $incomes_graph = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;

        ($request->status) ?  $status = $request->status : $status = 'all';

        $financial_start = $this->getFinancialStart();
        if ($financial_start->month != 1) {
            if (!is_null($request->year)) {
                $financial_start->year = $year;
            }
            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id');

        if ($categories_filter = $request->categories) {
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

        $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $revenues = $revenues->where('account_id', $request->accounts);

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $revenues->where('account_id', $request->accounts);
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'paid_at');

                // Revenues
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setAmount($incomes_graph, $totals, $incomes, $invoices, 'invoice', 'invoiced_at');

                // Revenues
                $this->setAmount($incomes_graph, $totals, $incomes, $revenues, 'revenue', 'paid_at');
                break;
        }

        $statuses = collect(['all' => 'All','paid' => 'Paid']);
        $years = collect(['2020' => '2020','2021' => '2021','2022' => '2022','2023' => '2023','2024' => '2024','2025' => '2025']);
        $thisYear = Carbon::now()->year;
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $customers = Customer::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $myMonth = json_encode(array_values($dates));
        $myIncomesGraph = json_encode(array_values($incomes_graph));


        return view('report.income', compact('years','thisYear','dates', 'categories', 'statuses', 'accounts', 'customers', 'incomes', 'totals', 'company', 'myMonth','myIncomesGraph'));
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

    public function expense(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $expenses = $expenses_graph = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

        $financial_start = $this->getFinancialStart();
        if ($financial_start->month != 1) {
            if (!is_null($request->year)) {
                $financial_start->year = $year;
            }
            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'expense')->orderBy('name')->pluck('name', 'id');


        if ($categories_filter = $request->categories) {
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
            $expenses_graph[Carbon::parse($ym_string)->format('F-Y')] = 0;
            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($cats as $category_id => $category_name) {
                $expenses[$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                );
            }
        }

        $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $payments = $payments->where('account_id', $request->accounts);

        switch ($status) {
            case 'paid':
                // Bills
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $bills = $bills->where('account_id', $request->accounts);
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $bills, 'bill', 'paid_at');

                // Payments
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $payments, 'payment', 'paid_at');
                break;
            default:
                // Bills
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $bills, 'bill', 'billed_at');

                // Payments
                $this->setExpenseAmount($expenses_graph, $totals, $expenses, $payments, 'payment', 'paid_at');
                break;
        }

        $statuses = collect(['all' => 'All','paid' => 'Paid']);
        $years = collect(['2020' => '2020','2021' => '2021','2022' => '2022','2023' => '2023','2024' => '2024','2025' => '2025']);
        $thisYear = Carbon::now()->year;
        $accounts = Account::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $vendors = Vendor::where('company_id', session('company_id'))->where('enabled', 1)->pluck('name', 'id')->toArray();
        $myMonth = json_encode(array_values($dates));
        $myExpensesGraph = json_encode(array_values($expenses_graph));

        return view('report.expense', compact('years','thisYear','dates', 'categories', 'statuses', 'accounts', 'vendors', 'expenses', 'totals', 'company', 'myMonth','myExpensesGraph'));
    }

    private function setExpenseAmount(&$graph, &$totals, &$expenses, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if ($item->getTable() == 'bill_payments') {
                $bill = $item->bill;

                if ($vendors = request('vendors')) {
                    if (!in_array($bill->vendor_id, $vendors)) {
                        continue;
                    }
                }

                $item->category_id = $bill->category_id;
            }

            if ($item->getTable() == 'bills') {
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

            if (!isset($expenses[$item->category_id]) || !isset($expenses[$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if (($type == 'bill') && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $expenses[$item->category_id][$month]['amount'] += $amount;
            $expenses[$item->category_id][$month]['currency_code'] = $item->currency_code;
            $expenses[$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            $graph[$month_year] += $amount;

            $totals[$month]['amount'] += $amount;
        }
    }

    public function incomeVsexpense(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $compares = $profit_graph = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

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

        $categories_filter = $request->categories;

        $income_categories = Category::where('enabled', 1)->where('type', 'income')->when($categories_filter, function ($query) use ($categories_filter) {
            return $query->whereIn('id', $categories_filter);
        })->orderBy('name')->pluck('name', 'id')->toArray();

        $expense_categories = Category::where('enabled', 1)->where('type', 'expense')->when($categories_filter, function ($query) use ($categories_filter) {
            return $query->whereIn('id', $categories_filter);
        })->orderBy('name')->pluck('name', 'id')->toArray();

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->format('F');
            $profit_graph[Carbon::parse($ym_string)->format('F-Y')] = 0;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($income_categories as $category_id => $category_name) {
                $compares['income'][$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                );
            }

            foreach ($expense_categories as $category_id => $category_name) {
                $compares['expense'][$category_id][$dates[$j]] = array(
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                );
            }
        }

        $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $revenues = $revenues->where('account_id', $request->accounts);

        $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
        if ($request->accounts)
            $payments = $payments->where('account_id', $request->accounts);

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $invoices = $invoices->where('account_id', $request->accounts);
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'paid_at');

                // Revenues
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');

                // Bills
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                if ($request->accounts)
                    $bills = $bills->where('account_id', $request->accounts);
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $bills, 'bill', 'paid_at');

                // Payments
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $invoices, 'invoice', 'invoiced_at');

                // Revenues
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $revenues, 'revenue', 'paid_at');

                // Bills
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $bills, 'bill', 'billed_at');

                // Payments
                $this->setIncomeExpenseAmount($profit_graph, $totals, $compares, $payments, 'payment', 'paid_at');
                break;
        }

        $statuses = collect(['all' => 'All','paid' => 'Paid']);
        $years = collect(['2020' => '2020','2021' => '2021','2022' => '2022','2023' => '2023','2024' => '2024','2025' => '2025']);
        $thisYear = Carbon::now()->year;
        $myMonth = json_encode(array_values($dates));
        $myGraph = json_encode(array_values($profit_graph));

        $accounts = Account::where('enabled', 1)->pluck('name', 'id')->toArray();
        $categories = Category::where('enabled', 1)->where('type', ['income', 'expense'])->pluck('name', 'id')->toArray();

        return view('report.income_expense', compact('years','thisYear','company', 'myMonth', 'myGraph', 'dates','income_categories','expense_categories','categories','statuses','accounts','compares','totals'));
    }

    private function setIncomeExpenseAmount(&$graph, &$totals, &$compares, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if (($item->getTable() == 'bill_payments') || ($item->getTable() == 'invoice_payments')) {
                $type_item = $item->$type;
                $item->category_id = $type_item->category_id;
            }

            if ($item->getTable() == 'invoice_payments') {
                $invoice = $item->invoice;

                if ($customers = request('customers')) {
                    if (!in_array($invoice->customer_id, $customers)) {
                        continue;
                    }
                }
                $item->category_id = $invoice->category_id;
            }

            if ($item->getTable() == 'bill_payments') {
                $bill = $item->bill;

                if ($vendors = request('vendors')) {
                    if (!in_array($bill->vendor_id, $vendors)) {
                        continue;
                    }
                }
                $item->category_id = $bill->category_id;
            }

            if (($item->getTable() == 'invoices') || ($item->getTable() == 'bills')) {
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

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            if (!isset($compares[$group][$item->category_id]) || !isset($compares[$group][$item->category_id][$month]) || !isset($graph[$month_year])) {
                continue;
            }

            $amount = $item->getConvertedAmount();

            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $compares[$group][$item->category_id][$month]['amount'] += $amount;
            $compares[$group][$item->category_id][$month]['currency_code'] = $item->currency_code;
            $compares[$group][$item->category_id][$month]['currency_rate'] = $item->currency_rate;

            if ($group == 'income') {
                $graph[$month_year] += $amount;

                $totals[$month]['amount'] += $amount;
            } else {
                $graph[$month_year] -= $amount;

                $totals[$month]['amount'] -= $amount;
            }
        }
    }

    private function setTaxAmount(&$items, &$totals, $rows, $type, $date_field)
    {
        foreach ($rows as $row) {
            if (($row->getTable() == 'bill_payments') || ($row->getTable() == 'invoice_payments')) {
                $type_row = $row->$type;
                $row->category_id = $type_row->category_id;
            }

            $date = Carbon::parse($row->$date_field)->format('M');

            ($date_field == 'paid_at') ? $row_totals = $row->$type->totals : $row_totals = $row->totals;

            foreach ($row_totals as $row_total) {
                if ($row_total->code != 'tax') {
                    continue;
                }

                if (!isset($items[$row_total->name])) {
                    continue;
                }

                if ($date_field == 'paid_at') {
                    $rate = ($row->amount * 100) / $type_row->amount;
                    $row_amount = ($row_total->amount / 100) * $rate;
                } else {
                    $row_amount = $row_total->amount;
                }

                $amount = $row->convert($row_amount, $row->currency_code, $row->currency_rate);

                $items[$row_total->name][$date]['amount'] += $amount;

                if ($type == 'invoice') {
                    $totals[$row_total->name][$date]['amount'] += $amount;
                } else {
                    $totals[$row_total->name][$date]['amount'] -= $amount;
                }
            }
        }


    }

    public function tax(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $incomes = $expenses = $totals = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

        $financial_start = $this->getFinancialStart();
        if ($financial_start->month != 1) {
            if (!is_null($request->year)) {
                $financial_start->year = $year;
            }
            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subMonth();
        }

        $t = Tax::where('enabled', 1)->where('rate', '<>', '0')->pluck('name')->toArray();
        $taxes = array_combine($t, $t);

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addMonth()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->format('M');
            foreach ($taxes as $tax_name) {
                $incomes[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1,
                ];
                $expenses[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1,
                ];
                $totals[$tax_name][$dates[$j]] = [
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1,
                ];
            }
        }

        switch ($status) {
            case 'paid':
                // Invoices
                $invoices = InvoicePayment::with(['invoice', 'invoice.totals'])->monthsOfYear('paid_at')->get();

                $this->setTaxAmount($incomes, $totals, $invoices, 'invoice', 'paid_at');
                // Bills
                $bills = BillPayment::with(['bill', 'bill.totals'])->monthsOfYear('paid_at')->get();
                $this->setTaxAmount($expenses, $totals, $bills, 'bill', 'paid_at');
                break;
            default:
                // Invoices
                $invoices = Invoice::with(['totals'])->accrued()->monthsOfYear('invoiced_at')->get();
                $this->setTaxAmount($incomes, $totals, $invoices, 'invoice', 'invoiced_at');
                // Bills
                $bills = Bill::with(['totals'])->accrued()->monthsOfYear('billed_at')->get();
                $this->setTaxAmount($expenses, $totals, $bills, 'bill', 'billed_at');
                break;
        }

        $statuses = collect(['all' => 'All','paid' => 'Paid']);
        $years = collect(['2020' => '2020','2021' => '2021','2022' => '2022','2023' => '2023','2024' => '2024','2025' => '2025']);
        $thisYear = Carbon::now()->year;

        return view('report.tax', compact('years','thisYear','dates', 'taxes', 'incomes', 'expenses', 'totals', 'statuses','company'));
    }

    private function setProfitLossAmount(&$totals, &$compares, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if (($item->getTable() == 'bill_payments') || ($item->getTable() == 'invoice_payments')) {
                $type_item = $item->$type;
                $item->category_id = $type_item->category_id;
            }

            $date = Carbon::parse($item->$date_field)->quarter;

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            if (!isset($compares[$group][$item->category_id]))
                continue;

            $amount = $item->getConvertedAmount(false, false);

            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            $compares[$group][$item->category_id][$date]['amount'] += $amount;
            $compares[$group][$item->category_id][$date]['currency_code'] = $item->currency_code;
            $compares[$group][$item->category_id][$date]['currency_rate'] = $item->currency_rate;
            $compares[$group][$item->category_id]['total']['amount'] += $amount;

            if ($group == 'income') {
                $totals[$date]['amount'] += $amount;
                $totals['total']['amount'] += $amount;
            } else {
                $totals[$date]['amount'] -= $amount;
                $totals['total']['amount'] -= $amount;
            }
        }
    }

    public function profitAndloss(Request $request)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $dates = $totals = $compares = $categories = [];

        ($request->year) ?  $year = $request->year : $year = Carbon::now()->year;
        ($request->status) ?  $status = $request->status : $status = 'all';

        // check and assign year start
        $financial_start = $this->getFinancialStart();

        if ($financial_start->month != 1) {
            // check if a specific year is requested
            if (!is_null(request('year'))) {
                $financial_start->year = $year;
            }

            $year = [$financial_start->format('Y'), $financial_start->addYear()->format('Y')];
            $financial_start->subYear()->subQuarter();
        }

        $income_categories = Category::where('enabled', 1)->where('type', 'income')->orderBy('name')->pluck('name', 'id')->toArray();
        $expense_categories = Category::where('enabled', 1)->where('type', 'expense')->orderBy('name')->pluck('name', 'id')->toArray();

        // Dates
        for ($j = 1; $j <= 12; $j++) {
            $ym_string = is_array($year) ? $financial_start->addQuarter()->format('Y-m') : $year . '-' . $j;
            $dates[$j] = Carbon::parse($ym_string)->quarter;

            // Totals
            $totals[$dates[$j]] = array(
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            );

            foreach ($income_categories as $category_id => $category_name) {
                $compares['income'][$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                ];
            }

            foreach ($expense_categories as $category_id => $category_name) {
                $compares['expense'][$category_id][$dates[$j]] = [
                    'category_id' => $category_id,
                    'name' => $category_name,
                    'amount' => 0,
                    'currency_code' => $company->default_currency,
                    'currency_rate' => 1
                ];
            }

            $j += 2;
        }

        $totals['total'] = [
            'amount' => 0,
            'currency_code' => $company->default_currency,
            'currency_rate' => 1
        ];

        foreach ($dates as $date) {
            $gross['income'][$date] = 0;
            $gross['expense'][$date] = 0;
        }

        $gross['income']['total'] = 0;
        $gross['expense']['total'] = 0;

        foreach ($income_categories as $category_id => $category_name) {
            $compares['income'][$category_id]['total'] = [
                'category_id' => $category_id,
                'name' => 'Totals',
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            ];
        }

        foreach ($expense_categories as $category_id => $category_name) {
            $compares['expense'][$category_id]['total'] = [
                'category_id' => $category_id,
                'name' => 'Totals',
                'amount' => 0,
                'currency_code' => $company->default_currency,
                'currency_rate' => 1
            ];
        }

        // Invoices
        switch ($status) {
            case 'paid':
                $invoices = InvoicePayment::monthsOfYear('paid_at')->get();
                $this->setProfitLossAmount($totals, $compares, $invoices, 'invoice', 'paid_at');
                break;
            default:
                $invoices = Invoice::accrued()->monthsOfYear('invoiced_at')->get();
                $this->setProfitLossAmount($totals, $compares, $invoices, 'invoice', 'invoiced_at');
                break;
        }

        // Revenues
        $revenues = Revenue::monthsOfYear('paid_at')->isNotTransfer()->get();
        $this->setProfitLossAmount($totals, $compares, $revenues, 'revenue', 'paid_at');

        // Bills
        switch ($status) {
            case 'paid':
                $bills = BillPayment::monthsOfYear('paid_at')->get();
                $this->setProfitLossAmount($totals, $compares, $bills, 'bill', 'paid_at');
                break;
            default:
                $bills = Bill::accrued()->monthsOfYear('billed_at')->get();
                $this->setProfitLossAmount($totals, $compares, $bills, 'bill', 'billed_at');
                break;
        }

        // Payments
        $payments = Payment::monthsOfYear('paid_at')->isNotTransfer()->get();
        $this->setProfitLossAmount($totals, $compares, $payments, 'payment', 'paid_at');

        $statuses = collect(['all' => 'All','paid' => 'Paid']);
        $years = collect(['2020' => '2020','2021' => '2021','2022' => '2022','2023' => '2023','2024' => '2024','2025' => '2025']);
        $thisYear = Carbon::now()->year;

        return view('report.profit_loss', compact('years','thisYear','dates', 'income_categories', 'expense_categories', 'compares', 'totals', 'gross', 'statuses','company'));
    }
}
