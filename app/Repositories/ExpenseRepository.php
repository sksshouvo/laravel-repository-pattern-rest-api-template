<?php

namespace App\Repositories;

class ExpenseRepository {

    public function getAllExpenseModels($model, $paginationLimit, $with = []) {

        $query = $model::groupBy('id');
        
        if (count($with)) {
            $query->with($with);
        }
        return $query->paginate($paginationLimit);
    }

    public function getSingleExpenseModels($id, $model) {
        $data = $model::find($id);
        if ($data) {
            return $data;
        }

        return false;
    }

    public function createExpenseModels($model, array $request) {
        return $model::create($request);
    }

    public function updateExpenseModels($model, $id, array $request) {

        return $model::whereId($id)->update($request);
    }

    public function deleteExpenseModels($model, $id) {
        return $model::destroy($id);
    }
}