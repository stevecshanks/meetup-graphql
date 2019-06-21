.PHONY: run test seed

run:
	docker-compose up

test:
	docker-compose -f docker-compose.yml -f docker-compose.test.yml run unit

seed:
	docker-compose exec graphql php bin/seed.php
