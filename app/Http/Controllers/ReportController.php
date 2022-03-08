<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Date;
use Session;

class ReportController extends Controller
{
    public function getFinancialStart()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $now = Carbon::now()->startOfYear();
        $setting = explode('-', $company->financial_start);
        $day = !empty($setting[0]) ? $setting[0] : $now->day;
        $month = !empty($setting[1]) ? $setting[1] : $now->month;
        return Carbon::create(null, $month, $day);
    }

    public function income(Request $request)
    {
        $dates = $totals = $incomes = $incomes_graph = $categories = [];
        $status = request('status');
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

    }

    private function filterIncome(Request $request)
    {

    }
}
