<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model 
{
    protected $table = 'reclamos';
    protected $primaryKey = 'idreclamo';
    public $timestamps = false;

}