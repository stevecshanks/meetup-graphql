<?php

namespace MeetupQL\Domain;

final class Person
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string|null */
    private $companyName;

    /**
     * Person constructor.
     * @param string $id
     * @param string $name
     * @param null|string $companyName
     */
    public function __construct(string $id, string $name, ?string $companyName = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getId(): string
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
