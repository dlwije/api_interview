<?php

namespace App\Http\Controllers;

use App\Jobs\FileImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelReader;

class FileImportController extends Controller
{
    public function importCSV(){

        try {

            $pathToCsv = public_path('csv_files/curvespark-sample.csv');
            // $rows is an instance of Illuminate\Support\LazyCollection

            FileImport::dispatch($pathToCsv);

            return response()->json([
                'status' => true,
                'message' => 'Successfully being inserted.',
            ], 200);

        }catch (\Exception $e){
            Log::error($e);
            return response()->json([
                'status' => true,
                'message' => 'Something went wrong. Please try again',
            ], 500);
        }
    }
}
