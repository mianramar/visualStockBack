<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Perfil extends Model
{
    use HasFactory;

    //Indicamos la tabla por si hay algún problema
    protected $table = 'perfils';

    // Pertenece a un usuario
    public function users() {
        return $this->belongsTo(User::class);
    }
}
