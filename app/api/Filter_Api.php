<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 12.12.2019
 * Time: 19:00
 */

namespace app\api;

use app\api\Auth\Login;
use app\api\Auth\Registration;
use app\entity\Login as loginEntity;
use app\entity\Register;

require_once __DIR__."/Author.php";
require_once __DIR__."/Book.php";
require_once __DIR__."/Publish.php";
require_once __DIR__ ."/Description.php";
require_once __DIR__."/EditAuthorAndBooks.php";
require_once __DIR__."/Auth/Registration.php";
require_once __DIR__."/Auth/Login.php";

require_once __DIR__."/../entity/Book.php";
require_once __DIR__."/../entity/Author.php";
require_once __DIR__."/../entity/Publish.php";
require_once __DIR__."/../entity/Auth/Register.php";
require_once __DIR__."/../entity/Auth/Login.php";


class Filter_Api
{
    private $env;

    /**
     * Filter_Api constructor.
     * @param $env
     */
    public function __construct($env)
    {
        $this->env =$env;
    }

    public function run_api()
    {
        $uri = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
        $count_uri =count($uri);
        if(strripos($count_uri>1 ? $uri[1]: '','ustom?')){
            $desc = new EditAuthorAndBooks($this->env);
            return $desc;
        };
        if(count($uri)==1){
            $desc = new Description($this->env);
            return $desc;
        }else{
            if($uri[1]==='registration'){
                return new Registration($this->env, new Register($this->env));
            }
            if($uri[1]==='login'){
                return new Login($this->env, new loginEntity($this->env));
            }
            if($uri[1]==='author'){
                return new Author($this->env, new \app\entity\Author($this->env));
            }
            if($uri[1]==='author'){
                return new Author($this->env, new \app\entity\Author($this->env));
            }
            elseif ($uri[1]==='book'){
                return new Book($this->env, new \app\entity\Book($this->env));
            }
            elseif ($uri[1]==='publish'){
                return new Publish($this->env, new \app\entity\Publish($this->env));
            }
            else{
                throw new \Exception('We do not have such url');
            }
        }
    }
}