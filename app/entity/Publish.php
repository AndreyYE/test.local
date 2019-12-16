<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 13.12.2019
 * Time: 11:07
 */

namespace app\entity;

require_once __DIR__."/Entity.php";

class Publish extends Entity
{
    protected $environment;

    public function __construct($environment)
    {
        parent::__construct($environment);

        $this->environment = $environment;
    }

    public function create()
    {
        if(!$this->check_auth()){
            throw new \Exception('You need to either register or login');
        }
        if(is_numeric($_POST['name'])){
            throw new \Exception('Имя долно быть только строкой');
        }
        // prepare and bind
        $stmt = $this->connection->prepare("INSERT INTO publishes (name) VALUES (?)");
        $name = $_POST['name'];
        $stmt->bind_param("s", $name);
        $stmt->execute();
        if(!$stmt) {
            throw new \Exception($stmt->error);
        }
        return 'You have successfully created a publisher - '.$name. ' id - '.$stmt ->insert_id;

    }

    public function show($id)
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT id, name FROM publishes WHERE id = $id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response, $row);
            }
            return $response;
        } else {
            return 'There is no such publisher';
        }
    }

    public function update()
    {
        if(!$this->check_auth()){
            throw new \Exception('You need to either register or login');
        }
        $method = $_SERVER['REQUEST_METHOD'];
        if ('PUT' === $method) {
            parse_str(file_get_contents('php://input'), $_PUT);
        }

        if(!isset($_PUT['name']) and is_numeric($_PUT['name'])){
            throw new \Exception('Name must be a string only');
        }
        if(!isset($_PUT['id']) and !is_numeric($_PUT['id'])){
            throw new \Exception('ID should only be a string');
        }

        $name = $_PUT['name'];
        $id = $_PUT['id'];

        // изменить имя издательства
            $stmt = $this->connection->prepare("UPDATE publishes SET name=? WHERE id=?");

            $stmt->bind_param("sd", $name, $id);
            $stmt->execute();
            if (!$stmt) {
                throw new \Exception($stmt->error);
            }
        return 'You have successfully updated the publisher. - '.$name;
    }

    public function delete($id)
    {
        if(!$this->check_auth()){
            throw new \Exception('You need to either register or login');
        }
        try{
            $conn = $this->connection;
            $conn->begin_transaction();

            //удаляем связь автор-издательство из таблицы author_publishes
            $sql = "DELETE FROM author_publishes WHERE publish_id = $id";
            if(!$conn->query($sql)){
                throw new \Exception('Failed to delete author-publisher relationship');
            }

            //обновляем строку publish_id в таблице books
            $sql = "UPDATE books SET publish_id=NULL WHERE publish_id=$id";
            $res=$conn->query($sql);
            if (!$res) {
                throw new \Exception($conn->error);
            }

            //удаляем издательство
            $sql = "DELETE FROM publishes WHERE id=$id";
            if(!$conn->query($sql)){
                throw new \Exception($conn->error);
            }

            $conn->commit();
        }catch (\Exception $e){
            $conn->rollback();
            return $e->getMessage();
        }
        return "You have removed the publisher";
    }

    public function index()
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT id, name, reg_date FROM publishes";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response, $row);
            }
            return $response;
        } else {
            return "We don't have any publishers";
        }
    }

}