<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;
use App\Helpers\APIHelpers;

class UpdateSerials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serials:count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Product::where('deleted', 0)->where('hidden', 0)->get()
        ->map(function ($row) {
            // get valid - all serials count
            $path2='http://athath-ads.tk/api/serials/count/' . $row->id;
            $count = APIHelpers::fetchApi($path2, [], 'json', 'get');
            
            if ($count && $count->success) {
                $row->update(['remaining_quantity' => $count->data->count_valid_serials, 'total_quatity' => $count->data->count_all_serials]);
            }
        });
    }
}
