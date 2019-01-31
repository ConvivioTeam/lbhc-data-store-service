# LBH & CoL Directory of Services: Data store service

The data sore service stores and retrieves data in the Directory of Services system.

Runs in Docker.

## Documentation

Documentation for the service is in the `/docs` directory.

API documentation is in OpenAPI 3, in the [/docs/api/data-store-service.openapi.yml](./docs/api/data-store-service.openapi.yml) file. Also available on SwaggerHub at {@todo: add URL}

## Setup

All database commands are run through `artisan` this can be called directly from the docker image.

### Development Setup

Copy `.env.example` to `.env` and edit as needed.

Navigate to the `source` directory and Cpy `.env.example` to `.env` and edit as needed.

Navigate to the `root` directory and run `make up`

Run `make shell` and `cd source`

Run `composer install`

All the commands you need for development can be found by running `php artisan`

#### Event sourcing

To connect to the external Kafka event stream microservice, configure the appropriate environment variables in your root `.env` file or other environment settings.

To run the queue listener:

```bash
$ make shell
$ cd ./source
$ php artisan queue:work kafka
```

Cf. [https://laravel.com/docs/5.7/queues#running-the-queue-worker](https://laravel.com/docs/5.7/queues#running-the-queue-worker)

##### In production

To keep the queue:work process running permanently in the background, you should use a process monitor such as Supervisor to ensure that the queue worker does not stop running.

Cf. [https://laravel.com/docs/5.7/queues#supervisor-configuration](https://laravel.com/docs/5.7/queues#supervisor-configuration)

### Production Setup

Any changes made to the `master` branch are built into a docker image and tagged as `latest`.

There are then uploaded to Amazon ECR. See the [`.drone.yml`](https://github.com/LBHackney-IT/DoS-data-store-service/blob/master/.drone.yml) file for more details.

## Useful links

* [Adminer](http://adminer.store.lbh.localhost)
