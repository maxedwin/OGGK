<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendMethod extends Model
{
    protected $table = 'send_methods';
    protected $primaryKey = 'id_sendmethod';
    public $timestamps = false;
}
