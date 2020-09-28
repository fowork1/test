<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRealtyRequest;
use App\Http\Resources\RealtyResource;
use App\Models\Realty;
use Illuminate\Support\Facades\Log;

class RealtyController extends Controller
{
    /**
     * Returns result of search by some parameters
     * @param SearchRealtyRequest $req
     * @return RealtyResource
     */
    public function search(SearchRealtyRequest $req)
    {
        $query = Realty::query();

        if ($req->name){
            $query->where('name', 'ilike', '%'.$req->name.'%');
        }

        if($req->price_from){
            $query->where('price', '>=', $req->price_from);
        }

        if($req->price_to){
            $query->where('price', '<=', $req->price_to);
        }

        if($req->bedrooms){
            $query->where('bedrooms', $req->bedrooms);
        }

        if($req->bathrooms){
            $query->where('bathrooms', $req->bathrooms);
        }

        if($req->storeys){
            $query->where('storeys', $req->storeys);
        }

        if($req->garages){
            $query->where('garages', $req->garages);
        }

        // Кусок для отлова запросов в лог
        /*$q = vsprintf(str_replace(array('?'), array('\'%s\''), $query->toSql()), $query->getBindings());
        Log::debug("query ------------------------ " . PHP_EOL . $q);*/

        return new RealtyResource($query->get());
    }
}
