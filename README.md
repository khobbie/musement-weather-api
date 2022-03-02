# Kwabena Ampah | JAGAAD Assignment Musement API
This is an API design test for JAGAAD. Code is build with PHP 8.0 and powered by Symfony 6.
## Running locally:
To run this locally, you just need Composer & Docker installed. 
- clone repo from git and cd into project directory
-  install dependencies with "composer install" and run "docker-compose up -d" to spin up a container (docker should be running)
- Access Container CLI via Docker app or by running "docker-exec -it"
- Start up local server by running "symfony server:start -d"
```sh
$ git clone [url]
$ cd /jagaad-musement-api
$ composer install
$ docker-compose up -d"
$ docker-exec -it
$ symfony server:start -d
```
## Step 1 | Development
The objective here was to build a service that gets the list of the cities from Musement's API
for each city gets the forecast for the next 2 days using http://api.weatherapi.com
```sh
php bin/console app:get-cities-forecast
```
Sample Output:
```sh
Processed city Amsterdam | Partly cloudy - Sunny
*******************************************************************
Processed city Paris | Overcast - Overcast
*******************************************************************
Processed city Rome | Sunny - Partly cloudy
*******************************************************************
Processed city Milan | Sunny - Sunny
*******************************************************************
Processed city Barcelona | Patchy rain possible - Patchy rain possible
*******************************************************************
Processed city Nice | Sunny - Patchy rain possible
*******************************************************************
Processed city Dubai | Sunny - Sunny
*******************************************************************
Processed city New York | Patchy rain possible - Moderate rain
*******************************************************************
```


## Step 2 | API design
In designing the endpoint, my objective was to keep the number of enpoints as minimal as possible to development and maintenance fairly easy.

Assumption: Only Forecasts for 7 days are available (includes current day)

### Set the forecast for a specific city and specific day

#


> <b>Method :</b> PUT <br>
> <b>Endpoint:</b> api/v3/cities/{ciy_id}/forecasts/{day} <br>
> <b>Path variable: </b> city_id & day ( 1=> today, 2 => tomorrow, 3 =>tomorrow next)  <br>
> <b> Request Body: </b>
```json
    {
        "condition" : "Overcast"
    }
```
> <b> Response Body: </b>

```json
    {
        "message" : " ${city_name} weather condition has been set successfully for day ${day}"
    }

```
> <b> Response Status Codes: </b>

```json
     [
          {
              "statusCode": 204,
              "description": "Returned when successful"
          },
          {
              "statusCode": 404,
              "description": "No data found for city with id"
          },
          {
              "statusCode": 500,
              "description": "Something went wrong on the server"
          },
          {
              "statusCode": 422,
              "description": "Condition for city can not be empty"
          }
     ]

```

### Read the forecast for a specific city and day

#

> <b>Method :</b> GET<br>
> <b>Endpoint:</b> api/v3/cities/{ciy_id}/forecasts/{day} <br>
> <b>Path variable: </b> city_id & day ( 1=> today, 2 => tomorrow, 3 =>tomorrow next)  <br>
> <b> Response Body: </b>

```json
    { 
        "location": {
            "name": "Kos",
            "region": "South Aegean",
            "country": "Greece",
            "lat": 36.89,
            "lon": 27.29,
            "tz_id": "Europe/Athens",
            "localtime_epoch": 1646142269,
            "localtime": "2022-03-01 15:44"
        },
        "forecastday": [
            {
                "date": "2022-03-01",
                "condition": {
                    "text": "Patchy rain possible",
                    "icon": "//cdn.weatherapi.com/weather/64x64/day/176.png",
                    "code": 1063
                }
            }
        ]
    
    }

```

### Read the forecast for a specific city and number of days

#

> <b>Method :</b> GET<br>
> <b>Endpoint:</b> api/v3/cities/{ciy_id}/forecasts?days={number_of_days} <br>
> <b>Path variable: </b> city_id & number_of_days ( integer )  <br>
> <b> Response Body: </b>

```json
    { 
        "location": {
            "name": "Kos",
            "region": "South Aegean",
            "country": "Greece",
            "lat": 36.89,
            "lon": 27.29,
            "tz_id": "Europe/Athens",
            "localtime_epoch": 1646142269,
            "localtime": "2022-03-01 15:44"
        },
        "forecastday": [
            {
                "date": "2022-03-01",
                "condition": {
                    "text": "Patchy rain possible",
                    "icon": "//cdn.weatherapi.com/weather/64x64/day/176.png",
                    "code": 1063
                }
            },
            {
                "date": "2022-03-02",
                "condition": {
                    "text": "heavy rain",
                    "icon": "//cdn.weatherapi.com/weather/64x64/day/188.png",
                    "code": 1063
                }
            },
            {
                "date": "2022-03-03",
                "condition": {
                    "text": "Overcast",
                    "icon": "//cdn.weatherapi.com/weather/64x64/day/55.png",
                    "code": 1063
                }
            }            
        ]
    
    }

```

> <b> Response Status Codes: </b>

```json
     [
          {
              "statusCode": 200,
              "description": "Returned when successful"
          },
          {
              "statusCode": 404,
              "description": "No data found for city with id"
          },
          {
              "statusCode": 500,
              "description": "Something went wrong on the server"
          }
     ]

```

### Questions answered

- Weather in Paris today (Friday) : api/v3/cities/{100}/forecasts/1 or api/v3/cities/{100}/forecasts?day=1
- Weather in Paris tomorrow (Saturday) : api/v3/cities/{100}/forecasts/2 or api/v3/cities/{100}/forecasts?day=2
- Weather in Amsterdam for number of days: api/v3/cities/{57}/forecasts?day=3

## Running Tests
Tests wriiten with help of php-unit and can be run with the following command.
```sh
$ php bin/phpunit -v
```
