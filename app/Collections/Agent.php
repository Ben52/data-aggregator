<?php

namespace App\Collections;

class Agent extends CollectionsModel
{

    public $incrementing = false;
    protected $primaryKey = 'citi_id';
    protected $dates = ['source_created_at', 'source_modified_at', 'source_indexed_at', 'citi_created_at', 'citi_modified_at'];

    public function artworks()
    {
        return $this->hasMany('App\Collections\Artwork');
    }

    public function agentType()
    {

        return $this->belongsTo('App\Collections\AgentType');

    }

    public function getFillFieldsFrom($source)
    {

        return [
            'birth_date' => $source->date_birth,
            //'birth_place' => ,
            'death_date' => $source->date_death,
            //'death_place' => ,
            //'licensing_restricted' => ,
            'agent_type_citi_id' => \App\Collections\AgentType::where('title', 'Artist')->first()->citi_id,
        ];

    }

}