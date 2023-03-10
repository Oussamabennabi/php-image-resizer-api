<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this.id,
            'name' => $this.name,
            'created_at' => $this.created_at,
            'updated_at' => $this.updated_at,
        ];
    }
}

// TODO:  you can think of a resource like graphQL
 //for exmp lets say you want to return all AlbumData
 // but not the user_id well!! you can do that with
 //  resources. 
