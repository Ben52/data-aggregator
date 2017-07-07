<?php

namespace App\Collections;

class ArtworkDate extends CollectionsModel
{

    protected $dates = ['date', 'source_created_at', 'source_modified_at', 'source_indexed_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['artwork_citi_id', 'date', 'qualifier', 'preferred'];
    
}
