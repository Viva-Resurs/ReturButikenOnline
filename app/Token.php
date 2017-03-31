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

    public function generate($length = 32)
    {
        if (!isset($length) || intval($length) <= 32 ){
            $length = 32;
        }
        // PHP 7
        if (function_exists('random_bytes')){
            $this->token = bin2hex(random_bytes($length));
        }
        // PHP 5 >= 5.3.0, PHP 7
        if (function_exists('openssl_random_pseudo_bytes')){
            $this->token = bin2hex(openssl_random_pseudo_bytes($length));
        }
    }

}
