<?php

use UrlShortener\Entities\Url;
use UrlShortener\Tests\Functional\UrlTestCase;

class IndexUrlTest extends UrlTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function insertFakeUrl()
    {
        $url = new Url();
        $url->setLong('https://www.google.co.uk');
        $url->setHash('3f1437');

        $this->entityManager->persist($url);
        $this->entityManager->flush();
        return $url;
    }

    public function testValidUrlRedirects()
    {
        $url = $this->insertFakeUrl();

        $this->makeRequest([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/' . $url->getHash(),
        ]);
        $response = $this->app->run(true);

        $this->assertSame($response->getStatusCode(), 302);
        $this->assertSame($response->getHeader('location')[0], $url->getLong());
    }

    public function testUrlThatDoesNotExistReturnsError()
    {
        $this->makeRequest([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/3f1437',
        ]);
        $response = $this->app->run(true);

        $data = [
            'errorStatusCode' => 404,
            'message' => 'This url could not be matched.',
        ];

        $this->assertSame($response->getStatusCode(), 404);
        $this->assertSame((string)$response->getBody(), json_encode($data, JSON_UNESCAPED_SLASHES));
    }
}