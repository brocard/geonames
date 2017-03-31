<?php

namespace BrocardJr\Geo;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class GeoServices
{
    const FORMAT_RESPONSE = 'JSON';
    const EARTH_GEONAMEID = 6295630;

    /**
     * @var array
     */
    public static $continentCodes = [
        'AF' => 6255146,
        'AS' => 6255147,
        'EU' => 6255148,
        'NA' => 6255149,
        'OC' => 6255151,
        'SA' => 6255150,
        'AN' => 6255152,
    ];

    /**
     * @var string
     */
    public static $base_uri = 'http://api.geonames.org/';

    /**
     * @var int
     */
    public static $maxRows = 400;

    /**
     * @var bool
     */
    public static $jsonAssoc = false;

    /**
     * @var array
     */
    protected static $default_params = [];

    protected static $supportedMethods = [
        'countryInfo' => [
            'params' => ['country', 'lang'],
            'root'   => 'geonames',
        ],
        'children' => [
            'params' => ['geonameId', 'maxRows'],
            'root'   => 'geonames',
        ],
    ];

    private static $_results;

    /**
     *  Singleton Geoservices Class.
     */
    private static $instance;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param $name
     * @param array $args
     *
     * @return string
     */
    public static function __callStatic($name, $args = [])
    {
        // Note: value of $name is case sensitive.
        if (array_key_exists($name, self::$supportedMethods)) {
            self::setDefaultParams();
            $keyCache = $name.implode('.', $args[0]);
            if (Cache::has($keyCache)) {
                logger('get Cache...', ['keyCache' => $keyCache]);

                return Cache::get($keyCache, function () use ($name, $args) {
                    return static::getResponse($name, $args);
                });
            } else {
                logger('set Cache...', ['keyCache' => $keyCache]);

                $expiresAt = Carbon::now()->addMinutes(30);
                $response = static::getResponse($name, $args);
                Cache::put($keyCache, $response, $expiresAt);

                return $response;
            }
        }

        throw new \RuntimeException('Not method allowed...');
    }

    /**
     * Get response from geonames.org api rest for any function.
     *
     * @param $name
     * @param array $args
     *
     * @return mixed
     */
    public static function getResponse($name, $args = [])
    {
        logger('function getResponse...', ['method' => $name]);

        $client = new Client(['base_uri' => self::$base_uri]);
        $uri = $name.self::FORMAT_RESPONSE;
        $query = isset($args)
            ? array_merge(self::$default_params, $args[0])
            : self::$default_params;

        $response = $client->request('GET', $uri, [
            'query' => $query,
        ]);

        if ($response->getStatusCode() == Response::HTTP_OK) {
            self::$_results = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);

            if (isset(self::$supportedMethods[$name]['root'])) {
                self::_parseRoot(self::$supportedMethods[$name]['root']);
            }

            return self::$_results;
        }

        throw new \RuntimeException('Error with response');
    }

    /**
     * Set de default params.
     */
    private static function setDefaultParams()
    {
        self::$default_params = [
            'formatted' => true,
            'username'  => env('GEONAMES_USERNAME', 'demo'),
            'lang'      => 'en',
            'maxRows'   => self::$maxRows,
        ];
    }

    /**
     * @param $root
     */
    private static function _parseRoot($root)
    {
        if (isset(self::$_results[$root]) && is_array(self::$_results[$root])) {
            $e = current(self::$_results[$root]);
            if (is_array($e)) {
                $cnt = count(self::$_results[$root]);
                for ($i = 0; $i < $cnt; $i++) {
                    self::$_results[$root][$i] = new GeoResult(self::$_results[$root][$i]);
                }
            } else {
                self::$_results[$root] = new GeoResult(self::$_results[$root]);
            }
        }
    }
}
