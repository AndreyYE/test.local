<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 16.12.2019
 * Time: 10:06
 */

namespace app\entity;

require_once __DIR__."/../Entity.php";

class Login extends Entity
{

    public function create()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_hash='';

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception('Email is not correct');
        }
        if(strlen($password)<6){
            throw new \Exception('Password must be more than 6 characters');
        }
        $sql = "select password from users where email='$email'";
        $result = $this->connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $password_hash=$row['password'];
            }
        } else {
            throw new \Exception('Email is not correct');
        }
        if(!password_verify($password, $password_hash)){
            throw new \Exception('Your password is not correct');
        }
        $token = md5(password_hash($password.$email, PASSWORD_DEFAULT));
        $sql = "UPDATE users SET token_access='$token' WHERE email='$email'";

        if (!$this->connection->query($sql) === TRUE) {
            throw new \Exception('Error Login, try return later');
        }

        return ['message'=>'You have successfully login. 
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