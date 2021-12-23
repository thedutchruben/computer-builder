<?php

namespace PcBuilder\Objects\User;

/**
 * The user is a customer with this we can track who orderd what
 * The database contains the basic data the password will be let out of the object
 */
class User
{
    private int $id;
    private String $username;
    private String $email;

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


}