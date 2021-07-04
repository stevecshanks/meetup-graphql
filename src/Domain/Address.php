<?php

namespace MeetupQL\Domain;

final class Address
{
    private string $companyName;
    private string $address;
    private string $city;
    private string $postcode;

    /**
     * Address constructor.
     * @param string $companyName
     * @param string $address
     * @param string $city
     * @param string $postcode
     */
    public function __construct(string $companyName, string $address, string $city, string $postcode)
    {
        $this->companyName = $companyName;
        $this->address = $address;
        $this->city = $city;
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }
}
