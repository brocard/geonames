<?php

namespace BrocardJr\Geo\Commands;

use BrocardJr\Geo\GeoServices;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:import-countries';

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
        //dd(Carbon::now()->toDateTimeString());

        $excludeIds = [
            'AQ'=> 6697173, 'AS'=> 5880801, 'EH'=> 2461445,
            'SJ'=> 607072, 'AX'=> 661882, 'HM'=> 1547314, 'BV'=>3371123,
        ];

        $items = [];
        $progress = $this->output->createProgressBar(count(GeoServices::$continentCodes));
        $progress->setBarWidth(50);

        $this->info(' Read continents to get all countries...');

        foreach (GeoServices::$continentCodes as $code => $id) :

            usleep(90000);
        $countries = GeoServices::children(['geonameId' => $id]);

        foreach ($countries['geonames'] as $country) {
            $items[] = $country;
        }

        $progress->advance();
        endforeach;
        $progress->finish();

        $this->line("\n");

        $bar = $this->output->createProgressBar(count($items));
        $bar->setBarWidth(46);

        $this->info(' Import data countries from api.geonames.org...');

        $result = [];
        foreach ($items as $index => $item) {
            $index++;

            if (in_array($item->geonameId, $excludeIds)) {
                continue;
            }

            $countryInfo = GeoServices::countryInfo([
                'country' => $item->countryCode,
                'style'   => 'full',
            ]);

            $country = array_get($countryInfo, 'geonames.0');

            if (empty($country))
                continue;

            $lang = array_first(explode(',', $country->languages));
            $language = array_first(explode('-', $lang));

            $result['data'][$item->countryId] = [
                'num'             => $index,
                'countryId'       => $item->countryId,
                'code'            => $item->countryCode,
                'code_iso_alpha3' => $country->isoAlpha3,
                'name'            => $country->countryName,
                //'geonameId' => $item->geonameId,
                //'fclName' => $item->fclName,
                'lat'       => $item->lat,
                'lng'       => $item->lng,
                'continent' => $country->continent,
                'currency'  => $country->currencyCode,
                'languages' => $lang,
                'lang'      => empty($language) ? null : (strlen($language) > 2 ? 'en' : $language),
            ];

            $bar->advance();
        }

        $bar->finish();
        $this->line("\n");

        $this->table([
            'N°', 'GeonameId', 'isoAlpha2', 'isoAlpha3', 'Name', 'Latitude',
            'Longitude', 'Cont', 'Currency', 'Lang', 'Language',
        ], array_values(array_sort($result['data'], function ($value) {
            return $value['name'];
        })));

        $this->insertDataCountry($result);

        $this->info("\n Done!!! ".__CLASS__);
    }

    protected function insertDataCountry(array $results)
    {
        $data = array_values(array_sort($results['data'], function ($value) {
            return $value['name'];
        }));

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('countries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        DB::beginTransaction();
        try {
            foreach ($data as $id => $item) {
                $dateTime = Carbon::now()->toDateTimeString();

                DB::table('countries')->insert([
                    [
                        'name'          => $item['name'],
                        'iso_code2'     => $item['code'],
                        'iso_code3'     => $item['code_iso_alpha3'],
                        'lang'          => $item['languages'],
                        'language'      => $item['lang'],
                        'currency_code' => $item['currency'],
                        'created_at'    => $dateTime,
                        'updated_at'    => $dateTime,
                        'geonameId'     => $item['countryId'],
                    ],
                ]);
            }

            DB::commit();

            $this->info('Successfully inserted Countries! Enjoy 🎉');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
