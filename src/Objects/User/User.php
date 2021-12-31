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
     * Get the email of the user
     * @return String
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email of the user
     * @param String $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the phonenumber of the user
     * @return String
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Set the phone number of the user
     * @param String $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Get the country of the user
     * @return String
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Set the country of the user
     * @param String $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * Get the street of the user
     * @return String
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Set the street of the user
     * @param String $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * Get the state/provice of the user
     * @return String
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Set the state/provice of the user
     * @param String $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * Get the city of the user
     * @return String
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set the city of the user
     * @param String $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Get the zip code of the user
     * @return String
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * Set the zip code of the user
     * @param String $zipcode
     */
    public function setZipcode(string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    /**
     * Get the user type of the user
     * @param String $userType Type's are Customer , Employee , Manager
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * Get the type of user
     * @return String Type's are Customer , Employee , Manager
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

}