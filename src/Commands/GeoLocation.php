<?php

namespace BrocardJr\Geo\Commands;

use BrocardJr\Geo\GeoServices;
use Illuminate\Console\Command;

class GeoLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:countries {lang?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all countries from json';

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
        $lang = ($this->argument('lang')) ? $this->argument('lang') : 'en';
        $items = GeoServices::children([
            'geonameId' => GeoServices::EARTH_GEONAMEID,
            'lang' => $lang,
            'style' => 'full',
        ]);


        $result = [];
        foreach ($items['geonames'] as $index => $item) {
            $result[$index] = [
                'geonameId' => $item->geonameId,
                'name' => $item->name,
                'code' => $item->continentCode
            ];
        }
        $this->table(['id', 'Name', 'continentCode'], $result);
        dd();

        $countries = [];
        foreach (GeoServices::$continentCodes as $code => $geoID) {
            $countries[$code] = GeoServices::children([
                'geonameId' => $geoID,
                'lang' => $lang,
            ]);
        }

        dd($countries);


        $this->info("\n Done!!! " . __CLASS__);
    }
}
