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
                'tipo' => $item->tipo,
                'nome_tipo' => $item->nome_tipo,
                'natureza' => $item->natureza,
                'valor_parcela' => $item->valor_parcela,
                'valor' => dec2str($item->valor),
                'grupo' => [
                    'id' => $item->grupo->id,
                    'nome' => $item->grupo->nome,
                ],
                'periodo' => $item->periodo
            ];
        });

        return [
            'data' => $collection, 
            'query' => $request->query()
        ];
    }
}
