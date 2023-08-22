<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GrupoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $collection = $this->collection->map(function($item){
            return [
                'id' => $item->id,
                'nome' => $item->nome
            ];
        });

        return [
            'data' => $collection, 
            'query' => $request->query()
        ];
    }
}
