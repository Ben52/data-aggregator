<?php

namespace App\Behaviors;

trait CanQuery
{

    /**
     * Convenience curl wrapper. Accepts `GET` URL. Returns decoded JSON.
     *
     * @TODO If we use curl, we should keep the connection open, and reuse the same handle
     * @link https://stackoverflow.com/questions/18046637/should-i-close-curl-or-not
     *
     * @param string $url
     *
     * @return string
     */
    protected function query($url, $auth = '')
    {

        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

        if ($auth)
        {
            curl_setopt ($ch, CURLOPT_USERPWD, $auth);
        }

        ob_start();

        curl_exec ($ch);
        curl_close ($ch);
        $string = ob_get_contents();

        ob_end_clean();

        return json_decode($string);

    }


    /**
     * Determine a URL to a source system and execute a query,
     *
     * @param string $endpoint
     * @param string $id
     *
     * @return array JSON object as an array
     */
    private function queryResource($endpoint, $id)
    {

        $model = app('Resources')->getModelForEndpoint($endpoint);
        $source = $model::source();
        $url = $this->sourceUrl($source) .'/' .$endpoint .'/' .$id;

        $auth = '';

        if ($source == 'Web' && env('WEB_CMS_DATA_SERVICE_USERNAME'))
        {
            $auth = env('WEB_CMS_DATA_SERVICE_USERNAME') .':' .env('WEB_CMS_DATA_SERVICE_PASSWORD');
        }

        $result = $this->query( $url, $auth );

        if( is_null( $result ) ) {
            throw new \Exception("Cannot contact data service: " . $url);
        }

        return $result;
    }


    /**
     * Save a new model instance given an object retrieved from an external source.
     *
     * @param object  $datum
     * @param string  $model
     * @param boolean $fake  Whether or not to fill missing fields w/ fake data.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function saveDatum( $datum, $model )
    {

        // Don't use findOrCreate here, since it can cause errors due to Searchable
        $resource = $model::findOrNew( $datum->id );

        $resource->fillFrom($datum);
        $resource->attachFrom($datum);
        $resource->save();

        return $resource;

    }


    /**
     * Determine a source URL.
     *
     * @param string $source
     *
     * @return string
     */
    private function sourceUrl($source)
    {

        $sources = config('resources.sources');

        return $sources['$source'] ?? $sources['default'];

    }

}
