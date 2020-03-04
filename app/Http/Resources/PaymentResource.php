<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return [
            'uuid'         => $this->uuid,
            'payment_date' => !$this->payment_date ? null : Carbon::parse($this->payment_date)->format('Y-m-d'),
            'expires_at'   => Carbon::parse($this->expires_at)->format('Y-m-d'),
            'status'       => $this->status,
            'user_id'      => $this->user_id,
            'clp_usd'      => $this->clp_usd,
        ];
    }
}
