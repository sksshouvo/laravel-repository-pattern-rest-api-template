<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Repositories\ExpenseRepository;
use App\Models\ExpenseCategory;
use App\Models\Expense;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private ExpenseCategory $expenseCategory;
    private ExpenseRepository $expenseRepository;
    private Expense $expense;
        

    public function __construct()
    {
        $this->exp     = new Expense();
        $this->expCat  = new ExpenseCategory();
        $this->expRepo = new ExpenseRepository();

    }
}
