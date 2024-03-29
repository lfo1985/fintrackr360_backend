<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = ['nome', 'created_by'];

    /**
     * ESCOPOS
     */

    public function scopeNome($query, $value){
        $query->where('nome', 'LIKE', '%'.$value.'%');
    }

    /**
     * RELACIONAMENTOS
     */
    
     public function contaPeriodo(){
        return $this->hasManyThrough(Periodo::class, Conta::class, 'id_grupo', 'id_conta', 'id', 'id');
    }

}