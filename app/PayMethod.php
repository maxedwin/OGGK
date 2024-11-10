<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{
    protected $table = 'pay_methods';
    protected $primaryKey = 'id_paymethod';
    public $timestamps = false;
}
