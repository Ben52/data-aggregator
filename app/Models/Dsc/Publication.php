<?php

namespace App\Models\Dsc;

use App\Models\DscModel;
use App\Models\ElasticSearchable;
use App\Models\Documentable;

/**
 * Represents an overall digital publication.
 */
class Publication extends DscModel
{

    use ElasticSearchable;
    use Documentable;


    /**
     * Specific field definitions for a given class. See `transformMapping()` for more info.
     */
    protected function transformMappingInternal()
    {

        return [
            'web_url' => [
                "doc" => "URL to the publication",
                "type" => "string",
                "value" => function() { return $this->web_url; },
            ],
            'site' => [
                "doc" => "Which site in our multi-site Drupal installation owns this publication",
                "type" => "string",
                "value" => function() { return $this->site; },
            ],
            'alias' => [
                "doc" => "Used by Drupal in lieu of the id to generate pretty paths",
                "type" => "string",
                "value" => function() { return $this->alias; },
            ],
            'title' => [
                "doc" => "Official title of the publication",
                "type" => "string",
                "value" => function() { return $this->title; },
            ],
        ];

    }


    /**
     * Generate model-specific fields for an array representing the schema for this object.
     *
     * @return array
     */
    public function elasticsearchMappingFields()
    {

        return
            [
                'web_url' => [
                    'type' => 'keyword',
                ],
                'site' => [
                    'type' => 'keyword',
                ],
                'alias' => [
                    'type' => 'keyword',
                ],
                'title' => [
                    'type' => 'text',
                ],
            ];

    }

    /**
     * Get an example ID for documentation generation
     *
     * @return string
     */
    public function exampleId()
    {

        return "6566";

    }

}
