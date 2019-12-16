<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 13.12.2019
 * Time: 14:13
 */

namespace app\entity;

require_once __DIR__."/Entity.php";

class Author extends Entity
{
    public function create()
    {
        if(!$this->check_auth()){
            throw new \Exception('You need to either register or login');
        }
        if(is_numeric($_POST['name'])){
            throw new \Exception('Имя долно быть только строкой');
        }
        // prepare and bind
        $stmt = $this->connection->prepare("INSERT INTO authors (name) VALUES (?)");
        $name = $_POST['name'];
        $stmt->bind_param("s", $name);

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }
        return 'You have successfully created a new author - '.$name. ' id - '.$stmt ->insert_id;

    }

    public function show($id)
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT authors.id as Author_id, authors.name as Author_name, books.id as Book_id, books.name as Book_name, 
                publishes.id as Publish_id, publishes.name as Publish_name
                FROM authors
                LEFT JOIN author_books
                ON author_books.author_id=authors.id
                LEFT JOIN books
                ON author_books.book_id=books.id
                LEFT JOIN publishes
                ON books.publish_id=publishes.id
                WHERE authors.id = $id
                 ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response, $row);
            }
            return $response;
        } else {
            return 'There is no such author';
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

        if(is_numeric($_PUT['name'])){
            throw new \Exception('Name must be a string only');
        }
        if(!is_numeric($_PUT['id'])){
            throw new \Exception('Author ID must be a number');
        }

        $name = $_PUT['name'];
        $id = $_PUT['id'];
        try{
            $this->connection->begin_transaction();
            //1 отвязать книги от автора
            if(isset($_PUT['untie'])){
                $conn = $this->connection;
                foreach (explode(",",$_PUT['untie']) as $item)
                {
                    $sql3 = "DELETE FROM author_books WHERE author_id = $id and book_id = $item";
                    if(!$conn->query($sql3)){
                        throw new \Exception('Could not untie book from author');
                    }
                }
            }
            //добавить автору книгу
            if(isset($_PUT['add_books'])){
                $stmt = $this->connection->prepare("INSERT INTO author_books (author_id, book_id) VALUES (?,?)");
                foreach (explode(",", $_PUT['add_books']) as $item1)
                {
                    $stmt->bind_param("dd", $id, $item1);

                    if(!$stmt->execute()) {
                        throw new \Exception($stmt->error);
                    }
                }
            }
            // изменить имя автора
            if(isset($_PUT['name'])) {
                $stmt = $this->connection->prepare("UPDATE authors SET name=? WHERE id=?");

                $stmt->bind_param("sd", $name, $id);

                if (!$stmt->execute()) {
                    throw new \Exception($stmt->error);
                }
            }
            $this->connection->commit();
            return 'You have successfully updated the author - '.$name;
        }catch (\Exception $exception){
            $this->connection->rollback();
            throw new \Exception($exception->getMessage());
        }
    }

    public function delete($id)
    {
        if(!$this->check_auth()){
            throw new \Exception('You need to either register or login');
        }
        try{
        $conn = $this->connection;
        $conn->begin_transaction();

        //удаляем все связи из таблицы author_books
        $sql2 = "SELECT book_id FROM author_books WHERE author_id = $id";
        $result = $conn->query($sql2);
        $must_delete_row = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($must_delete_row, $row['book_id']);
            }
        }
        $sql3 = "DELETE FROM author_books WHERE author_id = $id";
        if(!$conn->query($sql3)){
           throw new \Exception('Failed to delete author');
        }

        //удаляем все записи из таблицы books
        foreach ($must_delete_row as $val)
        {
            $sql4 = "SELECT book_id FROM author_books  WHERE book_id = $val";
            $result = $conn->query($sql4);
            if ($result->num_rows > 0) {

            }else{
                $sql5 = "DELETE FROM books WHERE id = $val";
                if(!$conn->query($sql5)){
                    throw new \Exception("Failed to delete author's books.");
                }
            }
        }

        //удаляем автора
        $sql = "DELETE FROM authors WHERE id = $id";
        if(!$conn->query($sql)){
            throw new \Exception('Failed to delete author');
        }

        $conn->commit();
        }catch (\Exception $e){
            $conn->rollback();
            return $e->getMessage();
        }

        return "You deleted the author and all his books";
    }

    public function index()
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT authors.id as Author_id, authors.name as Author_name, books.id as Book_id, books.name as Book_name, 
                publishes.id as Publish_id, publishes.name as Publish_name
                FROM authors
                LEFT JOIN author_books
                ON author_books.author_id=authors.id
                LEFT JOIN books
                ON author_books.book_id=books.id
                LEFT JOIN publishes
                ON books.publish_id=publishes.id
                 ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response, $row);
            }
            return $response;
        } else {
            return "We don't have any authors";
        }
    }
}