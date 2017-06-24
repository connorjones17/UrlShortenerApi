# UrlShortenerApi

Url Shortener has an api that you can create your own short urls from as well as using it to redirect to the long urls.

<hr>

<h3>Contents</h3>
1. <a href="#api">Api</a> <br />
3. <a href="#errors">Errors</a>  <br />
2. <a href="#setup">Setup</a>  <br />
3. <a href="#testing">Testing</a>  <br />

<hr>

<h3 id="api">Api</h3>

Make a get request like /create?url=YOUR_LONG_URL to create a short url.

***NB*** *The full url must be specified eg https://www.google.co.uk unfortunately just www.google.co.uk will not suffice.

A successfull create will return JSON like this:

```
{
  "originalUrl": "https://www.google.com",
  "shortUrl": "http://localhost:8000/8ffdef",
  "hash": "8ffdef"
}
```

If you then visit the shortUrl you will be redirected to the long url you originally provided.

Using the above example if you visited http://localhost:8000/8ffdef you would be redirected to https://www.google.com

<hr>

<h3 id="erros">Errors</h3>

If you try and create a short url by giving an invalid Url you will receive a 422 status code and a response similar to:

```
{
  "errorStatusCode": 422,
  "message": "The url provided is invalid.",
  "originalUrl": "some_InvalidURL.co.uk"
}
```

If you try and create a short url by giving a Url that already exists you will receive a 409 status code and a response similar to:

```
{
  "errorStatusCode": 409,
  "message": "Request conflict, this long url already exists.",
  "shortUrl": "http://localhost:8000/8ffdef"
}
```

If you try and access a short url that does not exist you will receive a 404 status code and a response similar to:

```
{
  "errorStatusCode": 404,
  "message": "This url could not be matched."
}
```

<hr>

<h3 id="setup">Setup</h3>

Clone this repository to your local machine and go into the root of the app

```
$ git@github.com:connorjones17/UrlShortenerApi.git
$ cd UrlShortenerApi
```

Install all the dependencies we require

```
$ composer install
```

Go to config/settings.php and update the build properties to match your local machine. 
You might need to update the host and your username and password

Create a database matching these credentials, the database name defaults to url_shortener

Then to build the database schema run:
```
$ composer build
```

To run a server and see everything on the browser a little easier I would advise you run
```
$ php -S localhost:8000 -t public/
```

<hr>

<h3 id="testing">Testing</h3>

Run all tests using
```
$ composer test
````

