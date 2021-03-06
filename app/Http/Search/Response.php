<?php

namespace App\Http\Search;

class Response
{

    /**
     * Response as it came back from Elasticsearch
     *
     * @var array
     */
    public $searchResponse;


    /**
     * Params passed to Elasticsearch
     *
     * @var array
     */
    public $searchParams;


    /**
     * Create a new request instance.
     *
     * @param array $searchResponse Response as it came back from Elasticsearch
     * @param array $searchParams Params passed to Elasticsearch
     *
     * @return void
     */
    public function __construct(array $searchResponse, array $searchParams)
    {
        $this->searchResponse = $searchResponse;
        $this->searchParams = $searchParams;
    }


    /**
     * Transform response for search queries.
     *
     * @return array
     */
    public function getSearchResponse()
    {

        $response = array_merge(
            $this->paginate(),
            $this->data()
        );

        $response = array_merge(
            $response,
            $this->getAutocompleteWithTitle()
        );

        $response = array_merge(
            $response,
            $this->aggregate()
        );

        $response = array_merge(
            [
                'preference' => $this->searchParams['preference'] ?? null,
            ],
            $response
        );

        return $response;

    }


    /**
     * Transform response for explain queries.
     *
     * @return array
     */
    public function getRawResponse()
    {

        return $this->searchResponse;

    }


    /**
     * Transform response for autocomplete queries.
     *
     * @return array
     */
    public function getAutocompleteWithTitleResponse() {

        // Defaulting to [] is safe, but ineffecient: catch empty `q` earlier!
        return $this->getAutocompleteWithTitle()['suggest']['autocomplete'] ?? [];

    }


    /**
     * Transform response for autocomplete queries. Pass-through the source.
     *
     * @return array
     */
    public function getAutocompleteWithSourceResponse() {

        $options = array_get($this->searchResponse, 'suggest.autocomplete.0.options');

        if ($options) {
            return array_pluck($options, '_source');
        }

        return [];

    }


    /**
     * Add pagination to response.
     *
     * @return array
     */
    private function paginate()
    {

        // We assume that `size` and `from` have been set via getPaginationParams()
        // This method should not be used for endpoints that return no results

        // LengthAwarePaginator has trouble here
        $total = $this->searchResponse['hits']['total'];
        $limit = $this->searchParams['size'] ?? 10;
        $offset = $this->searchParams['from'] ?? 0;

        // Avoid division by zero
        if( $limit > 0 ) {

            $total_pages = ceil( $total / $limit );
            $current_page = floor( $offset / $limit ) + 1;

        } else {

            $total_pages = null;
            $current_page = null;

        }

        $pagination = [
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'total_pages' => $total_pages,
            'current_page' => $current_page,
        ];

        return [
            'pagination' => $pagination
        ];

    }


    /**
     * Add data (i.e. hits, results) to response.
     *
     * @return array
     */
    private function data()
    {

        $hits = $this->searchResponse['hits']['hits'];
        $results = [];

        // Reduce to just the _source objects
        foreach( $hits as $hit ) {

            $result = [
                '_score' => $hit['_score']
            ];

            // Avoid filtering fields here: filter fields via `_source` in Request instead
            // This will reduce AWS ES load - it won't need to return as much data
            // Note that `_source` might be undefined if `_source` was set to false in Request
            if( isset( $hit['_source'] ) ) {
                $result = array_merge( $result, $hit['_source'] );
            }

            $results[] = $result;

        }

        return [
            'data' => $results
        ];

    }


    /**
     * Add suggestions (i.e. completion, phrases) to response.
     *
     * @return array
     */
    private function getAutocompleteWithTitle()
    {

        $suggest = [];

        $options = array_get($this->searchResponse, 'suggest.autocomplete.0.options');

        if ($options) {
            $suggest['autocomplete'] = array_pluck($options, '_source.title');
        }

        if ($suggest)
        {
            return ['suggest' => $suggest];
        }

        return [];

    }


    /**
     * Add aggregations (i.e. facets) to response. Again, straight pass-through.
     *
     * @return array
     */
    private function aggregate()
    {

        $aggregations = $this->searchResponse['aggregations'] ?? null;

        return $aggregations ? ['aggregations' => $aggregations] : [];

    }

}
