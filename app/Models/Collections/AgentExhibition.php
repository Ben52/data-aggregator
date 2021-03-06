<?php

namespace App\Models\Collections;

use App\Models\AbstractPivot as BasePivot;

/**
 * A venue in which an exhibition took place
 */
class AgentExhibition extends BasePivot
{

    protected $primaryKey = 'citi_id';

    protected $casts =[
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];

    public function agent()
    {

        return $this->belongsTo('App\Models\Collections\Agent');

    }

    public function exhibition()
    {

        return $this->belongsTo('App\Models\Collections\Exhibition');

    }

    public function getUpdatedAtColumn()
    {

        return 'updated_at';

    }

    /**
     * Specific field definitions for a given class. See `transformMapping()` for more info.
     */
    protected function transformMappingInternal()
    {

        return [
            [
                "name" => 'agent_title',
                "doc" => "Name of the venue in which this exhibition took place",
                "type" => "string",
                "value" => function() { return $this->agent->title ?? null; },
            ],
            [
                "name" => 'agent_id',
                "doc" => "Unique identifier of the venue in which this exhibition took place",
                "type" => "number",
                "value" => function() { return $this->agent_citi_id; },
            ],
            [
                "name" => 'exhibition_title',
                "doc" => "Name of the exhibition",
                "type" => "string",
                "value" => function() { return $this->exhibition->title ?? null; },
            ],
            [
                "name" => 'exhibition_id',
                "doc" => "Unique identifier of the exhibition",
                "type" => "number",
                "value" => function() { return $this->exhibition_citi_id; },
            ],
            [
                "name" => 'date_start',
                'doc' => "Date the exhibition opened at this venue",
                "type" => "ISO 8601 date and time",
                'value' => function() { return $this->date_start ? $this->date_start->toIso8601String() : NULL; },
            ],
            [
                "name" => 'date_end',
                'doc' => "Date the exhibition closed at this venue",
                "type" => "ISO 8601 date and time",
                'value' => function() { return $this->date_end ? $this->date_end->toIso8601String() : NULL; },
            ],
            [
                "name" => 'is_host',
                "doc" => "Whether this venue was the host for this exhibition",
                "type" => "boolean",
                "value" => function() { return (bool) $this->is_host; },
            ],
            [
                "name" => 'is_organizer',
                "doc" => "Whether this venue was the organizer of this exhibition",
                "type" => "boolean",
                "value" => function() { return (bool) $this->is_organizer; },
            ],
        ];

    }

}
