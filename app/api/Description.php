<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 15.12.2019
 * Time: 21:41
 */

namespace app\api;


class Description
{
    public function run()
    {
        return 'work';
    }
};

if ($_SERVER['REQUEST_URI']=='/api/'): ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
      "http://www.w3.org/TR/html4/strict.dtd">
    <html>
     <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <title>Api</title>
     </head>
     <body>
     <p>Тестовое приложение. В приложенни три сущности: автор, книга, издательство.</p>
     <div>
         <h1>Авторизация и аутентификация</h1>
         <div>
             Ответ сервера:
             <p>message - описание действий при окончвнии токена доступа</p>
             <p>token_access - токен доступа. Нужно добавлять в заголовки запроса (Authorization) при добавлении, удалении и редактировании любой из трех сущностей</p>
         </div>
         <h3>Регистрация</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/registration'?>.
         </p>
         <p>Тело запроса - email, password.</p>
         <p>Параметр email - должен быть электронной почтой.</p>
         <p>Параметр password - должен быть строкой больше 6 символов.</p>
         <h3>Логин</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/login'?>.
         </p>
         <p>Тело запроса - email, password.</p>
         <p>Параметр email - должен быть электронной почтой по которой вы регистрировались.</p>
         <p>Параметр password - должен совпадать с паролем по которому вы регистрировались.</p>
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
     </div>
     <div>
         <h3>Добавить издательство.</h3>
         <p>
             Метод POST url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/publish'?>.
         </p>
         <p>Тело запроса - name.</p>
         <p>Параметр name - должен быть строкой.</p>
         <p>Чтобы добавить новое издательство под название "Весна", передайте в теле запроса name=Весна.</p>
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
     </div>
     <div>
         <h3>Редактировать автора.</h3>
         <p>
             Метод PUT url - <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>.
         </p>
         <p>Тело запроса - name, id, untie, add_books.</p>
         <p>id - является id автора которого вы хотите отредактировать. Параметр id - должен быть числом.</p>
         <p>name - является именем автора. Параметр name - должен быть строкой.</p>
         <p>untie - список id книг автром которых больше не будет явлсятся. Id книг должны передаваться через запятую.</p>
         <p>add_books - список id книг автром которых станет. Id книг должны передаваться через запятую.</p>
         <p>Заголовок Authorization с token_access</p>
         <p>Всех авторов можете увидеть по ссылке <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author'?>">Авторы</a></p>
         <p>Пример. У вас есть автор {"id": 1, "name":"Шевченко"} Который написал две книги {"id": 1, "name":"Книга 1"}, {"id": 2, "name":"Книга 2"}, а также есть треться книги которую написал другой автор {"id": 3, "name":"Книга 3"}.
             Чтобы изменить имя автора на "Украинка", отказаться от авторства книг "Книга 1" и "Книга 2", а также добавить автору книгу "Книга 3" отправле запрос методом PUT <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/author/'?>
             с телом запроса id=1&name=Украинка&untie=1,2&add_books=3.</p>
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
     </body>
    </html>
<?php endif; ?>