<?php

namespace MeetupQL\Database;

use MeetupQL\Domain\Person;
use MeetupQL\Domain\PersonRepository;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;

class MongoDbPersonRepository implements PersonRepository
{
    /** @var Client */
    private $client;

    /** @var Collection */
    private $collection;

    /**
     * MongoDbPersonRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->collection = $this->client->meetupql->people;
    }

    public function findAll(): array
    {
        return array_map(
            [$this, 'documentToPerson'],
            iterator_to_array($this->collection->find())
        );
    }

    public function add(Person $person): void
    {
        $this->collection->insertOne($this->personToArray($person));
    }

    public function findById(string $id): Person
    {
        $document = $this->collection->findOne(['id' => $id]);
        if (!$document) {
            throw new \InvalidArgumentException("Person with ID {$id} not found");
        }

        return $this->documentToPerson($document);
    }

    public function findByInterest(string $interest): array
    {
        $documentIterator = $this->collection->find([
            'interests' => $interest
        ]);
        return array_map([$this, 'documentToPerson'], iterator_to_array($documentIterator));
    }

    protected function personToArray(Person $person)
    {
        return [
            'id' => $person->getId(),
            'name' => $person->getName(),
            'companyName' => $person->getCompanyName(),
            'interests' => $person->getInterests(),
        ];
    }

    protected function documentToPerson(BSONDocument $document)
    {
        return new Person(
            $document['id'],
            $document['name'],
            $document['companyName'],
            $document['interests']->getArrayCopy()
        );
    }
}
