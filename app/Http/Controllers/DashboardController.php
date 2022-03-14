<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Models\ApplicationSetting;
use App\Models\Category;
use Mpdf\Tag\Em;
use Spatie\Permission\Models\Role;
use DB;

use App\Traits\DateTime;
use Carbon\Carbon;
use Session;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 * @category Controller
 */
class DashboardController extends Controller
{
    use DateTime;

    public $today;

    public $company;

    public $financial_start;

    public $income_donut = ['colors' => [], 'labels' => [], 'values' => []];

    public $expense_donut = ['colors' => [], 'labels' => [], 'values' => []];

    public function index()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $this->company = $company;
        $this->today = Carbon::today();

        $this->financial_start = $financial_start = $this->getFinancialStart()->format('Y-m-d');

        list($total_incomes, $total_expenses, $total_profit) = $this->getTotals();

        // dd($total_incomes);

        // list($donut_incomes, $donut_expenses) = $this->getDonuts();

        return view('dashboard', compact(
            'company',
            'total_incomes',
            'total_expenses',
            'total_profit',
            'financial_start'
        ));
    }

    private function getTotals()
    {
        list($incomes_amount, $open_invoice, $overdue_invoice, $expenses_amount, $open_bill, $overdue_bill) = $this->calculateAmounts();

        $incomes_progress = 100;
        if (!empty($open_invoice) && !empty($overdue_invoice)) {
            $incomes_progress = (int) ($open_invoice * 100) / ($open_invoice + $overdue_invoice);
        }
        // Totals
        $total_incomes = array(
            'total'             => $incomes_amount,
            'open_invoice'      => money($open_invoice, $this->company->default_currency, true),
            'overdue_invoice'   => money($overdue_invoice, $this->company->default_currency, true),
            'progress'          => $incomes_progress
        );

        $expenses_progress = 100;
        if (!empty($open_bill) && !empty($overdue_bill)) {
            $expenses_progress = (int) ($open_bill * 100) / ($open_bill + $overdue_bill);
        }
        $total_expenses = array(
            'total'         => $expenses_amount,
            'open_bill'     => money($open_bill, $this->company->default_currency, true),
            'overdue_bill'  => money($overdue_bill, $this->company->default_currency, true),
            'progress'      => $expenses_progress
        );

        $amount_profit = $incomes_amount - $expenses_amount;
        $open_profit = $open_invoice - $open_bill;
        $overdue_profit = $overdue_invoice - $overdue_bill;

        $total_progress = 100;

        if (!empty($open_profit) && !empty($overdue_profit)) {
            $total_progress = (int) ($open_profit * 100) / ($open_profit + $overdue_profit);
        }

        $total_profit = array(
            'total'         => $amount_profit,
            'open'          => money($open_profit, $this->company->default_currency, true),
            'overdue'       => money($overdue_profit, $this->company->default_currency, true),
            'progress'      => $total_progress
        );

        return array($total_incomes, $total_expenses, $total_profit);
    }

    private function calculateAmounts()
    {
        $incomes_amount = $open_invoice = $overdue_invoice = 0;
        $expenses_amount = $open_bill = $overdue_bill = 0;

        $categories = Category::with(['bills', 'invoices', 'payments', 'revenues'])->orWhere('type', 'income')->orWhere('type', 'expense')->where('enabled', 1)->get();

        foreach ($categories as $category) {
            switch ($category->type) {
                case 'income':
                    $amount = 0;
                    // Revenues
                    foreach ($category->revenues as $revenue) {
                        $amount += $revenue->getConvertedAmount();
                    }
                    $incomes_amount += $amount;

                    // Invoices
                    $invoices = $category->invoices()->accrued()->get();
                    foreach ($invoices as $invoice) {
                        list($paid, $open, $overdue) = $this->calculateInvoiceBillTotals($invoice, 'invoice');

                        $incomes_amount += $paid;
                        $open_invoice += $open;
                        $overdue_invoice += $overdue;

                        $amount += $paid;
                    }
                    break;

                case 'expense':
                    $amount = 0;
                    // Payments
                    foreach ($category->payments as $payment) {
                        $amount += $payment->getConvertedAmount();
                    }

                    $expenses_amount += $amount;

                    // Bills
                    $bills = $category->bills()->accrued()->get();
                    foreach ($bills as $bill) {
                        list($paid, $open, $overdue) = $this->calculateInvoiceBillTotals($bill, 'bill');

                        $expenses_amount += $paid;
                        $open_bill += $open;
                        $overdue_bill += $overdue;

                        $amount += $paid;
                    }
                    break;
            }
        }
        return array($incomes_amount, $open_invoice, $overdue_invoice, $expenses_amount, $open_bill, $overdue_bill);
    }

    private function calculateInvoiceBillTotals($item, $type)
    {
        $paid = $open = $overdue = 0;

        $today = $this->today->toDateString();

        $paid += $item->getConvertedAmount();

        $code_field = $type . '_status_code';

        if ($item->$code_field != 'paid') {
            $payments = 0;
            if ($item->$code_field == 'partial') {
                foreach ($item->payments as $payment) {
                    $payments += $payment->getConvertedAmount();
                }
            }

            if ($item->due_at > $today) {
                $open += $item->getConvertedAmount() - $payments;
            } else {
                $overdue += $item->getConvertedAmount() - $payments;
            }
        }

        return array($paid, $open, $overdue);
    }
}
