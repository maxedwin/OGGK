<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Transporte extends Model {
    protected $table = 'transporte';
    protected $primaryKey = 'idtransporte';
    protected $fillable = ['nombre_trans'];
    public $timestamps = false;

}