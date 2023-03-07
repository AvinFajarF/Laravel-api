<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
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

            'id' => $this->id,
            'image' => $this->image,
            'title' => $this->title,
            'content' => $this->content,
            'views' => $this->views,
            'created_at' => date_format($this->created_at, "Y/m/d H:i:s"),
            'is_pinned' => $this->is_pinned,
            'created_by' => $this->whenLoaded('created_by'),
            'comments' => $this->whenLoaded('comments', function(){
                return collect($this->comments)->each(function ($comment) {
                    $comment->comments;
                    return $comment;
                });
            }),

        ];
    }
}
