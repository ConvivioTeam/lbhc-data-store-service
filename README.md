# LBH & CoL Directory of Services: Data store service

The data sore service stores and retrieves data in the Directory of Services system.

Runs in Docker.

## Documentation

Documentation for the service is in the `/docs` directory.

API documentation is in OpenAPI 3, in the [/docs/api/data-store-service.openapi.yml](./docs/api/data-store-service.openapi.yml) file. Also available on SwaggerHub at {@todo: add URL}

## Run

```
cd source
composer install
cd ..
make up
make shell
cd source
php artisan migrate:refresh
```

## Useful links

* [Adminer](http://adminer.store.lbh.localhost)
