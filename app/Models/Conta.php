<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method porTitulo()
 */

class Conta extends Model
{
    use HasFactory;

    protected $table = 'contas';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'titulo', 
        'natureza', 
        'descricao', 
        'valor',
        'created_by',
        'id_grupo'
    ];

    /**
     * ESCOPOS
     */

    public function scopePorTitulo($query, $value){
        return $query->where('titulo', 'LIKE', '%'.$value.'%');
    }

    /**
     * RELACIONAMENTOS
     */
    
    public function grupo(){
        return $this->hasOne(Grupo::class, 'id', 'id_grupo');
    }
}