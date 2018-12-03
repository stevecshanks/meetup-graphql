# meetup-graphql

[![Build Status](https://scrutinizer-ci.com/g/stevecshanks/meetup-graphql/badges/build.png?b=master)](https://scrutinizer-ci.com/g/stevecshanks/meetup-graphql/build-status/master)

Learning GraphQL by creating an API for a Meetup.com clone.

## Usage

```shell
docker-compose up -d

# Create some test data
docker-compose exec graphql php seed.php
```

### Frontend

http://localhost:8081/

### GraphQL

http://localhost:8080/

## Tests

```shell
./vendor/bin/phpunit
```
