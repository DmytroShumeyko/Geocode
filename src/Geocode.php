<?php

namespace Shumex\Geocode;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Shumex\Geocode\Exceptions\GeoException;
use \GuzzleHttp\Client;


class Geocode
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $apiKey;
    protected $language;

    /**
     * Geocode constructor.
     */
    public function __construct()
    {
        $this->apiKey = config('geocode.apikey');
        $this->language = config('geocode.language');
    }

    /**
     * Make static access
     *
     * @return static
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Try to find coordinates by address
     *
     * @param string $address
     * @param string|null $language
     * @return GeoResponse
     * @throws GeoException
     */
    public function address(string $address, string $language = null): GeoResponse
    {

        if (empty($address)) {
            throw new GeoException('Empty arguments.');
        }

        $params = ['address' => $address];

        if (!empty($this->apiKey)) {
            $params['key'] = $this->apiKey;
        }

        if (!empty($this->language)) {
            if ($language === null) {
                $params['language'] = $this->language;
            } else {
                $params['language'] = $language;
            }
        }

        if (Cache::has(implode('&',$params))){
            $response = Cache::get(implode('&',$params));
            return new GeoResponse(json_decode($response));
        }

        try {
            $client = new Client();
            $response = json_decode($client->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => $params
            ])->getBody());
        } catch (ClientException $exception) {
            throw new GeoException('Bad Request');
        }

        # check for status in the response
        switch ($response->status) {

            case "ZERO_RESULTS": # indicates that the geocode was successful but returned no results. This may occur if the geocoder was passed a non-existent address.
            case "OVER_QUERY_LIMIT": # indicates that you are over your quota.
            case "REQUEST_DENIED": # indicates that your request was denied.
            case "INVALID_REQUEST": # generally indicates that the query (address, components or latlng) is missing.
            case "UNKNOWN_ERROR":
                throw new GeoException($response->status);

            case "OK": # indicates that no errors occurred; the address was successfully parsed and at least one ggeocode was returned.
                Cache::forever(implode('&',$params), json_encode($response));
                return new GeoResponse($response);
        }

    }

    /**
     * Try to find address by coordinates
     *
     * @param string $lat
     * @param string $lng
     * @return GeoResponse
     * @throws GeoException
     */
    public function latLng(string $lat, string $lng): GeoResponse
    {

        if (empty($lat) || empty($lng)) {
            throw new GeoException('Empty arguments.');
        }

        $params = ['latlng' => $lat . ',' . $lng];
        if (!empty($this->apiKey)) {
            $params['key'] = $this->apiKey;
        }

        if (!empty($this->language)) {
            $params['language'] = $this->language;
        }

        if (Cache::has(implode('&',$params))){
            $response = Cache::get(implode('&',$params));
            return new GeoResponse(json_decode($response));
        }

        try {
            $client = new Client();
            $response = json_decode($client->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => $params
            ])->getBody());
        } catch (ClientException $exception) {
            throw new GeoException('Bad Request');
        }

        # check for status in the response
        switch ($response->status) {

            case "ZERO_RESULTS": # indicates that the geocode was successful but returned no results. This may occur if the geocoder was passed a non-existent address.
            case "OVER_QUERY_LIMIT": # indicates that you are over your quota.
            case "REQUEST_DENIED": # indicates that your request was denied.
            case "INVALID_REQUEST": # generally indicates that the query (address, components or latlng) is missing.
            case "UNKNOWN_ERROR":
                throw new GeoException($response->status);

            case "OK": # indicates that no errors occurred; the address was successfully parsed and at least one ggeocode was returned.
                Cache::forever(implode('&',$params), json_encode($response));
                return new GeoResponse($response);
        }

    }

}
