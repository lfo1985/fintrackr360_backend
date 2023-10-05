<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContaCollection extends ResourceCollection
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
                'id_grupo' => $item->id_grupo,
                'titulo' => $item->titulo,
                'natureza' => $item->natureza,
                'valor' => $item->valor,
            ];
        });

        return [
            'data' => $collection, 
            'query' => $request->query()
        ];
    }
}
