<?php

namespace PcBuilder\Objects\User;

/**
 * The user is a customer with this we can track who orderd what
 * The database contains the basic data the password will be let out of the object
 */
class User
{
    /**
     * @var int
     */
    private int $id;
    /**
     * @var String
     */
    private String $username;
    /**
     * @var String
     */
    private String $email;
    /**
     * @var String
     */
    private String $phoneNumber;
    /**
     * @var String
     */
    private String $country;
    /**
     * @var String
     */
    private String $street;
    /**
     * @var String
     */
    private String $state;
    /**
     * @var String
     */
    private String $city;
    /**
     * @var String
     */
    private String $zipcode;
    /**
     * @var String
     */
    private String $userType;

    /**
     * Get the id of the user
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the user id
     * Only the database will do this
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the username of the User
     * @return String
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the username of the user
     * @param String $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return String
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param String $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return String
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param String $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return String
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param String $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return String
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param String $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return String
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param String $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return String
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param String $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return String
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * @param String $zipcode
     */
    public function setZipcode(string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @param String $userType
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * @return String
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

}