<?php

use UrlShortener\Entities\Url;
use UrlShortener\Tests\Functional\UrlTestCase;

class CreateUrlTest extends UrlTestCase
{
    public function testCreateValidUrl()
    {
        $this->makeRequest([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/create',
            'QUERY_STRING' =>'url=https://www.google.co.uk/maps/place/Bath/@51.3801748,-2.3995494,13z/data=!3m1!4b1!4m5!3m4!1s0x487178a6743ee12d:0x138b27d0d66d9a09!8m2!3d51.375801!4d-2.3599039'
        ]);
        $response = $this->app->run(true);

        $data = [
            'originalUrl' => 'https://www.google.co.uk/maps/place/Bath/@51.3801748,-2.3995494,13z/data=!3m1!4b1!4m5!3m4!1s0x487178a6743ee12d:0x138b27d0d66d9a09!8m2!3d51.375801!4d-2.3599039',
            'shortUrl' => 'http://localhost:8000/1e640c',
            'hash' => '1e640c'
        ];

        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame((string)$response->getBody(), json_encode($data, JSON_UNESCAPED_SLASHES));

        // checking the entity was created in the database
        $urlEntity = $this->entityManager->getRepository(Url::class)->findOneBy(['hash' => '1e640c']);
        $this->assertSame($urlEntity->getLong(), 'https://www.google.co.uk/maps/place/Bath/@51.3801748,-2.3995494,13z/data=!3m1!4b1!4m5!3m4!1s0x487178a6743ee12d:0x138b27d0d66d9a09!8m2!3d51.375801!4d-2.3599039');
        $this->assertSame($urlEntity->getHash(), '1e640c');
    }

    public function testCreateUrlReturns409IfUrlAlreadyExists()
    {
        $this->makeRequest([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/create',
            'QUERY_STRING' =>'url=https://www.google.co.uk/maps/place/Bath/@51.3801748,-2.3995494,13z/data=!3m1!4b1!4m5!3m4!1s0x487178a6743ee12d:0x138b27d0d66d9a09!8m2!3d51.375801!4d-2.3599039'
        ]);
        // running twice so the first one successfully inputs
        $this->app->run(true);
        $response = $this->app->run(true);

        $data = [
            'errorStatusCode' => 409,
            'message' => 'Request conflict, this long url already exists.',
            'shortUrl' => 'http://localhost:8000/1e640c'
        ];

        $this->assertSame($response->getStatusCode(), 409);
        $this->assertSame((string)$response->getBody(), json_encode($data, JSON_UNESCAPED_SLASHES));

        // checking the entity exists in the database
        $urlEntity = $this->entityManager->getRepository(Url::class)->findOneBy(['hash' => '1e640c']);
        $this->assertSame($urlEntity->getLong(), 'https://www.google.co.uk/maps/place/Bath/@51.3801748,-2.3995494,13z/data=!3m1!4b1!4m5!3m4!1s0x487178a6743ee12d:0x138b27d0d66d9a09!8m2!3d51.375801!4d-2.3599039');
        $this->assertSame($urlEntity->getHash(), '1e640c');
    }

    public function testCreateUrlReturns422IfUrlIsInvalid()
    {
        $this->makeRequest([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/create',
            'QUERY_STRING' =>'url=51.3801748,-29'
        ]);
        $response = $this->app->run(true);

        $data = [
            'errorStatusCode' => 422,
            'message' => 'The url provided is invalid.',
            'originalUrl' => '51.3801748,-29'
        ];

        $this->assertSame($response->getStatusCode(), 422);
        $this->assertSame((string)$response->getBody(), json_encode($data, JSON_UNESCAPED_SLASHES));

        // checking nothing was entered into the database
        $urlEntity = $this->entityManager->getRepository(Url::class)->findOneBy(['hash' => '1e640c']);
        $this->assertNull($urlEntity);
    }
}