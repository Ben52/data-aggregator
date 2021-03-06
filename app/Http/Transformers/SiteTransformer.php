<?php

namespace App\Http\Transformers;

use App\Models\StaticArchive\Site;

class SiteTransformer extends ApiTransformer
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['artworks'];


    /**
     * Include artworks.
     *
     * @param  \App\Models\StaticArchive\Site  $site
     * @return League\Fractal\ItemResource
     */
    public function includeArtworks(Site $site)
    {
        return $this->collection($site->artworks, new ArtworkTransformer, false);
    }

}
