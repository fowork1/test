<?php


namespace App\Services;


use App\Http\Requests\SearchRealtyRequest;
use App\Models\Realty;

class RealtySearchService
{
    public static function search(SearchRealtyRequest $req)
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

        return $query->get();
    }
}
