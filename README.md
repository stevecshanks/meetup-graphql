# meetup-graphql

Learning GraphQL by creating an API for a Meetup.com clone.

## Usage

```shell
# Run API
php -S localhost:8080 graphql.php

# Run frontend
php -S localhost:8081 frontend.php
```

## Example Query

```graphql
query {
  meetups {
    id
    name
    organiser {
      name
      companyName
    }
    presenter {
      name
      companyName
    }
    attendees {
      name
      companyName
    }
  }
}
```
