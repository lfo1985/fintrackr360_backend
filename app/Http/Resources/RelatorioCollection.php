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
                        'periodo' => $conta->periodo->map(function($periodo) use ($conta){
                            return [
                                'numero' => $periodo->numero,
                                'total' => $periodo->total,
                                'valor' => $periodo->valor,
                                'valor_formatado' => dec2str($conta->natureza == 'D' ? 0-$periodo->valor : $periodo->valor),
                                'data_vencimento' => date2DataBR($periodo->data_vencimento),
                            ];
                        })
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
