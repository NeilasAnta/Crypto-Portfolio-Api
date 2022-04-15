## Laravel project Crypto Portfolio Tracker

Documentation to Taken-away-items-info APIs.

-   [How to run project](#how)
-   [Dummy data](#dummy)
-   [API methods](#api)
    -   [Asset CRUD](#crud)
        -   [Create Asset](#create)
        -   [Update Asset](#update)
        -   [Delete Asset](#delete)
        -   [Get All Assets](#get-all)
        -   [Get Asset By ID](#get-by-id)
        -   [Get Asset By User ID](#get-by-user-id)
    -   [Market](#market)
        -   [Get total assets values for user](#get-all-value)
        -   [Get single currency assets values for user](#get-single-currency)
        -   [Get single asset value by ID](#get-single-asset)
-   [HTTP Response Codes](#responses)

## <a name="how"></a>How to run project:

-   Create a database locally
-   Rename `.env.example` file to `.env`inside your project root and fill the database information.
-   Open the console and cd your project root directory
-   Run `composer install`
-   Run `php artisan key:generate`
-   Run `php artisan migrate`
-   Run `php artisan db:seed`
-   Run `php artisan serve`

#### You can now access your project at http://127.0.0.1:8000 :)

### If for some reason your project stop working do these:

-   `composer install`
-   `php artisan migrate`

# <a name="dummy">Dummy data

Run `php artisan db:seed`

Creates static user (`email = crypto@teltonika.lt, password = teltonika`), 3 curencies(`currency_id=1, name=BTC; currency_id=2, name=ETH; currency_id=3, name=I0TA;`)

# <a name="api"></a>API methods

## <a name="crud"></a>Asset CRUD

### <a name="create">Create Asset

```no-highlight
Post http://127.0.0.1:8000/api/asset
```

#### Body parameters

| Name                                        | Type    |
| --------------------------------------------| ------- |
| currency_id                                 | integer |
| label                                       | string  |
| value_before (default will be newest price) | double  |
| amount                                      | double  |
| user_id (default be first user)             | integer |

#### Response

```json
{
    "status": "ok",
    "message": "Asset successfully stored"
}
```

### <a name="update">Update Asset

```no-highlight
Put http://127.0.0.1:8000/api/asset/{id}
```

#### Body parameters

| Name                                        | Type    |
| --------------------------------------------| ------- |
| currency_id                                 | integer |
| label                                       | string  |
| value_before (default will be newest price) | double  |
| amount                                      | double  |


#### Response

```json
{
    "status": "ok",
    "message": "Asset successfully updated"
}
```

### <a name="delete">Delete Asset

```no-highlight
Delete http://127.0.0.1:8000/api/asset/{id}
```
#### Response

```json
{
    "status": "ok",
    "message": "Asset successfully deleted"
}
```

### <a name="get-all">Get All Assets

```no-highlight
Get http://127.0.0.1:8000/api/asset
```
#### Response
```json
[
    {
        "id": 1,
        "currency_id": 1,
        "label": "ByBit",
        "value_before": 5,
        "amount": 69,
        "created_at": "2022-04-13T09:50:46.000000Z",
        "updated_at": "2022-04-15T07:42:46.000000Z",
        "user": null
    },
    {
        "id": 4,
        "currency_id": 1,
        "label": "Binance",
        "value_before": 40225.10352131422,
        "amount": 2,
        "created_at": "2022-04-15T05:49:08.000000Z",
        "updated_at": "2022-04-15T05:49:08.000000Z",
        "user": null
    },
    {
        "id": 5,
        "currency_id": 1,
        "label": "Binance",
        "value_before": 40229.57851879306,
        "amount": 2,
        "created_at": "2022-04-15T05:50:38.000000Z",
        "updated_at": "2022-04-15T05:50:38.000000Z",
        "user": {
            "id": 1,
            "name": "Neilas",
            "email": "neilas.antanavicius@teltonika.lt",
            "created_at": "2022-04-13T09:40:36.000000Z",
            "updated_at": null
        }
    }
]
    
```

### <a name="get-by-id">Get Asset By ID

```no-highlight
Get http://127.0.0.1:8000/api/asset/{id}
```
#### Response
```json
{
    "id": 1,
    "currency_id": 2,
    "label": "Binance",
    "value_before": 3053.7176968603744,
    "amount": 2,
    "created_at": "2022-04-13T09:50:46.000000Z",
    "updated_at": "2022-04-13T09:50:46.000000Z",
    "user": null
}
```

### <a name="get-by-user-id">Get Asset By User ID

```no-highlight
Get http://127.0.0.1:8000/api/asset/user/{id}
```


#### Response

```json
{
        "id": 5,
        "user_id": 1,
        "currency_id": 1,
        "label": "Binance",
        "value_before": 40229.57851879306,
        "amount": 2,
        "created_at": "2022-04-15T05:50:38.000000Z",
        "updated_at": "2022-04-15T05:50:38.000000Z"
    },
    {
        "id": 7,
        "user_id": 1,
        "currency_id": 2,
        "label": "Binance",
        "value_before": 3044.5459639015808,
        "amount": 2,
        "created_at": "2022-04-15T05:54:29.000000Z",
        "updated_at": "2022-04-15T05:54:29.000000Z"
    }
```


## <a name="market">Market

### <a name="get-all-value">Get total assets values for user

```no-highlight
GET http://127.0.0.1:8000/api/total-value/{id}
```

#### Response

```json
{
    "userID": 1,
    "userName": "Neilas",
    "userEmail": "neilas.antanavicius@teltonika.lt",
    "totalValueNowUSD": 420060.67729968415,
    "totalValueBeforeUSD": 334331.8712065851,
    "differenceUSD": 85728.80609309906,
    "differenceProc": 25.6418288162922
}
```

### <a name="get-single-currency">Get single currency assets values for user

```no-highlight
GET http://127.0.0.1:8000/api/single-currency/?currency={BTC/ETH/I0TA}&user_id={id}
```

#### Response

```json
{
    "userID": 1,
    "userName": "Neilas",
    "userEmail": "neilas.antanavicius@teltonika.lt",
    "currency": "BTC",
    "totalValueNowUSD": 321513.44257801934,
    "totalValueBeforeUSD": 241825.22575563463,
    "differenceUSD": 79688.21682238471,
    "differenceProc": 32.952813989269245
}

```

### <a name="get-single-asset">Get single asset value by ID

```no-highlight
GET http://127.0.0.1:8000/api/single-asset/{id}
```

#### Response

```json
{
    "currency": "BTC",
    "valueNowUSD": 2773725.7663917528,
    "valueBeforeUSD": 345,
    "differenceUSD": 2773380.7663917528,
    "differenceProc": 803878.4830121022
}

```



## <a name="responses"></a>HTTP Response Codes

Each response will be returned with one of the following HTTP status codes:

-   `200` `OK` The request was successful
-   `400` `Bad Request` There was a problem with the request (security, malformed, data validation, etc.)
-   `404` `Not found` An attempt was made to access a resource that does not exist in the API
