<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    protected $table='tipo_pagos';
    protected $fillable=['descripcion','tipo','estado','id_user','codigo_sunat'];
}
