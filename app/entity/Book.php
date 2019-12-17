<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 13.12.2019
 * Time: 14:13
 */

namespace app\entity;

require_once __DIR__."/Entity.php";

class Book extends Entity
{
    public function create()
    {
        if(!$this->check_auth()){
            throw new \Exception('You need to either register or login');
        }
        $publish_id=$_POST['publish_id'];
        $name = $_POST['name'];
        $authors_id =  $_POST['authors_id'];
        if(isset($name) and is_numeric($name)){
            throw new \Exception('Name must be a string only');
        }
        if(isset($publish_id) and !is_numeric($publish_id)){
            throw new \Exception('publisher_id should only be a number');
        }
        if(!isset($authors_id)){
            throw new \Exception('authors_id should only be a number');
        }
        // prepare and bind
        $prepear = "INSERT INTO books (name, publish_id) VALUES (?,?)";
        $stmt = $this->connection->prepare($prepear);

        $stmt->bind_param("sd", $name,$publish_id);


        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }
        $id_book=$stmt->insert_id;
        foreach (explode(",",$authors_id) as $author_id){
            $stmt1 = $this->connection->prepare("INSERT INTO author_books (author_id, book_id) VALUES (?,?)");

            $stmt1->bind_param("dd",$author_id,$id_book);
            $stmt1->execute();
            if(!$stmt1) {
                throw new \Exception($stmt1->error);
            }
        }
        return 'You have successfully created a new book - '.$name. ' id - '.$stmt->insert_id;

    }

    public function show($id)
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT books.id as Book_id, books.name as Book_name, authors.id as Author_id, authors.name as Author_name,
                publishes.id as Publish_id, publishes.name as Publish_name
                FROM books
                LEFT JOIN author_books
                ON author_books.book_id=books.id
                LEFT JOIN authors
                ON author_books.author_id=authors.id             
                LEFT JOIN publishes
                ON books.publish_id=publishes.id
                WHERE books.id=$id
                 ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response, $row);
            }
            return $response;
        } else {
            return 'There is no such book';
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
            throw new \Exception('Book ID must be a number');
        }

        $name = $_PUT['name'];
        $id = $_PUT['id'];
        $publish_id ='';
        if(isset($_PUT['publish_id'])){
            if(!is_numeric($_PUT['publish_id'])){
                throw new \Exception('Publisher ID must be a number');
            } else{
                $publish_id = $_PUT['publish_id'];
            }
        }
        try{
            $this->connection->begin_transaction();
        //1 отвязать книги от автора
        if(isset($_PUT['untie'])){
            $conn = $this->connection;
            foreach (explode(",",$_PUT['untie']) as $item)
            {
                //проверяем сколько книг автора в издательстве, если только одна книга, удаляем связь автора ($item) и издательства
                $sql6 = "SELECT COUNT(*) as quantity
                FROM books
                INNER JOIN author_books
                ON author_books.book_id=books.id
                INNER JOIN authors
                ON author_books.author_id=authors.id
                WHERE authors.id=$item and publish_id=(SELECT publish_id FROM books WHERE id=$id)
                 ";
                $result = $conn->query($sql6);
                if(!$result){
                    throw new \Exception('Failed to untie the author from the publication');
                }
                if($result->fetch_assoc()['quantity']<=1){
                    $sql3 = "DELETE FROM author_publishes WHERE author_id = $item and publish_id = (SELECT publish_id FROM books WHERE id=$id)";
                    if(!$conn->query($sql3)){
                        throw new \Exception('Failed to untie the author from the publication');
                    }
                }

                // удаляем связь книги ($id) с авторами ($item)
                $sql3 = "DELETE FROM author_books WHERE book_id = $id and author_id = $item";
                if(!$conn->query($sql3)){
                    throw new \Exception('Could not untie book from author');
                }
            }
        }

        //добавить книге автора
        if(isset($_PUT['add_authors'])){
            $stmt = $this->connection->prepare("INSERT INTO author_books (book_id, author_id) VALUES (?,?)");
            foreach (explode(",", $_PUT['add_authors']) as $item1)
            {
                $stmt->bind_param("dd", $id, $item1);

                if(!$stmt->execute()) {
                    throw new \Exception($stmt->error);
                }
            }
        }

        // изменить имя автора
        if(isset($_PUT['name'])) {
            $stmt = $this->connection->prepare("UPDATE books SET name=? WHERE id=?");

            $stmt->bind_param("sd", $name, $id);

            if (!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }
        }

        //поменять издательство
        if($publish_id){
            $conn=$this->connection;
            //проверяем есть ли сзять автор с книгой
            $sql7 = "SELECT author_books.author_id as author_id, books.publish_id as publish_id
                FROM author_books
                INNER JOIN books
                ON author_books.book_id=books.id
                WHERE book_id=$id
                 ";
            $result7 = $conn->query($sql7);
            if ($result7->num_rows > 0) {
                while($row = $result7->fetch_assoc()) {
                    $id_author = $row['author_id'];
                    $id_publish = $row['publish_id'];

                    // если у автора только одна книга удаляем связь автор - издальство
                    if($id_publish){
                    $sql8 = "SELECT COUNT(*) as quantity
                FROM books
                INNER JOIN author_books
                ON author_books.book_id=books.id
                INNER JOIN authors
                ON author_books.author_id=authors.id
                WHERE authors.id=$id_author and publish_id=$id_publish
                 ";
                    $result = $conn->query($sql8);
                    var_dump('author' . $id_author);
                    var_dump('publish' . $id_publish);
                    if ($result->fetch_assoc()['quantity'] <= 1) {
                        $sql3 = "DELETE FROM author_publishes WHERE author_id = $id_author and publish_id = (SELECT publish_id FROM books WHERE id=$id)";
                        if (!$conn->query($sql3)) {
                            throw new \Exception('Failed to untie the author from the publisher');
                        }
                    }
                    // проверяем есть ли связь автор и нового издалельства, если есть ничего не делает, если нет тогда добавляем новую связь
                    $sql8 = "SELECT COUNT(*) as quantity
                            FROM author_publishes
                            WHERE author_id=$id_author and publish_id=$publish_id
                            ";
                    $result = $conn->query($sql8);
                    if ($result->fetch_assoc()['quantity'] < 1) {
                        // добавляем связь автор издательство
                        $stmt = $this->connection->prepare("INSERT INTO author_publishes (author_id, publish_id) VALUES (?,?)");
                        $stmt->bind_param("dd", $id_author, $publish_id);

                        if (!$stmt->execute()) {
                            throw new \Exception($stmt->error);
                        }
                    }
                }
                }
            }
            $stmt = $this->connection->prepare("UPDATE books SET publish_id=? WHERE id=?");

            $stmt->bind_param("dd", $publish_id, $id);

            if (!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }
        }
            $this->connection->commit();
        return 'You have successfully updated the book - '.$name;
        }catch (\Exception $exception)
        {
            $this->connection->rollback();
            throw new \Exception($exception->getMessage());
        }
    }

    public function delete($id)
    {
        if(!$this->check_auth()){
            throw new \Exception('You need to either register or login');
        }
            $conn = $this->connection;
        try{

            $conn->begin_transaction();
            //выбирем удаляемую книгу
            $sql4 = "SELECT publish_id FROM books WHERE id = $id";
            if(!$conn->query($sql4)){
                throw new \Exception("The book does not exist");
            }
            $publish_id = $conn->query($sql4)->fetch_assoc()['publish_id'];

            //удаляем все связи из таблицы author_books
            $sql2 = "SELECT author_id FROM author_books WHERE book_id = $id";
            $result = $conn->query($sql2);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $author_id =$row['author_id'];
                    $sql3 = "SELECT COUNT(*) as quantity FROM books
                            INNER JOIN author_books
                            ON author_books.book_id=books.id
                            INNER JOIN authors
                            ON author_books.author_id=authors.id
                            WHERE authors.id = $author_id and books.publish_id=$publish_id";
                    $res = $conn->query($sql3);
                    if($res->fetch_assoc()['quantity']<=1){
                        $sql = "DELETE FROM author_publishes WHERE author_id=$author_id and publish_id=$publish_id";
                        if(!$conn->query($sql)){
                            throw new \Exception('Failed to unlink author from publisher');
                        }
                    };

                }
            }

            //удаляем все связи из таблицы author_books
            $sql = "DELETE FROM author_books WHERE book_id = $id";
            if(!$res=$conn->query($sql)){
                throw new \Exception('Failed to untie authors from book');
            }

            //удаляем книгу

            $sql = "DELETE FROM books WHERE id = $id";
            if(!$res=$conn->query($sql)){
                throw new \Exception('Failed to delete book.');
            }
            $conn->commit();
            return "You deleted a book ".$id;
        }catch (\Exception $exception){
            $this->connection->rollback();
            throw new \Exception($exception->getMessage());
        }
    }

    public function index()
    {
        $response=[];
        $conn = $this->connection;

        $sql = "SELECT books.id as Book_id, books.name as Book_name, authors.id as Author_id, authors.name as Author_name,
                publishes.id as Publish_id, publishes.name as Publish_name
                FROM books
                LEFT JOIN author_books
                ON author_books.book_id=books.id
                LEFT JOIN authors
                ON author_books.author_id=authors.id             
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
            return 'No books yet';
        }
    }
}