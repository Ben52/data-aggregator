<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collections\Agent;

use Aic\Hub\Foundation\AbstractController as BaseController;

class AgentPlacesController extends BaseController
{

    protected $model = \App\Models\Collections\AgentPlace::class;

    protected $transformer = \App\Http\Transformers\PivotTransformer::class;

    // agent/{id}/places
    public function forAgent(Request $request, $id) {

        return $this->collect( $request, function( $limit, $id ) {

            return Agent::findOrFail($id)->places;

        });

    }

}
