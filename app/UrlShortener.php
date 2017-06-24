<?php

namespace UrlShortener;

class UrlShortener
{

    /**
     * @var string
     */
    protected $longUrl;

    /**
     * UrlShortener constructor.
     * @param $longUrl
     */
    public function __construct($longUrl)
    {
        $this->longUrl = (string) $longUrl;
    }

    /**
     * Returns a 6 character hash code based on the long Url.
     * The hash will remain consistent based on the long url
     * @return string
     */
    public function getHash()
    {
        return substr(md5($this->longUrl), 0, 6);
    }

    /**
     * returns url with short url appended
     * @return string
     */
    public function getShortUrl()
    {
        $http = isset($_SERVER['HTTPS']) ? "https://" : "http://";
        return $http . $_SERVER['HTTP_HOST'] . '/' . $this->getHash();
    }

    /**
     * returns long url
     * @return string
     */
    public function getLongUrl()
    {
        return $this->longUrl;
    }

    /**
     * validates the long url and forces a boolean to be returned
     * @return bool
     */
    public function isValid()
    {
        return !!filter_var($this->longUrl, FILTER_VALIDATE_URL);
    }

}
