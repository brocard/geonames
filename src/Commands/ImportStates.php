<?php

namespace BrocardJr\Geo\Commands;

use BrocardJr\Geo\GeoServices;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

//use BrocardJr\Geo\Facades\GeoServices;

class ImportStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:import-states';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all states/region from countries database';

    /**
     * Create a new command instance.
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
        //'ES' => 2510769
        //'Catalunya' => 3336901
        //dd(GeoServices::children(['geonameId' => 3336901]));

        DB::table('states')->delete();

        $countries = DB::table('countries')->where('status', 1)->get();
        foreach ($countries as $country) {
            $items = GeoServices::children([
                'geonameId' => $country->geonameId,
                //'lang' => $country->language
            ]);

            if (!empty($items['totalResultsCount'])) {
                $msg = " | Country:{$country->name} | geonameId:{$country->geonameId}";
                $this->output->success('TotalResultsCount: '.$items['totalResultsCount'].$msg);

                $states = [];
                foreach ($items['geonames'] as $index => $item) {
                    $index++;

                    $nameState = !empty($item->adminName1)
                        ? $item->adminName1
                        : $item->name;

                    $this->info(' '.$index.' - '.$nameState);

                    array_set($states[$index], 'name', $nameState);
                    array_set($states[$index], 'country_id', $country->id);
                    array_set($states[$index], 'geonameId', $item->geonameId);
                }

                $this->insertData($states);
            }
        }

        $this->info("\n".' Successfully inserted states! Enjoy 🎉');

        //dd($countries);
        $this->info("\n Done!!! ".__CLASS__);
    }

    /**
     * @param array $results
     */
    protected function insertData($results = [])
    {
        DB::beginTransaction();
        try {
            foreach ($results as $id => $item) {
                $dateTime = Carbon::now()->toDateTimeString();

                DB::table('states')->insert([
                    [
                        'name'       => $item['name'],
                        'country_id' => $item['country_id'],
                        'created_at' => $dateTime,
                        'updated_at' => $dateTime,
                        'status'     => 1,
                        'geonameId'  => $item['geonameId'],
                    ],
                ]);
            }

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
