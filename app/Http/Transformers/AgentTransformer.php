<?php

namespace App\Http\Transformers;

use App\Collections\Agent;
use League\Fractal\TransformerAbstract;

class AgentTransformer extends ApiTransformer
{

    public $citiObject = true;
    
    /**
     * Turn this item object into a generic array.
     *
     * @param  \App\Agent  $item
     * @return array
     */
    public function transformFields($item)
    {
        return [
            'birth_date' => $item->birth_date,
            'birth_place' => $item->birth_place,
            'death_date' => $item->death_date,
            'death_place' => $item->death_place,
            'licensing_restricted' => (bool) $item->licensing_restricted,
            'agent_type' => $item->agentType()->getResults() ? $item->agentType()->getResults()->title : '',
            'agent_type_id' => $item->agent_type_citi_id,
        ];
    }
}