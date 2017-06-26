<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'name',
        'desc',
        'price',
        'amount',
        'public',
        'active',
        'publish_interval',
        'bidding_interval'
    ];

    public function creator()
    {
        return $this->belongsToMany(User::class, 'articles_creator');
    }

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'articles_contact');
    }

    public function sections() {
        return $this->belongsToMany(Section::class,'articles_section');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'articles_category');
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'articles_image');
    }

    public function isActive()
    {
        $today = Carbon::now();
        if ($this->publish_interval=='')
            return false;
        $parts = explode('|',$this->publish_interval);
        if (count($parts)<2)
            return false;

        if (!$this->active)
            return false;

        $start = Carbon::createFromFormat('Y-m-d H:i:s', trim($parts[0]));
        $end   = Carbon::createFromFormat('Y-m-d H:i:s', trim($parts[1]));

        //Checks if current date is between start and end date.
        return $today->between($start, $end);
    }

    public function haveCurrentOrFutureInterval($type){
        if ($type == 0)
            $parts = explode('|',$this->publish_interval);
        
        if ($type == 1)    
            $parts = explode('|',$this->bidding_interval);
        
        if (count($parts) < 2)
            return false;
        
        if (!$this->active)
            return false;       

        $start = Carbon::createFromFormat('Y-m-d H:i:s', trim($parts[0]));
        $end   = Carbon::createFromFormat('Y-m-d H:i:s', trim($parts[1]));
        
        $today = Carbon::now();  
              
        return ($end->gt($start) && ($today->between($start,$end) || $start->isFuture()));
    }

    public function matchAudience($audience)
    {
        if ($this->public==1 && $audience=='public')
            return true;
        if ($this->public==0 && $audience=='internal')
            return true;
        return false;
    }

}
