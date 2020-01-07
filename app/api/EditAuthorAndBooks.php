<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 06.01.2020
 * Time: 12:37
 */

namespace app\api;


class EditAuthorAndBooks
{
    private $connection;
    /**
     * Description constructor.
     * @param $env
     */
    public function __construct($env)
    {
        //Подключаемся к базе данных
        $server_name =$env['DB_SERVER_NAME'] ;
        $port = $env['DB_PORT'];
        $username = $env['DB_USER_NAME'];
        $password = $env['DB_PASSWORD'];
        $db_name = $env['DB_NAME'];

        // Создаем соединение
        $conn = new \mysqli($server_name, $username, $password,$db_name,$port);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $this->connection = $conn;
    }
    private function get_all_books_author($id)
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT id, name
                FROM books WHERE id IN (SELECT book_id FROM author_books WHERE author_id=$id)";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response, $row);
            }
            return $response;
        } else {
            return "We don't have any books";
        }
    }
    private function get_all_books_not_author($id)
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT id, name
                FROM books WHERE id NOT IN (SELECT book_id FROM author_books WHERE author_id=$id)";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response, $row);
            }
            return $response;
        } else {
            return "We don't have any books";
        }
    }

    private function get_all_authors($id)
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT id, name
                FROM authors WHERE id IN (SELECT author_id FROM author_books WHERE book_id=$id)";
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
    private function get_all_authors_havent_book($id)
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT id, name
                FROM authors WHERE id NOT IN (SELECT author_id FROM author_books WHERE book_id=$id)";
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
    private function get_all_publish($id)
    {
        $response=['checked'=>[],'all'=>[]];
        $conn = $this->connection;

        $sql = "SELECT id, name
                FROM publishes";
        $result = $conn->query($sql);

        $sql1 = "SELECT id, name
                FROM publishes WHERE id = (SELECT publish_id
                FROM books WHERE id=$id)";
        $result1 = $conn->query($sql1);

        if ($result1->num_rows > 0) {
            while($row = $result1->fetch_assoc()) {
                array_push($response['checked'], $row);
            }
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($response['all'], $row);
            }
            return $response;
        } else {
            return "We don't have any publishes";
        }
    }
    public function run()
    {
        $url = $_SERVER['QUERY_STRING'];
        parse_str($url, $array);
        if(array_key_exists('author_id', $array)){
            echo json_encode($this->get_all_books_author($array['author_id']));
        };
        if(array_key_exists('author_id_not_books', $array)){
            echo json_encode($this->get_all_books_not_author($array['author_id_not_books']));
        };
        if(array_key_exists('book_id', $array)){
            echo json_encode($this->get_all_authors($array['book_id']));
        };
        if(array_key_exists('book_is_not_authors', $array)){
            echo json_encode($this->get_all_authors_havent_book($array['book_is_not_authors']));
        };
        if(array_key_exists('list_publish_to_book', $array)){
            echo json_encode($this->get_all_publish($array['list_publish_to_book']));
        };
    }
    public function __destruct()
    {
        $this->connection->close();
        // TODO: Implement __destruct() method.
    }
}