<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'user' => [
                'id' => \Auth::user()->id,
                'name' => \Auth::user()->name
            ],
            'summaries' => SummaryResource::collection($this->summaries)
        ];
    }
}
