<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table='empresas';
    protected $fillable=['nombre','ruc','direccion','departamento','provincia','distrito','telefono1','telefono2','web','email','imagen','estado','id_user']; 
}
