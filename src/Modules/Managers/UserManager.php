<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\Objects\User\User;

/**
 * Manage the user's
 */
class UserManager extends Manager
{

    /**
     * Get the user by id
     * @param int $id the id of the user that we want
     * @return User|null
     */
    public function getUser(int $id) : ?User
    {

        $user = new User();
        $statement = $this->getMysql()->getPdo()->prepare("SELECT `id`, `username`, `email`, `usertype` FROM `users` WHERE `id` = :ID");
        $statement->execute([
            ':ID' => $id
        ]);

        $row =  $statement->fetch();
        $user->setId($id);
        $user->setUsername($row['username']);
        $user->setEmail($row['email']);
        $user->setUserType($row['usertype']);

        // Select the customer data like the phone number and the country
        $statement = $this->getMysql()->getPdo()->prepare("SELECT * FROM `customer_data` WHERE `customer_id` = :ID");
        $statement->execute([
            ':ID' => $id
        ]);
        $row =  $statement->fetch();
        $user->setPhoneNumber($row['phone_number']);
        $user->setCountry($row['country']);
        $user->setStreet($row['street']);
        $user->setState($row['state']);
        $user->setCity($row['city']);
        $user->setZipcode($row['zip_code']);
        return $user;
    }

    /**
     * Register an user in the system
     * @param array $vars
     * @return array
     */
    public function register(array $vars = []) : array
    {
        if(isset($vars['username']) && isset($vars['email'])){
            $this->getMysql()->getPdo()->beginTransaction();
            try {
                $userName = $vars['username'];
                $email = $vars['email'];
                $password = password_hash($vars['password'],PASSWORD_DEFAULT);
                //Check if the email is in use
                if($this->emailInUse($email)){
                    return [
                        "success" => false,
                        "message" => "Email already in use"
                    ];
                }


                $statement = $this->getMysql()->getPdo()->prepare("INSERT INTO `users`(`username`, `email`, `password`) VALUES (:username,:email,:password);");
                $statement->execute([
                    ":username" =>  $userName,
                    ":email" =>  $email,
                    ":password" =>  $password
                ]);

                $userId = $this->getMysql()->getPdo()->lastInsertId();
                $customerData = $this->getMysql()->getPdo()->prepare("INSERT INTO `customer_data`(`customer_id`, `phone_number`, `country`, `state`, `street`, `city`, `zip_code`) 
            VALUES (:ID,:PHONENUMBER,:COUNTRY,:STATE,:STREET,:CITY,:ZIP)");
                $customerData->execute([
                    ":ID" =>  $userId,
                    ":PHONENUMBER" => $vars['phoneNumber'],
                    ":COUNTRY" =>  $vars['country'],
                    ":STATE" => $vars['state'],
                    ":STREET" => $vars['street'],
                    ":CITY" => $vars['city'],
                    ":ZIP" => $vars['zipcode'],
                ]);


            }catch (\Exception $exception){
                $this->getMysql()->getPdo()->rollBack();
                return [
                    "success" => false,
                    "message" => "Incorrect data"
                ];
            }
            $this->getMysql()->getPdo()->commit();
            return [
                "success" => true
            ];
        }else{
            return [
                "success" => false,
                "message" => "Incorrect data"
            ];
        }

    }

    /**
     * Get a user on base of the session data
     * @return User|null
     */
    public function getSessionUser(): ?User
    {
        if(isset($_SESSION["userId"])){
            return $this->getUser($_SESSION["userId"]);
        }

        return null;
    }

    /**
     * Log the user in
     * @param array $vars The data of the login
     * @return array
     */
    public function login(array $vars = []) : array
    {
        if(isset($vars['email']) && isset($vars['password'])){
            $email = $vars['email'];

            if(!$this->emailInUse($email)){
                return [
                    "success" => false,
                    "message" => "The login credentials are incorrect"
                ];
            }

            $login = $this->checkLogin($email,$vars['password']);
            if( $login != null){
                $_SESSION["loggedin"] = true;
                $_SESSION["userId"] = $login;
                //Log the user login
                $customerData = $this->getMysql()->getPdo()->prepare("INSERT INTO `log_user_login`(`user_id`, `ip`) VALUES (:USERID,:IP)");
                $customerData->execute([
                    ":USERID" =>  $login,
                    ":IP" => $this->getIp(),
                ]);

                return [
                    "success" => true,
                    "message" => "Login success full"
                ];
            }

        }
        return [
            "success" => false,
            "message" => "The login credentials are incorrect"
        ];
    }

    /**
     * Check if the email exist in the database
     * @param string $email
     * @return bool
     */
    public function emailInUse(string $email) : bool
    {

        $statement = $this->getMysql()->getPdo()->prepare("SELECT `email` FROM `users` WHERE `email` = :email;");
        $statement->execute([
            ":email" =>  $email
        ]);
        if($statement->rowCount() != 0){
            return true;
        }
        return false;
    }

    /**
     * Check if the login is correct
     * @param $email
     * @param $password
     * @return int|null
     */
    public function checkLogin($email,$password) : ?int
    {

        $statement = $this->getMysql()->getPdo()->prepare("SELECT `id`, `email` , `password` FROM `users` WHERE `email` = :email");
        $statement->execute([
            ":email" =>  $email
        ]);
        if($statement->rowCount() == 1){
            $row = $statement->fetch();
            if(password_verify($password,$row['password'])){
                return $row['id'];
            }

        }
        return null;
    }

    /**
     * Check if the used is authenticated
     * @return bool
     */
    public function is_authenticated() : bool
    {
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            return false;
        }
        return true;
    }

}