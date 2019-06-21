<?php

namespace MeetupQL\Database;

use MeetupQL\Domain\Address;
use MeetupQL\Domain\Meetup;
use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\Person;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;

class MongoDbMeetupRepository implements MeetupRepository
{
    private Client $client;
    private Collection $collection;

    /**
     * MongoDbMeetupRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->collection = $this->client->meetupql->meetups;
    }

    public function findAll(): array
    {
        return array_map(
            [$this, 'documentToMeetup'],
            iterator_to_array($this->collection->find())
        );
    }

    public function add(Meetup $meetup): void
    {
        $this->collection->insertOne($this->meetupToArray($meetup));
    }

    public function update(Meetup $meetup): void
    {
        $this->collection->updateOne(['id' => $meetup->getId()], ['$set' => $this->meetupToArray($meetup)]);
    }

    public function findByAttendee(Person $person): array
    {
        $documentIterator = $this->collection->find([
            'attendee_ids' => $person->getId()
        ]);
        return array_map([$this, 'documentToMeetup'], iterator_to_array($documentIterator));
    }

    public function findById(string $id): Meetup
    {
        $document = $this->collection->findOne(['id' => $id]);
        if (!$document) {
            throw new \InvalidArgumentException("Meetup with ID {$id} not found");
        }

        return $this->documentToMeetup($document);
    }

    protected function meetupToArray(Meetup $meetup): array
    {
        return [
            'id' => $meetup->getId(),
            'name' => $meetup->getName(),
            'start' => $meetup->getStart(),
            'location' => $this->addressToArray($meetup->getLocation()),
            'organiser_id' => $meetup->getOrganiserId(),
            'presenter_id' => $meetup->getPresenterId(),
            'attendee_ids' => $meetup->getAttendeeIds(),
        ];
    }

    protected function addressToArray(Address $address)
    {
        return [
            'companyName' => $address->getCompanyName(),
            'address' => $address->getAddress(),
            'city' => $address->getCity(),
            'postcode' => $address->getPostcode(),
        ];
    }

    protected function documentToMeetup(BSONDocument $document): Meetup
    {
        return new Meetup(
            $document['id'],
            $document['name'],
            $this->documentToAddress($document['location']),
            $document['start'],
            $document['organiser_id'],
            $document['presenter_id'],
            $document['attendee_ids']->getArrayCopy()
        );
    }

    protected function documentToAddress(BSONDocument $document)
    {
        return new Address(
            $document['companyName'],
            $document['address'],
            $document['city'],
            $document['postcode']
        );
    }
}
