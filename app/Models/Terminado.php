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
use App\Models\Producto;

class Terminado extends Model
{
    use HasFactory;

    //Indicamos la tabla por si hay algún problema
    protected $table = 'terminados';

    protected $primaryKey = 'producto_id';

    // Pertenece a Producto 1:1
    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}
