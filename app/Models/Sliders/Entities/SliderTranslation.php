<?php

namespace App\Models\Sliders\Entities;

use Illuminate\Database\Eloquent\Model;

class SliderTranslation extends Model
{
    protected $guarded = [];

    public function Sliders()
    {
        return $this->hasMany('App\Models\Sliders\Entities\Slider');
    }

    //
}
