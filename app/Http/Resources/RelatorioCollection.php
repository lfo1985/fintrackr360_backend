<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RelatorioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $collection = $this->collection->map(function($grupo){
            return [
                'id' => $grupo->id,
                'nome' => $grupo->nome,
                'conta' => $grupo->conta->map(function($conta){
                    return [
                        'id' => $conta->id,
                        'titulo' => $conta->titulo,
                        'natureza' => $conta->natureza,
                        'descricao' => $conta->descricao,
                        'tipo' => $conta->tipo,
                        'periodo' => [
                            'numero' => $conta->periodo->numero,
                            'total' => $conta->periodo->total,
                            'valor' => $conta->periodo->valor,
                            'valor_formatado' => dec2str($conta->natureza == 'D' ? 0-$conta->periodo->valor : $conta->periodo->valor),
                            'data_vencimento' => date2DataBR($conta->periodo->data_vencimento),
                            'status' => $conta->periodo->status,
                        ]
                    ];
                }),
            ];
        });

        return [
            'data' => $collection, 
            'query' => $request->query()
        ];
    }
}
