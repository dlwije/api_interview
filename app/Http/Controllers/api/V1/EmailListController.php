<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\FileImport;
use Illuminate\Http\Request;

class EmailListController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:api');
        $this->guard = 'api';
    }
    /**
     * Display a listing of the resource.
     */
    public function index($perPage)
    {

        $responseList = FileImport::query()->paginate($perPage);

        if($responseList->count() > 0) {
            $resp =[
                'status' => true,
                'message' => 'Records found!',
                'data' => $responseList
            ];
        }else{
            $resp =[
                'status' => false,
                'message' => 'Records not found!',
                'data' => ''
            ];
        }

        return response()->json($resp,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
