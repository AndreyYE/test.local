<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 16.12.2019
 * Time: 10:04
 */

namespace app\api\Auth;

use app\api\Api;

require_once __DIR__.'/../Api.php';

class Login extends Api
{
    public $apiName = 'login';
    private $environment;
    private $entity;

    public function __construct($environment,$entity)
    {
        parent::__construct($environment);
        $this->environment = $environment;
        $this->entity = $entity;
    }

    protected function indexAction()
    {
        echo "Send request method POST";
    }

    protected function viewAction()
    {
        echo "Send request method POST";
    }

    protected function createAction()
    {
        try{
            echo $this->response($this->entity->create(), 200);
        }catch (\Exception $exception){
            echo $this->response($exception->getMessage(), 500);
        }

    }

    protected function updateAction()
    {
        echo "Send request method POST";
    }

    protected function deleteAction()
    {
        echo "Send request method POST";
    }
}