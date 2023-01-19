# CodeIgniter 4 Application Sample

# Introduction

This is a template application using the CodeIgniter 4 framework.

# Installation

The easiest way to run project is using docker and few simple steps:

- ```cd .docker-local```
- (optional) configure your parameters in ```.env``` file
- ```ln -sfn ../.env ./.env```
- ```docker compose up -d --build```

This will start the cli-server on port `80`, and bind it to all network interfaces. You can then visit the site
at `http://localhost`

## API Documentation

We should carefully support our API documentation, each controller action that mapped to the route should be documented.
Use syntax described for following library https://zircote.github.io/swagger-php/ and be ready that your pull request
will not be accepted if something wrong with documentation.

Documentation available at http://localhost/api/documentation

Also, do not forget to update postman_collection.json if you do some changes in API

## Adminer

Adminer available at http://localhost:8080