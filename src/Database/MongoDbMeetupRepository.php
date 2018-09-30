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
    /** @var Client */
    private $client;

    /** @var Collection */
    private $collection;

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

    protected function meetupToArray(Meetup $meetup): array
    {
        return [
            'id' => $meetup->getId(),
            'name' => $meetup->getName(),
            'start' => $meetup->getStart(),
            'location' => $this->addressToArray($meetup->getLocation()),
            'organiser' => $this->personToArray($meetup->getOrganiser()),
            'presenter' => $this->personToArray($meetup->getPresenter()),
            'attendees' => array_map([$this, 'personToArray'], $meetup->getAttendees()),
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

    protected function personToArray(Person $person)
    {
        return [
            'id' => $person->getId(),
            'name' => $person->getName(),
            'companyName' => $person->getCompanyName(),
        ];
    }

    protected function documentToMeetup(BSONDocument $document): Meetup
    {
        return new Meetup(
            $document['id'],
            $document['name'],
            $this->documentToAddress($document['location']),
            $document['start'],
            $this->documentToPerson($document['organiser']),
            $this->documentToPerson($document['presenter']),
            array_map([$this, 'documentToPerson'], $document['attendees']->getArrayCopy())
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

    protected function documentToPerson(BSONDocument $document)
    {
        return new Person(
            $document['id'],
            $document['name'],
            $document['companyName']
        );
    }
}
