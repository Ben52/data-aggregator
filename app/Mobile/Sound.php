<?php

namespace App\Mobile;

class Sound extends MobileModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mobile_app_sounds';

    public function artworks()
    {

        return $this->belongsToMany('App\Collections\Artwork', 'artwork_mobile_app_sound');

    }

}
