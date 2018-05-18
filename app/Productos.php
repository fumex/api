<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table='productos';
    protected $primary='id';
    protected $fillable=['id_categoria','nombre_producto','descripcion','unidad_de_medida','precio'];
}
