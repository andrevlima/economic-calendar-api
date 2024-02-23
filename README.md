(Forex) Economic Calendar API
==========================
[![PHP Composer](https://github.com/andrevlima/economic-calendar-api/actions/workflows/php.yml/badge.svg?branch=master)](https://github.com/andrevlima/economic-calendar-api/actions/workflows/php.yml)

This project consists of a PHP-based API endpoint designed to rapidly deliver the current economic calendar of events in JSON format, often utilized within the Forex market. 

It sources its data from investing.com through web crawling techniques, extracting relevant information and presenting it in a well-structured data model, specifically in JSON format.
It is important to note that there are no guarantees regarding its availability or stability. Potential changes on the source page (investing.com) could disrupt the web crawler's functionality, leading to possible outages or errors.

The API has been intentionally developed with simplicity in mind, both in terms of maintenance and usability, ensuring that it remains straightforward to integrate into various applications.

![Static Badge](https://img.shields.io/badge/composer-php-blue?logo=php)

## Play / Installation 
Just upload this repo files on your favourite PHP Server.

You can use XAMPP for example: [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
(Put files in htdocs folder and start PHP server).

## How to use:

Super simple, probably your url in a PHP server with common default configurations will be available:

```
http://localhost/economic-calendar-api
```
And you will see a JSON as response like this:
```json
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

## Demo
A demo available is [here](https://andrevlimawebh.000webhostapp.com/) (Can be broken, free server can be deleted)

Tip: If you want, you are able to host it in almost free webhosts to make it online and available on the internet. 
https://www.freehosting.com/ or https://www.000webhost.com/ and many others.
