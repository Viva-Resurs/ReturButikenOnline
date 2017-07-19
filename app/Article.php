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

   /**
     * Get articles creator.
     *
     * @return user    
     */
    public function creator()
    {
        return $this->belongsToMany(User::class, 'articles_creator');
    }

    /**
     * Get contacts related to the article.
     *
     * @return users    
     */
    public function contacts()
    {
        return $this->belongsToMany(User::class, 'articles_contact');
    }

    /**
     * Get sections related to the article.
     *
     * @return sections    
     */
    public function sections() {
        return $this->belongsToMany(Section::class,'articles_section');
    }

    /**
     * Get categories related to the article.
     *
     * @return categories    
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'articles_category');
    }

    /**
     * Get images related to the article.
     *
     * @return images    
     */
    public function images() {
        return $this->belongsToMany(Image::class, 'articles_image');
    }

    /**
     * Returns if article is active.
     *
     * @return boolean true or false    
     */
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

    /**
     * Returns if article have a current or future 
     * publish and bidding interval.
     *
     * @return boolean true or false    
     */
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


    /**
     * Returns if article matches a specific audience.
     *
     * @return boolean true or false    
     */
    public function matchAudience($audience)
    {
        if ($this->public==1 && $audience=='public')
            return true;
        if ($this->public==0 && $audience=='internal')
            return true;
        return false;
    }


    /**
     * Returns articles serial number.
     *
     * @return string articlenr    
     */
    public function getArticleNR()
    {
        // SectionID - ArticleID
        // 00 - 000.000

        $sn = "00";
        $sectionID = strval($this->sections[0]->id);
        if (strlen($sectionID) == 1) $sn = "0" . $sectionID;
        if (strlen($sectionID) == 2) $sn = $sectionID;

        $an = "000.000";
        $articleID = strval($this->id);
        if ($articleID < 999999 && $articleID >= 100000)
            $an = floor($articleID/1000) . '.' . ($articleID%1000);
        if ($articleID < 100000 && $articleID >= 10000)
            $an = '0'+floor($articleID/1000) . '.' . ($articleID%1000);
        if ($articleID < 10000 && $articleID >= 1000)
            $an = '00' . floor($articleID/1000) . '.' . ($articleID%1000);
        if ($articleID < 1000 && $articleID >= 100)
            $an = '000.' . $articleID;
        if ($articleID < 100 && $articleID >= 10)
            $an = '000.0' . $articleID;
        if ($articleID < 10)
            $an = '000.00' . $articleID;

        return $sn . '-' . $an;
    }

}
