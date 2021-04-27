// Функция для добавления страны в таблицу
function addCountry(){
    // Получаем имя страны из поля
    var countryName = $("#country-name").val();

    // Если значение переменной не пустое
    if (countryName != ""){
        // Отправляем POST запрос на сервер
        $.ajax({
            url: "api.php", 
            method: "post",
            data: {
                // Указываем действие add - добавление новой страны в таблицу
                action: "add", 
                // В параметре name передаем имя страны
                name: countryName
            },

            // Обработка ответа от сервера
            success: function(response){
                // Если в ответе присутствует текст "ERROR", возникла ошибка
                if (response.indexOf("ERROR") != -1){
                    // Выводим сообщение об ошибке
                    alert(response);
                    // Прерываем выполнение функции
                    return;
                }

                // Если все хорошо, очистим поле для ввода страны и обновим таблицу
                $("#country-name").val("");
                // В response пришел обновленный HTML код таблицы с новой страной, заносим его в tbody
                $("table tbody").html(response);
            }
        });
    } else {
        // Если значение переменной пустое, просим пользователя ввести страну
        alert("Пожалуйста, введите страну");
    }
}

// Функция для получения таблицы со странами
function getCountries(){
    // Отправляем POST запрос на сервер
    $.ajax({
        url: "api.php", 
        method: "post",
        data: {
            // Указываем действие get - получение таблицы со странами
            action: "get"
        },

        // Обработка ответа от сервера
        success: function(response){
            // Если в ответе присутствует текст "ERROR", возникла ошибка
            if (response.indexOf("ERROR") != -1){
                // Выводим сообщение об ошибке
                alert(response);
                // Прерываем выполнение функции
                return;
            }

            // В response пришел HTML код таблицы со странами, заносим его в tbody
            $("table tbody").html(response);
        }
    });
}

// Устанавливаем на событие клика по кнопке функцию addCountry
$("button").click(addCountry);

// Получаем таблицу стран при загрузке страницы
getCountries();