<?php

namespace MeetupQL\Tests;

abstract class Collection
{
    /** @var array */
    private $collection;

    /**
     * MemoryRepository constructor.
     */
    public function __construct()
    {
        $this->collection = [];
    }

    protected function addToCollection($object): void
    {
        $this->collection[] = $object;
    }

    protected function allItemsInCollection(): array
    {
        return $this->collection;
    }

    protected function itemWithId($id)
    {
        $matches = $this->filterCollection(
            function ($item) use ($id) {
                return $item->getId() === $id;
            }
        );

        if (empty($matches)) {
            throw new \InvalidArgumentException("No item found with ID {$id}");
        }

        return $matches[0];
    }

    protected function filterCollection(callable $filter): array
    {
        return array_filter($this->collection, $filter);
    }
}
