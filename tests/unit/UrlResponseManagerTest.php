<?php

use UrlShortener\UrlShortener;
use Slim\Http\Response;
use UrlShortener\UrlResponseManager;

class UrlResponseManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var obj UrlResponseManager
     */
    private $urlResponseManager;

    protected function setUp()
    {
        parent::setUp();
        $_SERVER['HTTP_HOST'] = 'localhost:8000';
        $longUrl = 'https://www.google.co.uk';
        $this->urlResponseManager = new UrlResponseManager(new UrlShortener($longUrl), new Response());
    }

    public function testUrlStatusCodeValidUrl()
    {
        $expected = [
            'originalUrl' => 'https://www.google.co.uk',
            'shortUrl' => 'http://localhost:8000/3f1437',
            'hash' => '3f1437',
        ];

        $actual = $this->urlResponseManager->validUrl();

        $this->assertSame($actual->getStatusCode(), 200);
        $this->assertSame((string)$actual->getBody(), json_encode($expected, JSON_UNESCAPED_SLASHES));
    }

    public function testUrlStatusCodeInvalidUrl()
    {
        $expected = [
            'errorStatusCode' => 422,
            'message' => 'The url provided is invalid.',
            'originalUrl' => 'https://www.google.co.uk',
        ];

        $actual = $this->urlResponseManager->invalidUrl();

        $this->assertSame($actual->getStatusCode(), 422);
        $this->assertSame((string)$actual->getBody(), json_encode($expected, JSON_UNESCAPED_SLASHES));
    }

    public function testUrlStatusCodeHashExists()
    {
        $expected = [
            'errorStatusCode' => 409,
            'message' => 'Request conflict, this long url already exists.',
            'shortUrl' => 'http://localhost:8000/3f1437',
        ];

        $actual = $this->urlResponseManager->hashExists();

        $this->assertSame($actual->getStatusCode(), 409);
        $this->assertSame((string)$actual->getBody(), json_encode($expected, JSON_UNESCAPED_SLASHES));
    }

    public function testUrlStatusCodeHashDoesNotExist()
    {
        $expected = [
            'errorStatusCode' => 404,
            'message' => 'This url could not be matched.',
        ];

        $actual = $this->urlResponseManager->hashDoesNotExist();

        $this->assertSame($actual->getStatusCode(), 404);
        $this->assertSame((string)$actual->getBody(), json_encode($expected, JSON_UNESCAPED_SLASHES));
    }

}
