<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RealtyRequest;
use App\Http\Requests\SearchRealtyRequest;
use App\Http\Resources\RealtyResource;
use App\Models\Realty;
use App\Services\RealtySearchService;
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
        $res = RealtySearchService::search($req);
        return new RealtyResource($res);
    }

    /**
     * Save new model
     * @param RealtyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RealtyRequest $request)
    {
        try {
            $realty = new Realty($request->validated());
            $success = $realty->save();
        } catch (\Exception $ex){
            $success = false;
            Log::error($ex->getMessage(), ['exception' => $ex]);
        }

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Successfully stored' : 'Store error',
            'data' => $realty
        ]);
    }

    /**
     * Update model data
     * @param Realty $realty
     * @param RealtyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Realty $realty, RealtyRequest $request)
    {
        try {
            $success = $realty->update($request->validated());
        } catch (\Exception $ex){
            $success = false;
            Log::error($ex->getMessage(), ['exception' => $ex]);
        }

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Successfully updated' : 'Update error',
            'data' => $realty
        ]);
    }

    /**
     * Delete by model
     * @param Realty $realty
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Realty $realty)
    {
        try{
            $success = $realty->delete();
        } catch (\Exception $ex){
            $success = false;
            Log::error($ex->getMessage(), ['exception' => $ex]);
        }

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Successfully deleted' : 'Delete error'
        ]);
    }
}
