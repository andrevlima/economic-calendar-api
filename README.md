(Forex) Economic Calendar API
==========================
![PHP Composer](https://github.com/andrevlima/economic-calendar-api/workflows/PHP%20Composer/badge.svg)

An API endpoint made in PHP that was implemented to give, as fast as possible, a JSON response with the economic calendar of events, commonly used in Forex.

It uses the investing.com as data source and using a "web crawling" methodology,
revelant data is captured and returned in a more well-structured data model, in this
case it will return a JSON.

There is no guarantees about the availability or estability of this API, changes
can be done in source page that can result in a crash of the web crawler.

It was concepted to be the most simple as possible to be easy to maintain and use.

A demo available is [here](https://forexcalendarjson.000webhostapp.com/) (Can be broken, free server can be deleted)

## Play

After just clone this repository you can copy that just right away to your PHP server.
You can use XAMPP for example for this: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
(Put it in htdocs folder and start PHP server) or any other you prefer.

## How to use:

Just checkout or download this repository and copy this folder to your favorite PHP server. Probably your url in a PHP server with common default configurations, will be available:

```
http://localhost/economic-calendar-api
```
And you will see a JSON as response like this:
```
[
  {
    "economy": "NZD",
    "impact": 1,
    "data": "2018-09-09 22:45:00",
    "name": "Manufacturing Sales Volume (QoQ) (Q2)",
    "actual": "-1.2%",
    "forecast": "",
    "previous": "1.4%"
  },
  ...
]
```
Tip: If you want, you are able to host it in almost free webhosts to make it online and available on the internet. 
https://www.freehosting.com/ or https://www.000webhost.com/ and many others.
