<?php

namespace App\Http\Transformers;

use Illuminate\Support\Facades\Log;

use League\Fractal\TransformerAbstract;

class ApiTransformer extends TransformerAbstract
{

    public $excludeIdsAndTitle = false;
    public $excludeDates = false;


    /**
     * Used for only returning a subset of fields.
     * Expects a comma-separated string or an array.
     *
     * @link https://github.com/thephpleague/fractal/issues/226
     *
     * @var string
     */
    protected $fields;

    public function __construct($fields = null)
    {

        $this->fields = $this->getFields( $fields );

    }

    /**
     * Parse out the fields variable passed via constructor.
     * Expects a comma-separated string or an array.
     *
     * @var array
     */
    private function getFields( $fields = null )
    {

        if( !$fields ) {
            return null;
        }

        if( is_array( $fields ) ) {
            return $fields;
        }

        if( is_string( $fields ) ) {
            return explode(',', $fields);
        }

        return null;

    }

    /**
     * Turn this item object into a generic array.
     *
     * @param  Illuminate\Database\Eloquent\Model  $item
     * @return array
     */
    public function transform($item)
    {

        $data = array_merge(
            $this->transformIdsAndTitle($item),
            $this->transformFields($item),
            $this->transformDates($item)
        );

        return $this->filterFields( $data );

    }

    protected function transformFields($item)
    {

        return $item->transform();

    }


    protected function transformIdsAndTitle($item)
    {

        if ($this->excludeIdsAndTitle)
        {

            return [];

        }

        return [
            'id' => $item->getAttributeValue($item->getKeyName()),
            'title' => $item->title,
        ];

    }

    protected function transformDates($item)
    {

        if ($this->excludeDates)
        {
            return [];
        }

        $dates = [];

        if ( $item->source_modified_at )
        {
            $dates['last_updated_source'] = $item->source_modified_at->toIso8601String();
        }

        if ( $item->updated_at )
        {
            $dates['last_updated'] = $item->updated_at->toIso8601String();
        }

        return $dates;

    }

    protected function filterFields($data)
    {
        if (is_null($this->fields)) {
            return $data;
        }

        // Unset default includes not present in fields param
        // https://github.com/thephpleague/fractal/issues/143
        $this->setDefaultIncludes( array_intersect($this->defaultIncludes, $this->fields) );

        // Filter $data to only provide keys specified in fields param
        return array_intersect_key($data, array_flip((array) $this->fields));
    }

}
