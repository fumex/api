<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
   protected $table='sucursals';
   protected $fillable=['nombre','direccion','telefono','id_almacen','descripcion','estado','id_user'];
}
