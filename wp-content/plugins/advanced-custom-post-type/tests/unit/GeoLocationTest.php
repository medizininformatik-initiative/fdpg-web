<?php

namespace ACPT\Tests;

use ACPT\Utils\PHP\GeoLocation;

class GeoLocationTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function return_null()
    {
        $address = "dsadasdasdsa";
        $coordinates =  GeoLocation::getCoordinates($address);

        $this->assertNull( $coordinates);
    }

    /**
     * @test
     */
    public function can_fetch_geo_data()
    {
        $address = "Via Latina 94, 00179 Roma";
        $coordinates =  GeoLocation::getCoordinates($address);

        $this->assertArrayHasKey('lat', $coordinates);
        $this->assertArrayHasKey('lng', $coordinates);
    }
}
