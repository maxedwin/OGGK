<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Almacen extends Model {
    protected $table = 'almacen';
    protected $primaryKey = 'idalmacen';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function parent(){
    	return $this->hasOne('App\Models\Almacen','idempresa');
    }

    public function user(){
    	return $this->belongsTo('App\User','idempresa','idempresa');
    }
}