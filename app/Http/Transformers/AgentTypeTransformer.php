<?php

namespace App\Http\Transformers;

use App\Collections\AgentType;
use League\Fractal\TransformerAbstract;

class AgentTypeTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array.
     *
     * @param  \App\AgentType  $item
     * @return array
     */
    public function transform(AgentType $item)
    {
        return [
            'id' => $item->citi_id,
            'title' => $item->title,
            'last_updated_lpm_fedora' => $item->api_modified_at->toDateTimeString(),
            'last_updated_lpm_solr' => $item->api_indexed_at->toDateTimeString(),
            'last_updated' => $item->updated_at->toDateTimeString(),
        ];
    }
}