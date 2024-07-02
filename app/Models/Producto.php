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
use App\Models\Intermedio;
use App\Models\Terminado;
use App\Models\Material;
use App\Models\Albaran;

class Producto extends Model
{
    use HasFactory;

    //Indicamos la tabla por si hay algún problema
    protected $table = 'productos';

    protected $fillable = [
        'cantidad_disponible',
    ];

    // Tiene realcion 1:1 con Intermedio y Terminado
    public function intermedio() {
        return $this->hasOne(Intermedio::class);
    }

    public function terminado() {
        return $this->hasOne(Terminado::class);
    }

    // Puede utilizar muchos Materiales, relacion N:M
    public function materiales() {
        return $this->belongsToMany(Material::class)->withPivot('cantidad_consumida', 'cantidad_producida');
    }

    // Puede aparecer en varios albaranes N:M
    public function albaran() {
        return $this->belongsToMany(Albaran::class)->withPivot('cantidad', 'precio');
    }

    // Al estar protegido el campo cantidad disponible, accedemos a él mediante get
    public function getCantidadDisponible()
    {
        return $this->attributes['cantidad_disponible'];
    }
}
