<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = ['nome', 'email'];

    // Escopo para filtragem de dados
    public function scopeNome($query, $value){
        $query->where('nome', $value);
    }

    // Getter do nome para personalização dos dados
    public function getNomeAttribute($value){
        return strtoupper($value);
    }

    // Setter do nome para personalização dos dados
    public function setNomeAttribute($value){
        $this->attributes['nome'] = 'xx-xx-xx-'.$value;
    }

}