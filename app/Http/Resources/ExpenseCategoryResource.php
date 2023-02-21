<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $totalExpAmt = 0;

        foreach ($this->expenses as $key => $expense) {
                $totalExpAmt += $expense->amount;
        }

        return [
            "id"                    => $this->id,
            "name"                  => $this->name,
            "image_url"             => $this->image_url,
            "total_expenses_amount" => $totalExpAmt,
            "created_at"            => date('Y-m-d H:i:s', strtotime($this->created_at)),
            "updated_at"            => date('Y-m-d H:i:s', strtotime($this->updated_at))
        ];
    }
}
