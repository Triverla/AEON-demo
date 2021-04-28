<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AeonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'sessionId' => $this['SessionId'],
            'eventType' => $this['EventType'],
            'phoneNumber' => $this['data']['PhoneNumber'],
            'amount' => $this['data']['Amount'],
            'reference' => $this['data']['Ref'],
            'transactionReference' => $this['data']['Reference'],
            'date' => $this['data']['Date']
        ];
    }
}
