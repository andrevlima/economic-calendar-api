Economic Calendar API
==========================

Version 1.0.0

An API endpoint implement to give fast as possible a JSON response with the economic calendar of events.

It uses the investing.com as data source and using a "web crawling" methodology,
revelant data is captured and returned in a more well-structured data model, in this
case it will return a JSON.

There is no guarantees about the availability or estability of this API, changes
can be done in source page that can result in a crash of crawler methodology.

It was concepted to be the most simple as possible to be easy to maintain and use.

## How to use:

Just checkout or download this repository and copy this folder in your favorite PHP server. Probably your url in a PHP server with common default configurations, will be available here:

```
http://localhost/economic-calendar-api
```
And you will see a JSON as response like this:
```
[
  {
    "pair": "NZD",
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
