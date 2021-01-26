<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRealtyRequest;
use App\Http\Resources\RealtyResource;
use App\Services\RealtySearchService;

class RealtyController extends Controller
{
    /**
     * Returns result of search by some parameters
     * @param SearchRealtyRequest $req
     * @return RealtyResource
     */
    public function search(SearchRealtyRequest $req)
    {
        $res = RealtySearchService::search($req);
        return new RealtyResource($res);
    }
}
