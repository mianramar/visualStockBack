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
use App\Models\Albaran;
use App\Models\Producto;

class Material extends Model
{
    use HasFactory;

    //Indicamos la tabla por si hay algún problema
    protected $table = 'materials';

    protected $fillable = [
        'cantidad_disponible',
    ];

    // Pertenece a Usuario 1:N
    public function users() {
        return $this->belongsTo(User::class);
    }

    // Puede aparecer en varios albaranes N:M
    public function albaran() {
        return $this->belongsToMany(Albaran::class)->withPivot('cantidad', 'precio');
    }

    // Puede transformarse en muchos Productos, relacion N:M
    public function productos() {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad_consumida', 'cantidad_producida');
    }

    // Al estar protegido el campo cantidad disponible, accedemos a él mediante get
    public function getCantidadDisponible()
    {
        return $this->attributes['cantidad_disponible'];
    }

}
