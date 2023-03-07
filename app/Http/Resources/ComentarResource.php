<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComentarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
        [

            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'comentar' => $this->content,
            'created_at' => date_format($this->created_at, "Y/m/d H:i:s"),
            'comentars' => $this->whenLoaded(['user']),
        ];
    }
}
