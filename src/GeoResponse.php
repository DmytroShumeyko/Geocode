<?php

namespace Shumex\Geocode;

class GeoResponse
{

    /**
     * GeoResponse constructor.
     *
     * @param $response
     */
    public function __construct($response)
    {
        $this->response = $response->results[0];
    }

    /**
     * Get raw response
     *
     * @return object
     */
    public function raw(): object
    {
        return (object)$this->response;
    }

    /**
     * Get address
     *
     * @return mixed
     */
    public function formattedAddress(): string
    {
        return $this->response->formatted_address;
    }

    /**
     * Get latitude
     *
     * @return mixed
     */
    public function latitude(): string
    {
        return $this->response->geometry->location->lat;
    }

    /**
     * Get longitude
     *
     * @return mixed
     */
    public function longitude(): string
    {
        return $this->response->geometry->location->lng;
    }

}
