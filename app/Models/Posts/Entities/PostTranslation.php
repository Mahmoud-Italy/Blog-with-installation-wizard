<?php

namespace App\Models\Posts\Entities;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    protected $guarded = [];

    public function Posts()
    {
        return $this->hasMany('App\Models\Posts\Entities\Post');
    }

    /** Here we go..  **/

    
    // get estimating reading on how long the content takes such as 10min read
    public static function estimate_reading_time($content, $wordsPerMinute=200, $minutesOnly=true, $abbreviated=true, $suffix='read')
    {
        $wordCount = str_word_count(strip_tags($content));

        $wordsPerMinute = (int) $wordsPerMinute;
        if( $wordsPerMinute <= 0 ){
            $wordsPerMinute = 200;
        }
        // added / 60 to decrease amunt of mintues
        $minutes = floor($wordCount / $wordsPerMinute / 60);
        $seconds = floor($wordCount % $wordsPerMinute / ($wordsPerMinute / 60));

        if( $minutesOnly === true && $minutes > 0 ){
            if( $seconds >=30 ){
                $minutes++;
            }
        }

        if( $abbreviated === true ){
            $strMinutes = 'min';
            $strSeconds = 'sec';
        } else {
            $strMinutes = ($minutes == 1) ? "min" : "min";
            $strSeconds = ($seconds == 1) ? "sec" : "sec";
        }

        if ($minutes == 0) {
            $returnString = "{$seconds} {$strSeconds}";
        } elseif( $minutesOnly === true ) {
            $returnString = "{$minutes} {$strMinutes}";
        } else {
            $returnString = "{$minutes} {$strMinutes}, {$seconds} {$strSeconds}";
        }

        if( is_string( $suffix ) && !empty( trim( $suffix ) ) ){
            $returnString.= ' ' . trim( $suffix );
        }

        return $returnString;
    }


}
