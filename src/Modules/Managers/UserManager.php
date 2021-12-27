<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
use PcBuilder\MailUtil;
use PcBuilder\Objects\User\User;

class UserManager extends Manager
{

    public function getUser($id) : ?User
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

    public function register($vars = []) : array
    {
        $userName = $vars['username'];
        $email = $vars['email'];
        $password = password_hash($vars['password'],PASSWORD_DEFAULT);
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

        return [
            "success" => true
        ];
    }

    public function getSessionUser(): ?User
    {
        if($_SESSION["userId"] != null){
            return $this->getUser($_SESSION["userId"]);
        }

        return null;
    }

    public function login($vars = []) : array
    {
        $email = $vars['email'];
//        $password = password_hash($vars['password'],PASSWORD_DEFAULT);

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
            return [
                "success" => true,
                "message" => "Login success full"
            ];
        }

        return [
            "success" => false,
            "message" => "The login credentials are incorrect"
        ];
    }


    public function emailInUse($email) : bool
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


    public function is_authenticated() : bool
    {
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            return false;
        }
        return true;
    }

}