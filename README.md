# meetup-graphql

Learning GraphQL by creating an API for a Meetup.com clone.

## Usage

```shell
php -S localhost:8080 graphql.php
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
