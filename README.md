Economic Calendar API
==========================

An API endpoint made in PHP that was implemented to give, as fast as possible, a JSON response with the economic calendar of events, commonly used in Forex.

It uses the investing.com as data source and using a "web crawling" methodology,
revelant data is captured and returned in a more well-structured data model, in this
case it will return a JSON.

There is no guarantees about the availability or estability of this API, changes
can be done in source page that can result in a crash of crawler methodology.

It was concepted to be the most simple as possible to be easy to maintain and use.

## How to use:

Just checkout or download this repository and copy this folder to your favorite PHP server. Probably your url in a PHP server with common default configurations, will be available here:

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
Tip: If you want, you are able to host it in almost of free hosts to make it online and available on the internet. 
https://www.freehosting.com/ or https://www.000webhost.com/ and many others.
