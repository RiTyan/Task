<?php 

// Функция для создания подключения с базой данных
// Вызвращает объект подключения PDO
function connectDB(){
    // Данные для подключения к базе данных
    $db_host = "localhost";
    $db_name = "countries";
    $db_user = "root";
    $db_password = ""; 

    // Создание подключения
    $database = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    return $database;
}

// Функция для добавления страны в таблицу
function addCountry(){
    // Получаем имя страны, переданное через POST запрос
    $name = $_POST["name"];

    // Если значение переменной установлено
    if (isset($name)){
        // Выполняем подключение к базе данных
        $database = connectDB();

        // Подготавливаем запрос для добавления строки в таблицу
        $query = $database->prepare("INSERT INTO countries (name) VALUES (:name)");
        // Подставляем значение в нужное место
        $query->bindValue(":name", $name, PDO::PARAM_STR);

        // Выполняем запрос
        if (!$query->execute()){
            // Если произошла ошибка, выводим ее в ответ на запрос
            die("ERROR - Failed to add country to database");
        }

        // Если все хорошо, выводим обновленную таблицу стран в ответ на запрос
        getCountries();
    } else {
        // Если значение переменной не установлено (пустое), выводим ошибку
        echo "ERROR - Country name required";
    }
}

// Функция для получения таблицы стран
function getCountries(){
    // Выполняем подключение к базе данных
    $database = connectDB();

    // Получаем строки из таблицы со странами
    $query = $database->query("SELECT name FROM countries");
    // Записываем их в ассоциативный массив
    $table = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Проходимся по массиву строк циклом, формируя HTML код таблицы в ответ на запрос
    foreach($table as $row){
        ?>
            <tr>
                <td>
                    <?php 
                        // Выводим имя страны, используя функцию htmlspecialchars для защиты от XSS атаки
                        echo htmlspecialchars($row["name"]); 
                    ?>
                </td>
            </tr>
        <?php
    }
}

// Функция обработки запроса от клиента
function handleRequest(){
    // Переменная с требуемым действием
    $action = $_POST["action"];

    // Если значение переменной установлено
    if (isset($action)) {
        // Проверяем, какое это действие
        switch ($action){
            case "add":
                // Если значение равно add, вызываем функцию для добавления страны в таблицу
                addCountry();
                break;
            case "get":
                // Если значение равно get, вызываем функцию для получения таблицы со странами
                getCountries();
                break;
            default:
                // Если такого действия нет, выводим ошибку
                echo "ERROR - Unknown action";
                break;
        }
    } else {
        // Если значение переменной не установлено (пустое), выводим ошибку
        echo "ERROR - Action name required";
    }
}

// Обрабатываем запрос
handleRequest();

?>