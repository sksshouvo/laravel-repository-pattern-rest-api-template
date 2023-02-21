<?php

namespace App\Http\Controllers\Expense;

use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\ExpenseResource;
use App\Repositories\ExpenseRepository;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Log;

class ExpenseController extends Controller
{
    private expense $expense;
    private ExpenseRepository $expenseRepository;
        
    public function __construct(expense $expense, ExpenseRepository $expenseRepository)
    {
        $this->exp     = $expense;
        $this->expRepo = $expenseRepository;
        $this->with    = ['expense_category'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->expRepo->getAllExpenseModels($this->exp, 100, $this->with);

        return new ExpenseCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_category_id' => 'required',
            'amount'              => 'required'
        ]);

        $req = $request->only(['expense_category_id', 'amount', 'note']);

        try {

            $data = $this->expRepo->createExpenseModels($this->exp, $req);
            return new ExpenseResource($data);
            
        } catch (\Exception $e) {
            
            Log::emergency($e);
            return response()->json(["msg" => __('common.error')], 500);
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->expRepo->getSingleExpenseModels($id, $this->exp);
        return new ExpenseResource($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'expense_category_id' => 'required',
            'amount'              => 'required'
        ]);

        $req = $request->only(['expense_category_id', 'amount', 'note']);

        try {

            $update = $this->expRepo->updateExpenseModels($this->exp, $id, $req);
            if ($update) {
                $data = $this->expRepo->getSingleExpenseModels($id, $this->exp);
                return new ExpenseResource($data);
            }
            
        } catch (\Exception $e) {
            
            Log::emergency($e);
            return response()->json(["msg" => __('common.error')], 500);
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = $this->expRepo->deleteExpenseModels($this->exp, $id);
            if ($delete) {
                return response()->json(['msg' => __('common.delete')], 200);
            } else {
                return response()->json(['msg' => __('common.notFound')], 200);
            }
            
        } catch (\Exception $e) {
            Log::emergency($e);
            return response()->json(["msg" => __('common.error')], 500);
        }

    }
}
