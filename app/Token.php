<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Token extends Model
{
    protected $table = 'tokens';

    protected $fillable = [
        'token'
    ];

    public function verify()
    {
        return true;
    }

}
