<?php

namespace UrlShortener\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use UrlShortener\Entities\Url;
use Doctrine\ORM\EntityManager;
use UrlShortener\UrlShortener;
use UrlShortener\UrlResponseManager;

class UrlController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UrlController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Redirects to the long url
     * @param Request $request
     * @param Response $response
     * @param $args passed through from the route
     * @return Response
     */
    public function index(Request $request, Response $response, $args)
    {
        $hash = $args['hash'];

        $urlEntity = $this->entityManager->getRepository(Url::class)->findOneBy(['hash' => $hash]);

        if ($urlEntity === null) {
            return (new UrlResponseManager(new UrlShortener(''), $response))->hashDoesNotExist();
        }

        return $response->withRedirect($urlEntity->getLong());
    }

    /**
     * Creates a short url when a long url is provided
     * @param Request $request
     * @param Response $response
     * @return static
     */
    public function create(Request $request, Response $response)
    {
        $longUrl = $request->getParam('url');

        $urlShortener = new UrlShortener($longUrl);
        $urlResponseManager = new UrlResponseManager($urlShortener, $response);

        if (!$urlShortener->isValid()) {
            return $urlResponseManager->invalidUrl();
        }

        if ($this->entityManager->getRepository(Url::class)->findOneBy(['hash' => $urlShortener->getHash()]) !== null) {
            return $urlResponseManager->hashExists();
        }

        $url = new Url();
        $url->setLong($urlShortener->getLongUrl());
        $url->setHash($urlShortener->getHash());

        $this->entityManager->persist($url);
        $this->entityManager->flush();

        return $urlResponseManager->validUrl();
    }
}
