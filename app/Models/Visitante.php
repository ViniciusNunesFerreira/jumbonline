<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nome', 'logradouro', 'numero', 'bairro', 'cidade', 'uf', 'cep', 'cod_consultor', 'doc'
    ];
}
