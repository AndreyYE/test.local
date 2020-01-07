<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 15.12.2019
 * Time: 21:41
 */

namespace app\api;

//include __DIR__."/../../style.css";

$environment = require_once __DIR__ . "/../../environment.php";


class Description
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
    public function get_all_authors()
    {
        $response=[];
        $conn = $this->connection;
        $sql = "SELECT id, name
                FROM authors";
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
     public function get_all_publishes()
     {
         $response=[];
         $conn = $this->connection;
         $sql = "SELECT id, name
                FROM publishes";
         $result = $conn->query($sql);
         if ($result->num_rows > 0) {
             while($row = $result->fetch_assoc()) {
                 array_push($response, $row);
             }
             return $response;
         } else {
             return "We don't have any publishes";
         }
     }

     public function get_all_books()
     {
         $response=[];
         $conn = $this->connection;
         $sql = "SELECT id, name
                FROM books";
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
    public function run()
    {
        return 'work';
    }

    public function __destruct()
    {
        $this->connection->close();
        // TODO: Implement __destruct() method.
    }
};

if ($_SERVER['REQUEST_URI']=='/api/'): ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
      "http://www.w3.org/TR/html4/strict.dtd">
    <html>
     <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
         <link rel="stylesheet" href="../../style.css">
      <title>Api</title>
     </head>
     <body>
     <?php $description = new Description($environment); ?>
     <div id="site_url" style="display: none"><?php echo 'http://'.$_SERVER['HTTP_HOST']?></div>
     <p>Тестовое приложение. В приложенни три сущности: автор, книга, издательство.</p>
     <div>
         <h1>Авторизация и аутентификация</h1>
         <div>
             Ответ сервера:
             <p>message - описание действий при окончании токена доступа</p>
             <p>token_access - токен доступа. Нужно добавлять в заголовки запроса (Authorization) при добавлении, удалении и редактировании любой из трех сущностей</p>
         </div>
         <h3>Регистрация</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/registration'?>.
         </p>
         <p>Тело запроса - email, password.</p>
         <p>Параметр email - должен быть электронной почтой.</p>
         <p>Параметр password - должен быть строкой больше 6 символов.</p>
         <p>
         <form method="post" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/registration'?>">
             <input name="email" type="email" placeholder="почта">
             <input name="password" type="password" placeholder="пароль">
             <button type="submit">Зарегистрироваться</button>
         </form>
         </p>
         <h3>Логин</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/login'?>.
         </p>
         <p>Тело запроса - email, password.</p>
         <p>Параметр email - должен быть электронной почтой по которой вы регистрировались.</p>
         <p>Параметр password - должен совпадать с паролем по которому вы регистрировались.</p>
         <p>
         <form method="post" action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/login'?>">
             <input name="email" type="email" placeholder="почта">
             <input name="password" type="password" placeholder="пароль">
             <button type="submit">Авторизация</button>
         </form>
         </p>
     </div>
     <h1>Сущность издательство.</h1>
     <div>
         Ответ сервера:
         <p>id - id издательства</p>
         <p>name - название издательства</p>
         <p>reg_date - дата основания издательства</p>
     </div>
     <div>
         <h3>Показать все издательства.</h3>
         <p>
             Метод GET url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/'?>.
         </p>
         <p>
             <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/'?>">Показать все издательства</a>
         </p>
     </div>
     <div>
         <h3>Добавить издательство.</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>.
         </p>
         <p>Тело запроса - name.</p>
         <p>Параметр name - должен быть строкой.</p>
         <p>Чтобы добавить новое издательство под название "Весна", передайте в теле запроса name=Весна.</p>
         <p>
         <form name="add_publish" method="post" action="">
             <input name="token" type="text" placeholder="токен">
             <input name="name" type="text" placeholder="имя">
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>" hidden>
             <button type="submit">ДОБАВИТЬ ИЗДАТЕЛЬСТВО</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Удалить издательство.</h3>
         <p>
             Метод DELETE url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/{id}'?>.
         </p>
         <p>{id} - является id издетельства которое вы хотите удалить.</p>
         <p>Заголовок Authorization с token_access</p>
         <p>Все издательства можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>">Все издательства.</a></p>
         <p>Пример. У вас есть издательство {"id": 1, "name":"Зима"}. Чтобы его удать отправле запрос методом DELETE <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/'?>1.</p>
         <p>
         <form name="delete_publish" method="delete" action="">
             <input name="token" type="text" placeholder="токен">
             <select name="id">
                 <?php
                 if( $curl = curl_init() ) {
                     curl_setopt($curl, CURLOPT_URL, 'http://'.$_SERVER['HTTP_HOST'].'/api/publish');
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                     $out = curl_exec($curl);
                     foreach (json_decode($out) as $k=>$v){
                         echo "<option value='$v->id'>$v->name</option>";

                     }
                     curl_close($curl);
                 }
                 ?>
             </select>
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>" hidden>
             <button type="submit">УДАЛИТЬ ИЗДАТЕЛЬСТВО</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Редактировать издательство.</h3>
         <p>
             Метод PUT url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>.
         </p>
         <p>Тело запроса - name, id.</p>
         <p>id - является id издетельства которое вы хотите отредактировать. Параметр id - должен быть числом.</p>
         <p>name - является именем издетельства. Параметр name - должен быть строкой.</p>
         <p>Заголовок Authorization с token_access</p>
         <p>Все издательства можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>">Все издательства.</a></p>
         <p>Пример. У вас есть издательство {"id": 1, "name":"Зима"}. Чтобы изменить его имя на "Лето" отправле запрос методом PUT <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/'?> с телом запроса id=1&name=Зима.</p>
         <p>
         <form name="edit_publish" method="put" action="">
             <input name="token" type="text" placeholder="токен">
             <input name="name" type="text" placeholder="название издательства">
             <select name="id">
                 <?php
                 if( $curl = curl_init() ) {
                     curl_setopt($curl, CURLOPT_URL, 'http://'.$_SERVER['HTTP_HOST'].'/api/publish');
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                     $out = curl_exec($curl);
                     foreach (json_decode($out) as $k=>$v){
                         echo "<option value='$v->id'>$v->name</option>";

                     }
                     curl_close($curl);
                 }
                 ?>
             </select>
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>" hidden>
             <button type="submit">РЕДАКТИРОВАТЬ ИЗДАТЕЛЬСТВО</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Показать одно издательство.</h3>
         <p>
             Метод GET url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/{id}'?>.
         </p>
         <p>id - является id издетельства которое вы хотите посмотреть. Параметр id - должен быть числом.</p>
         <p>Все издательства можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>">Все издательства.</a></p>
         <p>Пример. У вас есть издательство {"id": 1, "name":"Зима"}. Чтобы увидеть всю его информацию отправле запрос методом GET <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/'?>1.</p>
     </div>
     <h1>Сущность автор.</h1>
     <div>
         Ответ Сервера:
         <p>Author_id - id автора</p>
         <p>Author_name - имя автора</p>
         <p>Book_id - id книги написаной автором</p>
         <p>Book_name - назмание книги</p>
         <p>Publish_id - id издательства которое публикует книгу</p>
         <p>Publish_name - название издательства которое публикует книгу</p>
     </div>
     <div>
         <h3>Показать всех авторов.</h3>
         <p>
             Метод GET url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author/'?>.
         </p>
         <p>
             <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author/'?>">Показать всех авторов</a>
         </p>
     </div>
     <div>
         <h3>Добавить автора.</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>.
         </p>
         <p>Тело запроса - name.</p>
         <p>Параметр name - должен быть строкой.</p>
         <p>Заголовок Authorization с token_access</p>
         <p>Чтобы добавить нового автора Шевченко, передайте в теле запроса name=Шевченко.</p>
         <p>
         <form name="add_author" method="post" action="">
             <input name="token" type="text" placeholder="токен">
             <input name="name" type="text" placeholder="имя">
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>" hidden>
             <button type="submit">ДОБАВИТЬ АВТОРА</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Удалить автора.</h3>
         <p>
             Метод DELETE url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author/{id}'?>.
         </p>
         <p>{id} - является id автора которого вы хотите удалить.</p>
         <p>Всех авторов можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>">Авторы</a></p>
         <p>Заголовок Authorization с token_access</p>
         <p>Пример. У вас есть авторы {"id": 1, "name":"Шевченко"} и {"id": 2, "name":"Украинка"}, которые написали совметную книгу.
             Если мы удалим автора Шевченко то совместная книга не удалится.
             Если у книги только одни автор то при удаление автора книга удалится тоже.
             Чтобы удать автора отправле запрос методом DELETE <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish/'?>1.</p>
         <p>
         <form name="delete_author" method="delete" action="">
             <input name="token" type="text" placeholder="токен">
             <select name="id">
                 <?php
                     foreach ($description->get_all_authors() as $k=>$v){
                         echo "<option value='$v[id]'>$v[name]</option>";
                 }
                 ?>
             </select>
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>" hidden>
             <button type="submit">УДАЛИТЬ АВТОРА</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Редактировать автора.</h3>
         <p>
             Метод PUT url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>.
         </p>
         <p>Тело запроса - name, id, untie, add_books.</p>
         <p>id - является id автора которого вы хотите отредактировать. Параметр id - должен быть числом.</p>
         <p>name - является именем автора. Параметр name - должен быть строкой.</p>
         <p>untie - список id книг автром которых больше не будет являтся. Id книг должны передаваться через запятую.</p>
         <p>add_books - список id книг автром которых станет. Id книг должны передаваться через запятую.</p>
         <p>Заголовок Authorization с token_access</p>
         <p>Всех авторов можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>">Авторы</a></p>
         <p>Пример. У вас есть автор {"id": 1, "name":"Шевченко"} Который написал две книги {"id": 1, "name":"Книга 1"}, {"id": 2, "name":"Книга 2"}, а также есть треться книги которую написал другой автор {"id": 3, "name":"Книга 3"}.
             Чтобы изменить имя автора на "Украинка", отказаться от авторства книг "Книга 1" и "Книга 2", а также добавить автору книгу "Книга 3" отправле запрос методом PUT <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author/'?>
             с телом запроса id=1&name=Украинка&untie=1,2&add_books=3.</p>
         <p>
         <form name="edit_author" method="put" action="">
             <input name="token" type="text" placeholder="токен">
             <input name="name" type="text" placeholder="изменить имя">
             <select name="id">
                 <?php
                 foreach ($description->get_all_authors() as $k=>$v){
                     echo "<option value='$v[id]'>$v[name]</option>";
                 }
                 ?>
             </select>
             <div id="delete_books" class="delete_point"><span>Удалить книги автора</span></div>
             <div id="add_books" class="edit_point"><span>Добавить книги автору</span></div>
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>" hidden>
             <button type="submit">РЕДАКТИРОВАТЬ АВТОРА</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Показать одного автора.</h3>
         <p>
             Метод GET url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author/{id}'?>.
         </p>
         <p>id - является id автора которого вы хотите посмотреть. Параметр id - должен быть числом.</p>
         <p>Всех авторов можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>">Авторы</a></p>
         <p>Пример. У вас есть автор {"id": 1, "name":"Шевченко"}. Чтобы увидеть всю его информацию отправле запрос методом GET <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author/'?>1.</p>
     </div>
     <h1>Сущность книга.</h1>
     <div>
         <div>
         Ответ Сервера:
         <p>Author_id - id автора</p>
         <p>Author_name - имя автора</p>
         <p>Book_id - id книги написаной автором</p>
         <p>Book_name - назмание книги</p>
         <p>Publish_id - id издательства которое публикует книгу</p>
         <p>Publish_name - название издательства которое публикует книгу</p>
     </div>
         <h3>Показать все книги</h3>
         <p>
             Метод GET url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book/'?>.
         </p>
         <p>
             <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book/'?>">Показать все книги</a>
         </p>
     </div>
     <div>
         <h3>Добавить книгу.</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>.
         </p>
         <p>Тело запроса - name, publish_id, authors_id.</p>
         <p>Параметр name - Название книги. должен быть строкой.</p>
         <p>Параметр publish_id - Id издательства которое будет публиковать книгу. Должен быть числом. Ссылка на все издательства <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>">Все издательства.</a></p>
         <p>Параметр authors_id - Id авторов книги. должен быть числом, если авторов больше чем один нужно писать через запятую authors_id=1,2,3,4,5.</p>
         <p>Заголовок Authorization с token_access</p>
         <p>Чтобы создать книгу передайте в теле запроса name=Книга1&publish_id=1&authors_id=1,2,3</p>
         <p>
         <form name="add_book" method="post" action="">
             <input name="token" type="text" placeholder="токен">
             <input name="name" type="text" placeholder="название книги">
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>" hidden>
             <select name="authors_id">
                 <?php
                 foreach ($description->get_all_authors() as $k=>$v){
                     echo "<option value='$v[id]'>$v[name]</option>";
                 }
                 ?>
             </select>
             <select name="publish_id">
                 <?php
                 foreach ($description->get_all_publishes() as $k=>$v){
                     echo "<option value='$v[id]'>$v[name]</option>";
                 }
                 ?>
             </select>
             <button type="submit">ДОБАВИТЬ КНИГУ</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Удалить книгу.</h3>
         <p>
             Метод DELETE url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book/{id}'?>.
         </p>
         <p>{id} - является id книги которого вы хотите удалить.</p>
         <p>Заголовок Authorization с token_access</p>
         <p>Все книги можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>">Книги</a></p>
         <p>Пример. Допустить есть книга {"id":"1", "name":"Книга 1", "publish_id":"1"}.Чтобы удать книгу отправле запрос методом DELETE <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book/'?>1.</p>
         <p>
         <form name="delete_book" method="delete" action="">
             <input name="token" type="text" placeholder="токен">
             <select name="id">
                 <?php
                 foreach ($description->get_all_books() as $k=>$v){
                     echo "<option value='$v[id]'>$v[name]</option>";
                 }
                 ?>
             </select>
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>" hidden>
             <button type="submit">УДАЛИТЬ КНИГУ</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Редактировать книгу.</h3>
         <p>
             Метод PUT url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>.
         </p>
         <p>Тело запроса - name, id, publish_id, untie, add_authors.</p>
         <p>id - является id книги которую вы хотите отредактировать . Параметр id - должен быть числом.</p>
         <p>name - является названием книги. Параметр name - должен быть строкой.</p>
         <p>untie - список id авторов которые перестануть быть авторами редактируемой книги. Id авторов должны передаваться через запятую.</p>
         <p>add_authors - список id авторов которые станут авторами редактируемой книги. Id авторов должны передаваться через запятую.</p>
         <p>Всех авторов можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>">Авторы</a></p>
         <p>Заголовок Authorization с token_access</p>
         <p>Все книги можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>">Книги</a></p>
         <p>Пример. У вас есть книга {"id": 1, "name":"Книга 1"} которую написало два автора {"id": 1, "name":"Шевченко"}, {"id": 2, "name":"Украинка"}, а также есть третий автор {"id": 3, "name":"Франко"}.
             Чтобы изменить название книги на "Книга 2", удалить авторов id=1 и id=2, а также добавить книге автора "Франко" отправле запрос методом PUT <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book/'?>
             с телом запроса id=1&name=Книга 2&untie=1,2&add_authors=3.</p>
         <p>
         <form name="edit_book" method="put" action="">
             <input name="token" type="text" placeholder="токен">
             <input name="name" type="text" placeholder="изменить название">
             <select name="id">
                 <?php
                 foreach ($description->get_all_books() as $k=>$v){
                     echo "<option value='$v[id]'>$v[name]</option>";
                 }
                 ?>
             </select>
             <div id="delete_authors" class="delete_point"><span>Удалить автора</span></div>
             <div id="add_authors" class="edit_point"><span>Добавить авторa</span></div>
             <div id="change_publish" class="edit_point_publish"><span>Изменить издательство</span></div>
             <input name="url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>" hidden>
             <button type="submit">РЕДАКТИРОВАТЬ КНИГУ</button>
         </form>
         </p>
     </div>
     <div>
         <h3>Показать одну книгу.</h3>
         <p>
             Метод GET url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book/{id}'?>.
         </p>
         <p>id - является id книги которую вы хотите посмотреть. Параметр id - должен быть числом.</p>
         <p>Все книги можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book'?>">Книги</a></p>
         <p>Пример. У вас есть книга {"id": 1, "name":"Книга 1"}. Чтобы увидеть всю его информацию отправле запрос методом GET <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/book/'?>1.</p>
     </div>
     <script type="text/javascript">
         /**
          *
          * @param form type DOM Element
          * @param method type String
          * @param body type Object
          */
         // eed422ea4d57707fcffe18c573b9c14a
         function custom_fetch(form, method, body){
                 let body_stirng ='';
                 if(body.add_books || body.untie || body.add_authors || body.publish_id){
                     for (let prop in body) {
                         body_stirng+=prop+'='+body[prop]+'&';
                     }
                }else{
                     for (let prop in body) {
                         for (let el_key in form.elements) {
                             if(form.elements[el_key].name == prop){
                                 body_stirng+=prop+'='+form.elements[el_key].value+'&';
                             }

                         }
                     }
                }

                 let url = form.elements.url.value;
                 if(method === 'DELETE'){
                     url=url+"/"+form.elements.id.value
                 }
                 console.log();
             console.log(body_stirng);
             console.log(method);
             console.log(url);
                 let response = fetch(url,{
                     method: method, // *GET, POST, PUT, DELETE, etc.
                     mode: 'cors', // no-cors, cors, *same-origin
                     cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                     credentials: 'same-origin', // include, *same-origin, omit
                     headers: {
                         'Content-Type': 'application/x-www-form-urlencoded',
                         'Authorization': form.elements.token.value,
                     },
                     redirect: 'follow', // manual, *follow, error
                     referrer: 'no-referrer', // no-referrer, *client
                     body: body_stirng ? body_stirng : '',
                 });
                 response.then(res=>{
                     console.log('resp '+res);
                     if(res.status === 200){
                         form.parentNode.innerHTML += '<div id="success" style="color: red">Всё прошло успешкно</div>';
                         setTimeout(function(){
                             let get_success_message = document.querySelector('#success');
                             get_success_message.remove();
                         },4000);
                         document.location.href = document.querySelector('#site_url').innerHTML;
                     }
                     if(res.status === 500){
                         form.parentNode.innerHTML += '<div id="error" style="color: red">Ошибка</div>';
                         setTimeout(function(){
                             let get_success_message = document.querySelector('#error');
                             get_success_message.remove();
                         },4000)
                     }
                 }).catch(err=>{
                     console.log('error '+err);
                     form.parentNode.innerHTML += '<div id="error" style="color: red">Ошибка</div>';
                     setTimeout(function(){
                         let get_success_message = document.querySelector('#error');
                         get_success_message.remove();
                     },4000)
                 });
         };
        // ДОБАВИТЬ ИЗДАТЕЛЬСТВО
        let add_form_publish = document.forms.add_publish;
         add_form_publish.addEventListener('submit', function (e) {
             e.preventDefault();
             custom_fetch(add_form_publish, 'POST', {'name': true});
         });

        //УДАЛИТЬ ИЗДАТЕЛЬСТВО
        let delete_form_publish = document.forms.delete_publish;
        delete_form_publish.addEventListener('submit', function (e) {
            e.preventDefault();
            custom_fetch(delete_form_publish, 'DELETE', {});
        });
         //РЕДАКТИРОВАТЬ ИЗДАТЕЛЬСТВО
         let edit_form_publish = document.forms.edit_publish;
         edit_form_publish.addEventListener('submit', function (e) {
             e.preventDefault();
             custom_fetch(edit_form_publish, 'PUT', {name: true, id: true});
         });

         // ДОБАВИТЬ КНИГУ
         let add_form_book = document.forms.add_book;
         add_form_book.addEventListener('submit', function (e) {
             e.preventDefault();
             custom_fetch(add_form_book, 'POST', {'name': true,'authors_id': true, 'publish_id': true});
         });

         //УДАЛИТЬ КНИГУ
         let delete_form_book = document.forms.delete_book;
         delete_form_book.addEventListener('submit', function (e) {
             e.preventDefault();
             custom_fetch(delete_form_book, 'DELETE', {});
         });
         //РЕДАКТИРОВАТЬ КНИГУ

         let edit_form_book = document.forms.edit_book;
         edit_form_book.addEventListener('submit', function (e) {
             e.preventDefault();
             let body = {};
             if(edit_form_book.elements.name){
                 body.name = edit_form_book.elements.name.value;
             }
             body.id = edit_form_book.elements.id.value;
             if(document.querySelector('#list_publish')){
                 let change_publish = document.querySelector('#list_publish').childNodes;
                 for (let prop of change_publish) {
                     if(prop.childNodes[0].checked){
                             console.log('first');
                             body.publish_id = prop.id;
                     }
                 }
             }
             if(document.querySelector('#list_authors')){
                 let delete_authors = document.querySelector('#list_authors').childNodes;
                 let i = 0;
                 for (let prop of delete_authors) {
                     if(prop.childNodes[0].checked){
                         if(i==0){
                             body.untie = prop.id+',';
                         }else{
                             body.untie += prop.id+',';
                         }
                     }
                     i++;
                 }
             }
             if(document.querySelector('#not_authors')){
                 let add_authors = document.querySelector('#not_authors').childNodes;
                 let j = 0;
                 for (let prop of add_authors) {
                     if(prop.childNodes[0].checked){
                         if(j==0){
                             body.add_authors  = prop.id+',';
                         }else{
                             body.add_authors  += prop.id+',';
                         }
                     }
                     j++;
                 }
             }

             if(body.untie.length){
                 body.untie = body.untie.substr(0, body.untie.length-1);
             }
             if(body.add_authors.length){
                 body.add_authors  = body.add_authors .substr(0, body.add_authors .length-1);
             }
            custom_fetch(edit_form_book, 'PUT', body);
         });
         // УДАЛИТЬ АВТОРА КНИГИ
         let delete_authors_element = document.querySelector('#delete_authors');
         delete_authors_element.addEventListener('click',function(){
             let list_authors = document.querySelector('#list_authors');
             if(!list_authors){
                 let url_book = document.querySelector('#site_url').innerHTML+'/api/custom?book_id='+edit_form_book.elements.id.value;
                 let response = fetch(url_book,{
                     method: 'GET', // *GET, POST, PUT, DELETE, etc.
                     mode: 'cors', // no-cors, cors, *same-origin
                     cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                     credentials: 'same-origin', // include, *same-origin, omit
                     redirect: 'follow', // manual, *follow, error
                     referrer: 'no-referrer', // no-referrer, *client
                 });
                 response.then(response => response.json())
                     .then((body) => {
                         if(typeof  body == 'string'){
                             let newDiv = document.createElement("ul");
                             newDiv.setAttribute("id", "list_authors");
                             newDiv.innerHTML += "<li>"+body+"</li>";
                             delete_authors_element.appendChild(newDiv);
                             return true;
                         }
                         let newDiv = document.createElement("ul");
                         newDiv.setAttribute("id", "list_authors");
                         for (let prop in body) {
                             newDiv.innerHTML += "<li id="+body[prop].id+"><input type='checkbox'>"+body[prop].name+"</li>";
                         }
                         delete_authors_element.appendChild(newDiv);
                     }).catch(err=>{
                     console.log(err);
                 });
             }
         });
         //ДОБАВИТЬ КНИГИ АВТОРУ
         let add_authors_element = document.querySelector('#add_authors');
         add_authors_element.addEventListener('click',function(){
             let list_authors = document.querySelector('#not_authors');
             if(!list_authors){
                 let url_author= document.querySelector('#site_url').innerHTML+'/api/custom?book_is_not_authors='+edit_form_book.elements.id.value;
                 let response = fetch(url_author,{
                     method: 'GET', // *GET, POST, PUT, DELETE, etc.
                     mode: 'cors', // no-cors, cors, *same-origin
                     cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                     credentials: 'same-origin', // include, *same-origin, omit
                     redirect: 'follow', // manual, *follow, error
                     referrer: 'no-referrer', // no-referrer, *client
                 });
                 response.then(response => response.json())
                     .then((body) => {
                         if(typeof  body == 'string'){
                             let newDiv = document.createElement("ul");
                             newDiv.setAttribute("id", "not_authors");
                             newDiv.innerHTML += "<li>"+body+"</li>";
                             add_authors_element.appendChild(newDiv);
                             return true;
                         }
                         let newDiv = document.createElement("ul");
                         newDiv.setAttribute("id", "not_authors");
                         for (let prop in body) {
                             newDiv.innerHTML += "<li id="+body[prop].id+"><input type='checkbox'>"+body[prop].name+"</li>";
                         }
                         add_authors_element.appendChild(newDiv);
                     }).catch(err=>{
                     console.log(err);
                 });
             }
         });

         //ИЗМЕНИТЬ ИЗДАТЕЛЬСТВО
         let change_publish_element = document.querySelector('#change_publish');
         change_publish_element.addEventListener('click',function(){
             let list_publishes = document.querySelector('#list_publish');
             if(!list_publishes){
                 let url_publish= document.querySelector('#site_url').innerHTML+'/api/custom?list_publish_to_book='+edit_form_book.elements.id.value;
                 let response = fetch(url_publish,{
                     method: 'GET', // *GET, POST, PUT, DELETE, etc.
                     mode: 'cors', // no-cors, cors, *same-origin
                     cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                     credentials: 'same-origin', // include, *same-origin, omit
                     redirect: 'follow', // manual, *follow, error
                     referrer: 'no-referrer', // no-referrer, *client
                 });
                 response.then(response => response.json())
                     .then((body) => {
                         let newDiv = document.createElement("ul");
                         newDiv.setAttribute("id", "list_publish");
                         for (let prop in body['all']) {
                             if(body['all'][prop].id===body['checked'][0].id){
                                 newDiv.innerHTML += "<li id="+body['all'][prop].id+">" +
                                     "<input type='radio' checked" +
                                     ">" +
                                     ""+body['all'][prop].name+"</li>";
                             }else{
                                 newDiv.innerHTML += "<li id="+body['all'][prop].id+">" +
                                     "<input type='radio'" +
                                     ">" +
                                     ""+body['all'][prop].name+"</li>";
                             }
                         }
                         change_publish_element.appendChild(newDiv);
                         //ИЗМЕНЕНИЕ INPUT type radio
                         let checked_publish = document.querySelector('#list_publish');
                         if(checked_publish){
                             checked_publish.addEventListener('click',function(e){
                                 if(e.target.tagName=="INPUT"){
                                     let all_input = document.querySelectorAll('#list_publish input');
                                     for (let i of all_input) {
                                         if(i.checked && i==e.target){
                                         }else{
                                             i.checked = false;
                                         }
                                     }
                                 }
                             });
                         }
                     }).catch(err=>{
                     console.log(err);
                 });
             }
         });

         // ИЗМЕНЕНИЕ КНИГИ В SELECT
         edit_form_book.elements.id.addEventListener('change',function(){
             if(document.querySelector('#list_authors')){
                 document.querySelector('#list_authors').remove();
             }
             if(document.querySelector('#not_authors')){
                 document.querySelector('#not_authors').remove();
             }
         });





         // ДОБАВИТЬ АВТОРА
         let add_form_author = document.forms.add_author;
         add_form_author.addEventListener('submit', function (e) {
             e.preventDefault();
             custom_fetch(add_form_author, 'POST', {'name': true});
         });

         //УДАЛИТЬ АВТОРА
         let delete_form_author = document.forms.delete_author;
         delete_form_author.addEventListener('submit', function (e) {
             e.preventDefault();
             custom_fetch(delete_form_author, 'DELETE', {});
         });
         //РЕДАКТИРОВАТЬ АВТОРА
         let edit_form_author = document.forms.edit_author;
         edit_form_author.addEventListener('submit', function (e) {
             e.preventDefault();
             let body = {};
             if(edit_form_author.elements.name){
                 body.name = edit_form_author.elements.name.value;
             }
             body.id = edit_form_author.elements.id.value;

             if(document.querySelector('#books_author')){
                 let delele_books_author = document.querySelector('#books_author').childNodes;
                    let i = 0;
                 for (let prop of delele_books_author) {
                    if(prop.childNodes[0].checked){
                        if(i==0){
                            body.untie = prop.id+',';
                        }else{
                            body.untie += prop.id+',';
                        }
                    }
                     i++;
                 }
             }
             if(document.querySelector('#not_books_author')){
                 let add_books_author = document.querySelector('#not_books_author').childNodes;
                 let j = 0;
                 for (let prop of add_books_author) {
                     if(prop.childNodes[0].checked){
                         if(j==0){
                             body.add_books = prop.id+',';
                         }else{
                             body.add_books += prop.id+',';
                         }
                     }
                     j++;
                 }
             }

             if(body.untie.length){
                 body.untie = body.untie.substr(0, body.untie.length-1);
             }
             if(body.add_books.length){
                 body.add_books = body.add_books.substr(0, body.add_books.length-1);
             }
             //console.log(body);
            custom_fetch(edit_form_author, 'PUT', body);
         });
         // УДАЛИТЬ КНИГИ АВТОРА
         let delete_books_element = document.querySelector('#delete_books');
         delete_books_element.addEventListener('click',function(){
             let list_books_author = document.querySelector('#books_author');
             if(!list_books_author){
                 let url_author= document.querySelector('#site_url').innerHTML+'/api/custom?author_id='+edit_form_author.elements.id.value;
                 let response = fetch(url_author,{
                     method: 'GET', // *GET, POST, PUT, DELETE, etc.
                     mode: 'cors', // no-cors, cors, *same-origin
                     cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                     credentials: 'same-origin', // include, *same-origin, omit
                     redirect: 'follow', // manual, *follow, error
                     referrer: 'no-referrer', // no-referrer, *client
                 });
                 response.then(response => response.json())
                     .then((body) => {
                         if(typeof  body == 'string'){
                             let newDiv = document.createElement("ul");
                             newDiv.setAttribute("id", "books_author");
                             newDiv.innerHTML += "<li>"+body+"</li>";
                             delete_books_element.parentNode.insertBefore(newDiv, delete_books_element.nextSibling);
                             return true;
                         }
                         let newDiv = document.createElement("ul");
                         newDiv.setAttribute("id", "books_author");
                         for (let prop in body) {
                             newDiv.innerHTML += "<li id="+body[prop].id+"><input type='checkbox'>"+body[prop].name+"</li>";
                         }
                         delete_books_element.parentNode.insertBefore(newDiv, delete_books_element.nextSibling)
                     }).catch(err=>{
                     console.log(err);
                 });
             }
         });
         //ДОБАВИТЬ КНИГИ АВТОРУ
         let add_books_element = document.querySelector('#add_books');
         add_books_element.addEventListener('click',function(){
             let list_not_books_author = document.querySelector('#not_books_author');
             if(!list_not_books_author){
                 let url_author= document.querySelector('#site_url').innerHTML+'/api/custom?author_id_not_books='+edit_form_author.elements.id.value;
                 let response = fetch(url_author,{
                     method: 'GET', // *GET, POST, PUT, DELETE, etc.
                     mode: 'cors', // no-cors, cors, *same-origin
                     cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                     credentials: 'same-origin', // include, *same-origin, omit
                     redirect: 'follow', // manual, *follow, error
                     referrer: 'no-referrer', // no-referrer, *client
                 });
                 response.then(response => response.json())
                     .then((body) => {
                         if(typeof  body == 'string'){
                             let newDiv = document.createElement("ul");
                             newDiv.setAttribute("id", "not_books_author");
                             newDiv.innerHTML += "<li>"+body+"</li>";
                             add_books_element.parentNode.insertBefore(newDiv, add_books_element.nextSibling);
                             return true;
                         }
                         let newDiv = document.createElement("ul");
                         newDiv.setAttribute("id", "not_books_author");
                         for (let prop in body) {
                             newDiv.innerHTML += "<li id="+body[prop].id+"><input type='checkbox'>"+body[prop].name+"</li>";
                         }
                         add_books_element.parentNode.insertBefore(newDiv, add_books_element.nextSibling)
                     }).catch(err=>{
                     console.log(err);
                 });
             }
         });
         // ИЗМЕНЕНИЕ АВТОРА В SELECT
         edit_form_author.elements.id.addEventListener('change',function(){
             if(document.querySelector('#books_author')){
                 document.querySelector('#books_author').remove();
             }
             if(document.querySelector('#not_books_author')){
                 document.querySelector('#not_books_author').remove();
             }
         });
     </script>
     </body>
    </html>
<?php endif; ?>