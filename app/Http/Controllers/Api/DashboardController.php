<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GroupUser;
use App\Models\Record;
use App\Models\RecordShare;
use Carbon\Carbon;
use stdClass;

class DashboardController extends Controller
{
    public function dailyIncomeExpense(Request $request)
    {
        $user = $request->user();
        $dailyIncomeExpenseChartData = null;
        $incomes = [];
        $expenses = [];
        $dates = [];
        for($i = 0; $i < 30; $i++)
        {
            $date = date("Y-m-d", strtotime('-'. $i .' days'));
            $params = [];
            $params[] = ['date', $date];
            $params[] = ['user_id', $user->id];

            $incomes[] = Record::where('type', "Income")->where($params)->sum('amount');
            $expenses[] = Record::where('type', "Expense")->where($params)->sum('amount');
            $dates[] = date("M-d", strtotime('-'. $i .' days'));
        }

        $dailyIncomeExpenseChartData = [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => "Income",
                    'backgroundColor' => getChartColor(),
                    'data' => $incomes
                ],
                [
                    'label' => "Expense",
                    'backgroundColor' => getChartColor(),
                    'data' => $expenses
                ]
            ]
        ];

        return successResponse("Daily income expense chart data", $dailyIncomeExpenseChartData);
    }

    public function largestExpense(Request $request)
    {
        $user = $request->user();
        $largestExpenseChartData = null;
        $largest_expenses = Record::where('type', "Expense")->where('user_id', $user->id)->orderBy('amount', "DESC")->take(5)->get();
        if(count($largest_expenses) > 0){
            foreach ($largest_expenses as $largest_expense) {
                $largestExpensesTitle[] = $largest_expense->title . " (" . date("d-m-Y", strtotime($largest_expense->date)) . ")";
                $largestExpensesColors[] = getChartColor();
                $largestExpensesAmount[] = $largest_expense->amount;
            }
            $largestExpenseChartData = [
                'labels' => $largestExpensesTitle,
                'datasets' => [
                    [
                        "label" => 'Amount',
                        "backgroundColor" => $largestExpensesColors,
                        "data" => $largestExpensesAmount
                    ]
                ]
            ];
        }

        return successResponse("Largest expenses chart data", $largestExpenseChartData);
    }

    public function groupExpense(Request $request)
    {
        $user = $request->user();
        $myGroupExpenseChartData = null;
        $myGroups = GroupUser::where('user_id', $user->id)->with('group')->get();
        if(count($myGroups) > 0){
            foreach ($myGroups as $myGroup) {
                $groupExpensesGroups[] = $myGroup->group->name;
                $groupExpensesColors[] = getChartColor();
                $myContributionIds = Record::where('type', "Contribution")->where('group_id', $myGroup->group_id)->get()->pluck('id')->toArray();
                $groupExpensesAmount[] = RecordShare::whereIn('record_id', $myContributionIds)->where('user_id', $user->id)->sum('share');
            }

            $myGroupExpenseChartData = [
                'labels' => $groupExpensesGroups,
                'datasets' => [
                    [
                        "backgroundColor" => $groupExpensesColors,
                        "data" => $groupExpensesAmount
                    ]
                ]
            ];
        }

        return successResponse("Group expense chart data", $myGroupExpenseChartData);
    }

    public function monthlyIncomeExpense(Request $request)
    {
        $user = $request->user();
        $monthlyIncomeExpenseChartData = null;
        $months = [];
        $monthly_income_sum = [];
        $monthly_expense_sum = [];
        for($i = 0; $i < 12; $i++){
            $months[] = Carbon::now()->subMonth($i)->format("M Y");
            $income_sum = Record::where('type', "Income")->where('user_id', $user->id)
                    ->whereMonth('date', '=', Carbon::now()->subMonth($i)->month)
                    ->sum('amount');
            $monthly_income_sum[] = round($income_sum, 2);
            $expense_sum = Record::where('type', "Expense")->where('user_id', $user->id)
                    ->whereMonth('date', '=', Carbon::now()->subMonth($i)->month)
                    ->sum('amount');
            $monthly_expense_sum[] = round($expense_sum, 2);
        }

        $monthly_incomes = new stdClass();
        $monthly_incomes->label = "Income";
        $monthly_incomes->backgroundColor = getChartColor();
        $monthly_incomes->data = $monthly_income_sum;

        $monthly_expenses = new stdClass();
        $monthly_expenses->label = "Expense";
        $monthly_expenses->backgroundColor = getChartColor();
        $monthly_expenses->data = $monthly_expense_sum;

        $monthlyIncomeExpenseChartData = [
            "labels" => $months,
            "datasets" => [$monthly_incomes, $monthly_expenses]
        ];

        return successResponse("Monthly income expense chart data", $monthlyIncomeExpenseChartData);
    }
}
