<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Countries;
use App\Helpers\FunctionHelper;

class CountriesTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:countries-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CURL data ke RapidAPI untuk mendapatkan data countries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $countries_api = FunctionHelper::rapidApiFootball('countries', 'GET');
        if (empty($countries_api)) return false;

        $countries = [];

        foreach ($countries_api['response'] as $countries_response)
        {
            $countries[] = [
                'code' => $countries_response['code'],
                'name' => $countries_response['name'],
                'flag' => $countries_response['flag'],
            ];
        }
        Contents::insert($countries);
        $this->info('Custom task executed successfully!');
    }
}
