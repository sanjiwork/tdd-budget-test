<?php


namespace App\Utilities;

use Illuminate\Support\Collection;

class BudgetRepo implements IBudgetRepo
{
    public function getAll()
    {
        $data = [];
        $data[] = ['month' => '202201', 'amount' => '1000'];
        $data[] = ['month' => '202202', 'amount' => '1500'];
        $data[] = ['month' => '202203', 'amount' => '3100'];
        $data[] = ['month' => '202204', 'amount' => '6000'];
        $data[] = ['month' => '202205', 'amount' => '2000'];
        $data[] = ['month' => '202206', 'amount' => '3000'];
        return collect($data);
    }
}