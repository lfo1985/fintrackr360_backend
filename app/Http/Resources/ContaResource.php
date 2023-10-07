<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContaResource extends JsonResource
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
            'id_grupo' => $this->id_grupo,
            'titulo' => $this->titulo,
            'natureza' => $this->natureza,
            'descricao' => $this->descricao,
            'valor' => str2dec($this->valor),
            'grupo' => [
                'id' => $this->grupo->id,
                'nome' => $this->grupo->nome
            ],
            'periodo' => [
                'id' => $this->periodo->id,
                'valor' => $this->periodo->valor,
            ]
        ];
    }
}
