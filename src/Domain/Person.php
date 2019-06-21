<?php

namespace MeetupQL\Domain;

final class Person
{
    private string $id;
    private string $name;
    private ?string $companyName;

    /** @var string[] */
    private array $interests;

    /**
     * Person constructor.
     * @param string $id
     * @param string $name
     * @param null|string $companyName
     * @param string[] $interests
     */
    public function __construct(string $id, string $name, ?string $companyName = null, array $interests)
    {
        $this->id = $id;
        $this->name = $name;
        $this->companyName = $companyName;
        $this->interests = $interests;
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

    /**
     * @return string[]
     */
    public function getInterests(): array
    {
        return $this->interests;
    }
}
