<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    protected $table='tipo_pagos';
    protected $fillable=['nombre','descripcion','tipo','estado','id_user'];
}
