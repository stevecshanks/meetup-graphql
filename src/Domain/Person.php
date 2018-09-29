<?php

namespace MeetupQL\Domain;

final class Person
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string|null */
    private $companyName;

    /**
     * Person constructor.
     * @param int $id
     * @param string $name
     * @param null|string $companyName
     */
    public function __construct(int $id, string $name, ?string $companyName = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->companyName = $companyName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
}
