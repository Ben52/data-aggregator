<?php

namespace App\Http\Transformers;

use App\Models\Shop\Category;

class ShopCategoryTransformer extends ApiTransformer
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['children'];

    /**
     * Include child shop categories.
     *
     * @param  \App\Models\Shop\Category  $category
     * @return League\Fractal\ItemResource
     */
    public function includeChildren(Category $category)
    {
        return $this->collection($category->children, new ShopCategoryTransformer, false);
    }

}
