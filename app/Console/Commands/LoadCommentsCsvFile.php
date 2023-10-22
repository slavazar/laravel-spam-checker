<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TeamTNT\TNTSearch\Classifier\TNTClassifier;

class LoadCommentsCsvFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-comments-csv-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load comments csv file';
    
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = storage_path('comments') . DIRECTORY_SEPARATOR . 'spam.csv';
        
        $classifier = new TNTClassifier();
        
        $row = 1;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                if ($row == 1) {
                    $row++;
                    continue;
                }
                
                //echo $data[0] . ' ' . $data[1] . "<br />\n";
                $classifier->learn($data[1], $data[0]);
                $row++;
            }
            fclose($handle);
        }

        $classifier->save(storage_path('comments') . DIRECTORY_SEPARATOR . 'comments.cls');
    }
}
