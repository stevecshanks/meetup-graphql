# meetup-graphql

Learning GraphQL by creating an API for a Meetup.com clone.

## Usage

```shell
docker-compose up
```

## Frontend

http://localhost:8081/

## GraphQL

http://localhost:8080/

### Example Query

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
