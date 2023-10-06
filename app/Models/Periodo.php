<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method porTitulo()
 */

class Periodo extends Model
{
    use HasFactory;

    protected $table = 'periodos';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'id_conta', 
        'numero', 
        'total', 
        'valor', 
        'data_vencimento',
        'status',
        'created_by'
    ];

    /**
     * RELACIONAMENTOS
     */
    
    public function conta(){
        return $this->hasOne(Conta::class, 'id', 'id_conta');
    }
}