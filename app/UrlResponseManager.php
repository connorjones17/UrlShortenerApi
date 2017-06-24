<?php

namespace UrlShortener;

use Slim\Http\Response;

class UrlResponseManager
{

    /**
     * @var UrlShortener
     */
    protected $urlShortener;

    /**
     * @var Response
     */
    protected $response;

    /**
     * UrlResponseManager constructor.
     * @param UrlShortener $urlShortener
     * @param Response $response
     */
    public function __construct(UrlShortener $urlShortener, Response $response)
    {
        $this->urlShortener = $urlShortener;
        $this->response = $response;
    }

    /**
     * Returns a 200 status code and json data
     * @return Response
     */
    public function validUrl()
    {
        $responseData = [
            'originalUrl' => $this->urlShortener->getLongUrl(),
            'shortUrl' => $this->urlShortener->getShortUrl(),
            'hash' => $this->urlShortener->getHash(),
        ];
        return $this->response->withJson($responseData, 200, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Returns a 422 status code and json data
     * @return Response
     */
    public function invalidUrl()
    {
        $responseData = [
            'errorStatusCode' => 422,
            'message' => 'The url provided is invalid.',
            'originalUrl' => $this->urlShortener->getLongUrl(),
        ];

        return $this->response->withJson($responseData, 422, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Returns a 409 status code and json data
     * @return Response
     */
    public function hashExists()
    {
        $responseData = [
            'errorStatusCode' => 409,
            'message' => 'Request conflict, this long url already exists.',
            'shortUrl' => $this->urlShortener->getShortUrl(),
        ];

        return $this->response->withJson($responseData, 409, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Returns a 409 status code and json data
     * @return Response
     */
    public function hashDoesNotExist()
    {
        $responseData = [
            'errorStatusCode' => 404,
            'message' => 'This url could not be matched.',
        ];

        return $this->response->withJson($responseData, 404, JSON_UNESCAPED_SLASHES);
    }

}
