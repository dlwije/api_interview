<?php

namespace App\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Spatie\SimpleExcel\SimpleExcelReader;

class FileImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $file_path;
    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rows = SimpleExcelReader::create($this->file_path)->getRows();

        /*print_r($rows);exit();*/
        $rows->each(function(array $rowProperties) {
            // in the first pass $rowProperties will contain
            $data_arr = [
                'email' => Controller::encryptData($rowProperties['email']),
                'first_name' => $rowProperties['first_name'],
                'last_name' => $rowProperties['last_name'],
                'tags' => $rowProperties['tags'],
                'ip' => $rowProperties['ip'],
            ];

            \App\Models\FileImport::create($data_arr);
        });
    }
}
