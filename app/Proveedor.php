<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table='proveedors';
    protected $fillable=['nombre_proveedor','ruc','direccion','telefono','telefono2','email','tipo_proveedor','estado','id_user'];
}
