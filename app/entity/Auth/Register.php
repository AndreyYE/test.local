<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 16.12.2019
 * Time: 9:14
 */
namespace app\entity;

require_once __DIR__."/../Entity.php";

class Register extends Entity
{

    public function create()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception('Email is not correct');
        }
        if(strlen($password)<6){
            throw new \Exception('password must be more than 6 characters');
        }
        $stmt = $this->connection->prepare("INSERT INTO users (email, password, token_access) VALUES (?,?,?)");
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(password_hash($password.$email, PASSWORD_DEFAULT));
        $stmt->bind_param("sss", $email, $password_hash, $token);

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }
        return ['message'=>'You have successfully created an account. 
        Your token_access expires after 60 minutes, after which you will need to send a 
        request using the post method to URL = "/login" indicating your email and 
        password in the request body',
            'token_access'=>$token];
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

}