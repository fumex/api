<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
     protected $table='impuestos';
    protected $fillable=['nombre','porcentaje','descripcion','tipo','estado','id_user']; 
}
