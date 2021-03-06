<?php

namespace App\Http\Controllers;

use Aic\Hub\Foundation\AbstractController as BaseController;

class GalleriesController extends BaseController
{

    protected $model = \App\Models\Collections\Gallery::class;

    protected $transformer = \App\Http\Transformers\GalleryTransformer::class;

}
