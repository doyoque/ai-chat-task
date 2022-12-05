# Customer Loyality Campaign

Simple give away voucher code campaign for loyal customer.

## Motivation

Eligible customer can participate the campaign by accessing _eligible_ API. If API response with 200 the voucher 
will be locked down for 10 minutes to this customer so the customer can continue to the next phase for uploading 
a selfie image. If image recognition result return is true and submission within 10 minutes since hitting the 
_eligible_ API, allocate the locked voucher to the customer and return the voucher code.
<br>
If the result of image recognition is false or submission exceeds 10 minutes, remove the lock down and this voucher 
will become available to the next customer to grab.


## Pre requisite

- Laravel v9.19
- PHP v8.1.13
- Composer v2.4.4
- MySQL v8.0
- Docker v20.10.21
- docker-compose v2.12.2

## Run Local Manually

Copy `.env.example` to `.env`. <br>
Configure `DB_` value as the same as your installed database. Here for the example.
```bash
DB_CONNECTION=mysql
DB_HOST=0.0.0.0
DB_PORT=3306
DB_DATABASE=aichat
DB_USERNAME=root
DB_PASSWORD=secret123
```

Generate artisan key with `php artisan key:generate`.<br>
Generate data with `php artisan migrate --seed`.<br>
Run locally with `php artisan serve`. It will open `http://localhost:8000`.

## Run Local With Docker (Recommend)

*Note: If you running on M1 chip (ARM) you should change mysql container configuration. For example:
```yaml
mysql:
    platform: linux/arm64/v8 # change to specific platform & base image
    image: mysql:8.0-oracle
    container_name: awhile-mysql
    restart: unless-stopped
    tty: true
```

Simply execute `docker-compose up` or `docker-compose up -d` for detach services from foreground.<br>
List accessible container:
- http://localhost:8081 (adminer)
- http://localhost:8000 (app)

## List Endpoint

- GET `{hostname}/api/eligible?user_id={customer_id}`
- POST `{hostname}/api/photo`

For API collection just import **aichat-campaign.postman_collection.json** to postman desktop client.
