<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoVirtual extends Model
{
    protected $table = 'catalogos';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
