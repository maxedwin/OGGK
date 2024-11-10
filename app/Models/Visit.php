<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model 
{
    protected $table = 'visit';
    protected $primaryKey = 'id';
    public $timestamps = false;

}