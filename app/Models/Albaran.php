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
use App\Models\Empresa;
use App\Models\Material;
use App\Models\Producto;

class Albaran extends Model
{
    use HasFactory;

    //Indicamos la tabla por si hay algún problema
    protected $table = 'albarans';

    // Pertenece a una empresa 1:N
    public function empresa() {
        return $this->belongsTo(Empresa::class);
    }

    // Puede contener varios materiales
    public function materials() {
        return $this->belongsToMany(Material::class)->withPivot('cantidad', 'precio');
    }

    // Puede contener varios productos
    public function productos() {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad', 'precio');
    }
}
