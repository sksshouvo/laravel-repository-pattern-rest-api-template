<?php

namespace App\Http\Controllers\Expense;

use App\Http\Resources\ExpenseCategoryCollection;
use App\Http\Resources\ExpenseCategoryResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
class ExpenseCategoryController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->with = ['expenses'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->expRepo->getAllExpenseModels($this->expCat, 100, $this->with);

        if ($data->count()) {
            return $this->response(new ExpenseCategoryCollection($data), __('common.expenseCatList'), 200);
        } else {
            return $this->response(NULL, __('common.notFound'), 200);
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
            'name'      => 'required',
            'image_url' => 'required',
        ]);

        $req = $request->only(['name', 'image_url']);

        try {

            $data = $this->expRepo->createExpenseModels($this->expCat, $req);

            if ($data) {
                return $this->response(new ExpenseCategoryResource($data), __('common.expenseCatCreate'), 200);
            }
            
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
        $data = $this->expRepo->getSingleExpenseModels($id, $this->expCat);

        if ($data) {
            return $this->response(new ExpenseCategoryResource($data), __('common.expenseCatSingle'), 200);
        } else {
            return $this->response(NULL, __('common.notFound'), 200);
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
            'name'      => 'required',
            'image_url' => 'required',
        ]);

        $req = $request->only(['name', 'image_url']);

        try {

            $update = $this->expRepo->updateExpenseModels($this->expCat, $id, $req);
            if ($update) {
                $data = $this->expRepo->getSingleExpenseModels($id, $this->expCat);
                return $this->response(new ExpenseCategoryResource($data), __('common.expenseCatUpdate'), 200);
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
            $delete = $this->expRepo->deleteExpenseModels($this->expCat, $id);
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
