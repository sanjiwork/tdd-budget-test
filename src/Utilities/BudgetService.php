<?php


namespace App\Utilities;

use DateTime;

class BudgetService
{

    public $amount = 0;
    public $repo;

    public function __construct()
    {
        $this->repo = new BudgetRepo();
    }

    public function query(DateTime $start, DateTime $end)
    {
        $end_month = $end->format('Ym');
        if ($end < $start) {
            return $this->amount;
        }
        $current_start = $start;
        do {
            $current_month_days = $this->calMonthDays($current_start);
            $current_month_end = $this->getMonthEndDate($current_start);
            $current_end = $end->format('Ymd') > $current_month_end->format('Ymd')
                ? $current_month_end
                : $end;
            $current_month = $current_start->format('Ym');
            $current_days = $this->diffDays($current_start, $current_end);
            $current_month_amount = $this->getMonthAmount($current_month);
            if ($current_days == $current_month_days) {
                $this->calAmount($current_month_amount);
            } else {
                $this->calAmount(round($current_month_amount / $current_month_days * $current_days, 2));
            }

            $current_start = date_create(date('Ymd',
                strtotime($current_end->format('Ym01') . ' +1 month')));

        } while ($current_start->format('Ym') <= $end_month);

        return $this->amount;
    }

    private function diffDays(DateTime $start, DateTime $end){
        return $start->diff($end)->days + 1;
    }

    private function getMonthEndDate(DateTime $date)
    {
        return date_create(date('Ymd',
            strtotime($date->format('Ym01') . ' +1 month -1 day')));
    }

    private function calMonthDays(DateTime $date): int
    {
        return cal_days_in_month(CAL_GREGORIAN, $date->format('m'),
            $date->format('Y'));
    }

    private function calAmount($amount)
    {
        $this->amount += $amount;
    }

    private function getMonthAmount($current_month): int
    {
        $budget_data = $this->repo->getAll()->where('month', $current_month)->first();

        return isset($budget_data['amount']) ? (int)$budget_data['amount'] : 0;
    }

}