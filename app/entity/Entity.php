<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 13.12.2019
 * Time: 11:03
 */
namespace app\entity;

abstract class Entity
{
    protected $connection;

    public function __construct($environment)
    {
        //Подключаемся к базе данных
        $servername =$environment['DB_SERVER_NAME'] ;
        $port = $environment['DB_PORT'];
        $username = $environment['DB_USER_NAME'];
        $password = $environment['DB_PASSWORD'];
        $db_name = $environment['DB_NAME'];

        // Создаем соединение
        $conn = new \mysqli($servername, $username, $password,$db_name,$port);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $this->connection = $conn;

        //Таблица publishes
        $sql = "CREATE TABLE IF NOT EXISTS publishes (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB COLLATE=utf8_unicode_ci";
        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error creating table publishes:  " . $conn->error;
        }
        //Таблица books
        $sql = "CREATE TABLE IF NOT EXISTS books (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                publish_id INT(6) UNSIGNED,
                name VARCHAR(150) NOT NULL,
                publication_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT books_publishes_fk
                FOREIGN KEY (publish_id)  REFERENCES publishes (id)
                ) ENGINE=InnoDB COLLATE=utf8_unicode_ci";
        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error creating table books:  " . $conn->error;
        }
        //Таблица authors
        $sql = "CREATE TABLE IF NOT EXISTS authors (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL
                ) ENGINE=InnoDB COLLATE=utf8_unicode_ci";
        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error creating table authors:  " . $conn->error;
        }
        //связь многие ко многим с таблицами books и authors
        $sql = "CREATE TABLE IF NOT EXISTS author_books (
                author_id INT(6) UNSIGNED,
                book_id INT(6) UNSIGNED,
                PRIMARY KEY (author_id, book_id),
                CONSTRAINT `FK__author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
                CONSTRAINT `FK_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`)
                ) ENGINE=InnoDB COLLATE=utf8_unicode_ci";
        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error creating table author_books:  " . $conn->error;
        }
        //связь многие ко многим с таблицами authors и publishes
        $sql = "CREATE TABLE IF NOT EXISTS author_publishes (
                author_id INT (6) UNSIGNED,
                publish_id INT(6) UNSIGNED,
                PRIMARY KEY (author_id, publish_id),
                CONSTRAINT `FK__author_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
                CONSTRAINT `publish` FOREIGN KEY (`publish_id`) REFERENCES `publishes` (`id`)
                ) ENGINE=InnoDB COLLATE=utf8_unicode_ci";
        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error creating table author_publishes:  " . $conn->error;
        }
        //Таблица User
        $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(150) NOT NULL,
                password VARCHAR(150) NOT NULL,
                token_access TEXT,
                time_expired_token TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_email (email)
                ) ENGINE=InnoDB COLLATE=utf8_unicode_ci";
        if ($conn->query($sql) === TRUE) {

        } else {
            echo "Error creating table authors:  " . $conn->error;
        }
    }
    protected function check_auth()
    {
        $authorization = getallheaders()['Authorization'];
        $time_expired='';
        $quantity='';
        $sql = "select count(*) as quantity, time_expired_token from users where token_access='$authorization'";
        $result = $this->connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $time_expired = $row['time_expired_token'];
                $quantity=$row['quantity'];
            }
        }
        $now = new \DateTime("NOW");
        $now->modify("-1 hour");
        $new_time =  $now->format('Y-m-d H:i:s');

        if($quantity>0 && strtotime($new_time)<strtotime($time_expired)){
            return true;
        } else {
           return false;
        }
    }
    public function __destruct()
    {
        $this->connection->close();
        // TODO: Implement __destruct() method.
    }

    abstract protected function create();
    abstract protected function show($id);
    abstract protected function update();
    abstract protected function delete($id);
    abstract protected function index();

}