<?php

namespace PcBuilder\Modules\Managers;

use PcBuilder\Framework\Registery\Manager;
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

        if($this->checkLogin($email,$vars['password'])){
            $_SESSION["loggedin"] = true;
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

    public function checkLogin($email,$password) : bool
    {

        $statement = $this->getMysql()->getPdo()->prepare("SELECT `email` , `password` FROM `users` WHERE `email` = :email");
        $statement->execute([
            ":email" =>  $email
        ]);
        if($statement->rowCount() == 1){
            $row = $statement->fetch();
            if(password_verify($password,$row['password'])){
                return true;
            }

        }
        return false;
    }


    public function is_authenticated() : bool
    {
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            return false;
        }
        return true;
    }

}