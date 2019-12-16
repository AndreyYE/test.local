<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 12.12.2019
 * Time: 18:17
 */

namespace app\api;

require_once __DIR__.'/Api.php';

class Author extends Api
{
    public $apiName = 'author';
    private $environment;
    private $entity;


    public function __construct($environment,$entity)
    {
        parent::__construct($environment);
        $this->environment = $environment;
        $this->entity = $entity;
    }

     public function indexAction()
     {
         $response = $this->entity->index();
         echo $this->response($response, 200);
     }

    public function viewAction()
    {
        $id = $this->requestUri;
        if(!is_numeric($id[0])){
            throw new \Exception('Passed id must be a number');
        }
        try{
            $response = $this->entity->show($id[0]);
            echo $this->response($response, 200);
        }catch (\Exception $exception)
        {
            echo $this->response($exception->getMessage(), $exception->getCode());
        }
    }
     public function createAction()
     {
         try{
             $response = $this->entity->create();
             echo $this->response($response, 200);
         }catch (\Exception $exception)
         {
             echo $this->response($exception->getMessage(), 500);
         }
     }

     public function updateAction()
     {
         try{
             $response = $this->entity->update();
             echo $this->response($response, 200);
         }catch (\Exception $exception){
             echo $this->response($exception->getMessage(), 500);
         }
     }

     public function deleteAction()
     {
         try{
             $id = $this->requestUri;
             if(!is_numeric($id[0])){
                 throw new \Exception('Passed id must be a number');
             }
             $response = $this->entity->delete($id[0]);
             echo $this->response($response, 200);
         }catch (\Exception $exception){
             echo $this->response($exception->getMessage(), 500);
         }
     }
}