<?php

namespace App\Http\Controllers\Expense;

use App\Http\Resources\ExpenseCategoryCollection;
use App\Http\Resources\ExpenseCategoryResource;
use App\Repositories\ExpenseRepository;
use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Log;
class ExpenseCategoryController extends Controller
{
    private ExpenseCategory $expenseCategory;
    private ExpenseRepository $expenseRepository;
    
    public function __construct(ExpenseCategory $expenseCategory, ExpenseRepository $expenseRepository)
    {
        $this->expCat  = $expenseCategory;
        $this->expRepo = $expenseRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->expRepo->getAllExpenseModels($this->expCat, 100);

        return new ExpenseCategoryCollection($data);
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
            'name'      => 'required',
            'image_url' => 'required',
        ]);

        $req = $request->only(['name', 'image_url']);

        try {

            $data = $this->expRepo->createExpenseModels($this->expCat, $req);
            return new ExpenseCategoryResource($data);
            
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
        $data = $this->expRepo->getSingleExpenseModels($id, $this->expCat);
        return new ExpenseCategoryResource($data);
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
            'name'      => 'required',
            'image_url' => 'required',
        ]);

        $req = $request->only(['name', 'image_url']);

        try {

            $update = $this->expRepo->updateExpenseModels($this->expCat, $id, $req);
            if ($update) {
                $data = $this->expRepo->getSingleExpenseModels($id, $this->expCat);
                return new ExpenseCategoryResource($data);
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
            $delete = $this->expRepo->deleteExpenseModels($this->expCat, $id);
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
