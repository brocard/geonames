<?php

namespace BrocardJr\Geo\Commands;

use BrocardJr\Geo\GeoServices;
use Illuminate\Console\Command;

class ImportCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:countries-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to get countries from geonames';

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
        $excludeIds = [
            'AQ' => 6697173,
            'AS' => 5880801,
            'EH' => 2461445,
            'SJ' => 607072
        ];

        $items = [];
        foreach (GeoServices::$continentCodes as $code => $id) {
            //if ($code=='OC') {
                $countries = GeoServices::children([
                    'geonameId' => $id,
                    //'lang' => 'es',
                ]);
                foreach ($countries['geonames'] as $country) {

                    $items[] = $country;
                }
            //}
        }

        $result = [];
        foreach ($items as $index => $item) {
            $index++;

            if (in_array($item->geonameId, $excludeIds))
                continue;

            $result['data'][] = [
                'num' => $index,
                'countryId' => $item->countryId,
                'code' => $item->countryCode,
                'name' => $item->name,
                //'geonameId' => $item->geonameId,
                //'fclName' => $item->fclName,
                'lat' => $item->lat,
                'lng' => $item->lng,
            ];
        }


        $this->table(['N°', 'id', 'code', 'name', 'latitude', 'longitude'], $result['data']);
        $this->info("\n Done!!! " . __CLASS__);
    }
}
