<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Media extends JsonResource
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
            'aws_bucket' => 'https://thedge.s3.eu-west-2.amazonaws.com/',
            'file' => $this->file,
            'mimeType' => $this->mimeType,
            'size' => $this->size,
        ];
    }
}
