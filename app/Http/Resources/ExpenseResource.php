<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "category_name" => $this->expense_category->name,
            "amount"        => $this->amount,
            "note"          => $this->note,
            "created_at"    => date('Y-m-d H:i:s', strtotime($this->created_at)),
            "updated_at"    => date('Y-m-d H:i:s', strtotime($this->updated_at))
        ];
    }
}
