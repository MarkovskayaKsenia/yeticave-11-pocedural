<?php
require_once ('helpers.php');
require_once ('data.php');
require_once('functions.php');
require_once('config.php'); //Настройки подключения к базе данных

//Получение категории из базы данных
$sql_categories = "SELECT id, name, description FROM outfit_categories";
$result_categories = mysqli_query($mysql, $sql_categories);

if (!$result_categories) {
    $error = mysqli_error($mysql);
    print ("Ошибка MySQL: " . $error);
    die();
}

$outfit_categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

//Массив для сбора ошибок валидации
$errors = [];

//Валидация формы добавления нового лота
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Массив полей, обязательных к заполнению
    $required_fields = ['email', 'password', 'name', 'message',];

    //Текст ошибок для пустых полей формы
    $empty_errors = [
        'email' => 'Введите e-mail',
        'password' => 'Введите пароль',
        'name' => 'Введите имя',
        'message' => 'Напишите как с вами связаться',
    ];

//Массив допустимых диапазонов для полей формы
    $ranges = [
        'name_min' => 3,
        'name_max' => 20,
        'password_min' => 10,
        'password_max' => 20,
        'message_min' => 11,
        'message_max' => 255,
    ];

//Правила валидации для полей
    $rules = [
        'email' => function () {
            return isCorrectEmail($_POST['email']);
        },
        'password' => function (array $ranges) {
            return isCorrectPassword($_POST['password'], $ranges['password_min'], $ranges['password_max']);
        },
        'name' => function (array $ranges) {
            return isCorrectLength($_POST['name'], $ranges['name_min'], $ranges['name_max']);
        },
        'message' => function (array $ranges) {
            return isCorrectLength($_POST['message'], $ranges['message_min'], $ranges['message_max']);
        },
    ];

//Применение правил валидации к полям формы
    foreach ($_POST as $key => $value) {

        if (isset($value) && !empty($value) && isset($rules[$key])) {
            $result = $rules[$key]($ranges);
        } else {
            $result = (in_array($key, $required_fields)) ? $empty_errors[$key] : '';
        }

        (isset($result) && !empty($result)) ? $errors[$key] = $result : '';
    };

//Загрузка пользователя в базу данных
    if (count($errors) == 0) {
        //Проверка на существование пользователя с таким же email
        $email = mysqli_real_escape_string($mysql, $_POST['email']);
        $sql_email_query = "SELECT id FROM users WHERE email = '$email'" ;
        $result_email = mysqli_query($mysql, $sql_email_query);

        if (mysqli_num_rows($result_email) > 0) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        }  else {

            //Добавление юзера в базу данных
            $sql_user = "INSERT INTO users (reg_date, email, login, password, contacts) "
                . " VALUES(NOW(), ?, ?, ?, ?)";

//Подготовка данных для передачи в базу данных
            $login = checkUserData($_POST['name']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $contacts = checkUserData($_POST['message']);

//Подготовка sql-выражения для добавления лота
            $stm_user = db_get_prepare_stmt($mysql, $sql_user, [
                $email,
                $login,
                $password,
                $contacts
            ]);

            if (mysqli_stmt_execute($stm_user)) {
                header('Location: /user-login.php');
                exit();
            }
        }
    }
}

//Отрисовка страницы
//Заголовок страницы
$title = 'Регистрация';

//Заполнение шаблонов данными и вставка на старницу
$page_content = include_template('sign-up.php', [
    'outfit_categories' => $outfit_categories,
    'errors' => $errors,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'outfit_categories' => $outfit_categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'title' => $title,
]);

print($layout_content);
