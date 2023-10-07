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
        'id_grupo',
        'tipo'
    ];

    private static $tipos = [
        'PARCELADO' => 'Parcelado',
        'A_VISTA'=> 'À vista',
        'RECORRENTE' => 'Recorrente'
    ];

    private static $naturezas = [
        'C' => 'Crédito',
        'D'=> 'Débito'
    ];

    /**
     * ESCOPOS
     */

    public function scopePorTitulo($query, $value){
        return $query->where('titulo', 'LIKE', '%'.$value.'%');
    }

    /**
     * ACESSORES
     */

    public function getValorParcelaAttribute(){
        $totalParcelas = $this->periodo->count();
        return dec2str($this->valor / $totalParcelas);
    }

    public function getNomeTipoAttribute(){
        return self::$tipos[$this->tipo];
    }

    public function getNomeNaturezaAttribute(){
        return self::$naturezas[$this->natureza];
    }

    /**
     * MÉTODOS
     */

    public static function getTipos(){
        return self::$tipos;
    }

    public static function getNaturezas(){
        return self::$naturezas;
    }

    /**
     * RELACIONAMENTOS
     */
    
    public function grupo(){
        return $this->hasOne(Grupo::class, 'id', 'id_grupo');
    }
    
    public function periodo(){
        return $this->hasMany(Periodo::class, 'id_conta', 'id');
    }
}