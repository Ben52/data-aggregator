<?php

namespace App\Http\Controllers;

use App\Mobile\Tour;
use Illuminate\Http\Request;

class ToursController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @param null $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() != 'GET')
        {

            $this->respondMethodNotAllowed();

        }

        $ids = $request->input('ids');
        if ($ids)
        {

            return $this->showMutliple($ids);

        }

        $limit = $request->input('limit') ?: 12;
        if ($limit > static::LIMIT_MAX) return $this->respondForbidden('Invalid limit', 'You have requested too many tours. Please set a smaller limit.');
        
        $all = Tour::paginate();
        return response()->collection($all, new \App\Http\Transformers\TourTransformer);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mobile\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $tourId)
    {

        if ($request->method() != 'GET')
        {

            $this->respondMethodNotAllowed();

        }

        try
        {
            if (intval($tourId) <= 0)
            {
                return $this->respondInvalidSyntax('Invalid identifier', "The tour identifier should be a number. Please ensure you're passing the correct source identifier and try again.");
            }

            $item = Tour::find($tourId);

            if (!$item)
            {
                return $this->respondNotFound('Tour not found', "The tour you requested cannot be found. Please ensure you're passing the source identifier and try again.");
            }

            return response()->item($item, new \App\Http\Transformers\TourTransformer);
        }
        catch(\Exception $e)
        {
            return $this->respondFailure();
        }
        
    }

    public function showMutliple($ids = '')
    {

        $ids = explode(',',$ids);
        if (count($ids) > static::LIMIT_MAX)
        {
            
            return $this->respondForbidden('Invalid number of ids', 'You have requested too many ids. Please send a smaller amount.');
            
        }
        $all = Tour::find($ids);
        return response()->collection($all, new \App\Http\Transformers\TourTransformer);
        
    }

}
