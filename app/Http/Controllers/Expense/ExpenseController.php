<?php

namespace App\Http\Controllers\Expense;

use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\ExpenseResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class ExpenseController extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->with    = ['expense_category'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = new ExpenseCollection($this->expRepo->getAllExpenseModels($this->exp, 100, $this->with));
            
            if ($data) {
                return $this->response($data, __('common.expenseList'), 200);
            }

        } catch (\Exception $e) {
            Log::emergency($e);
            return $this->response(NULL, __('common.error'), 500);
        }
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
            'amount'              => 'required',
            'payment_method'      => 'required'
        ]);

        $req = $request->only(['expense_category_id', 'amount', 'note', 'payment_method']);

        $expCatSingle = $this->expRepo->getSingleExpenseModels($request->expense_category_id, $this->expCat);

        if (!$expCatSingle) {

            return $this->response(NULL, __('common.expCatNotFound'), 200);
        }

        try {

            $data = $this->expRepo->createExpenseModels($this->exp, $req);
            return $this->response(new ExpenseResource($data), __('common.expenseCreate'), 200);
            
        } catch (\Exception $e) {
            
            Log::emergency($e);
            return $this->response(NULL, __('common.error'), 500);
            
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
        try {
            $data = $this->expRepo->getSingleExpenseModels($id, $this->exp);
            
            if ($data) {
                return $this->response( new ExpenseResource($data), __('common.expenseSingle'), 200);
            } else {
                return $this->response(NULL, __('common.notFound'), 200);
            }

        } catch (\Exception $e) {
            Log::emergency($e);
            return $this->response(NULL, __('common.error'), 500);
        }
        
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
            'amount'              => 'required',
            'payment_method'      => 'required'
        ]);

        $req = $request->only(['expense_category_id', 'amount', 'note', 'payment_method']);

        try {

            $update = $this->expRepo->updateExpenseModels($this->exp, $id, $req);
            if ($update) {
                $data = $this->expRepo->getSingleExpenseModels($id, $this->exp);
                return $this->response(new ExpenseResource($data), __('common.expenseUpdate'), 200);
            }
            
        } catch (\Exception $e) {
            
            Log::emergency($e);
            return $this->response(NULL, __('common.error'), 500);
            
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
                return $this->response(NULL, __('common.delete'), 200);
            } else {
                return $this->response(NULL, __('common.notFound'), 200);
            }
            
        } catch (\Exception $e) {
            Log::emergency($e);
            return $this->response(NULL, __('common.error'), 500);
        }

    }
}
