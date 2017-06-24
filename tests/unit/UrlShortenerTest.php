<?php

use UrlShortener\UrlShortener;

class UrlShortenerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var obj UrlShortener
     */
    private $urlShortener;

    protected function setUp()
    {
        parent::setUp();

        $_SERVER['HTTP_HOST'] = 'localhost:8000';
        $longUrl = 'https://www.google.co.uk/maps/place/Bath/@51.3801748,-2.3995494,13z/data=!3m1!4b1!4m5!3m4!1s0x487178a6743ee12d:0x138b27d0d66d9a09!8m2!3d51.375801!4d-2.3599039';
        $this->urlShortener = new UrlShortener($longUrl);
    }

    public function testUrlShortenerReturnsHash()
    {
        $expected = '1e640c';
        $actual = $this->urlShortener->getHash();

        $this->assertSame($expected, $actual);
    }

    public function testUrlShortenerReturnsShortUrl()
    {
        $expected = 'http://localhost:8000/1e640c';
        $actual = $this->urlShortener->getShortUrl();

        $this->assertSame($expected, $actual);
    }
    public function testUrlShortenerReturnsLongUrl()
    {
        $expected = 'https://www.google.co.uk/maps/place/Bath/@51.3801748,-2.3995494,13z/data=!3m1!4b1!4m5!3m4!1s0x487178a6743ee12d:0x138b27d0d66d9a09!8m2!3d51.375801!4d-2.3599039';
        $actual = $this->urlShortener->getLongUrl();

        $this->assertSame($expected, $actual);
    }

}
