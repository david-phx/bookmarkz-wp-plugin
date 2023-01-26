В процессе обработки WordPress’a напильником, захотелось прикрутить к нему кнопочки для быстрого добавления статьи в разнообразные сети социальных закладок. Из найденного, более-менее вменяемого и русскоязычного, больше всего понравился плагин “Addzakl” с maxsite.org, но в нем для настроек нужно лезть в код плагина, что, на мой взгляд, совсем не круто.

В итоге, решил сделать свой плагин — с админкой, по-взрослому, заодно восстановив былые навыки софтописания. Итак, прошу любить и жаловать: мой первый плагин для WordPress’a — Bookmarkz!

### Основные возможности

- Автоматическая генерация ссылок на основные популярные англо- и русскоязычные социальные сети
- Управление отображаемыми ссылками через админку
- Автоматическая или ручная вставка кода в шаблон
- Возможность включать/отключать `rel=”nofollow”`
- Возможность включать/отключать открывание ссылок в новом окне
- Автоматическое отслеживание текущей версии плагина

### Установка

1. Скачайте дистрибутив и распакуйте его куда-нибудь
2. Скопируйте папку `bookmarkz` целиком со всем ее содержимым в `/wp-content/plugins/`
3. Зайдите в админку WordPress’а и активизируйте плагин Bookmarkz

В общем-то, этого достаточно, чтоб плагин начал работать.

### Настройка

- Заходите в Options -> Bookmarkz и настраиваете все на свой вкус — там все просто и понятно
- По умолчанию плагин добавляет список иконок со ссылками в конец любого поста на блоге. Чтоб вручную определить, где именно должна появиться полоска с иконками, установите галочку “Использовать ручную вставку кода” в настройках, а в своем шаблоне вставьте `<?php bookmarkz(); ?>` там, где вы хотите вывести иконки. Обратите внимание, что этот код должен быть размещен внутри цикла TheLoop, т.е. между `<?php while (have_posts()) : the_post(); ?>` и `<?php endwhile; ?>`
- Управлять внешним видом иконок, выравниванием, отступами и т.п. можно через определение класса div.bookmarkz в файле style.css вашего шаблона, например, так:
`div.bookmarkz {text-align: center; margin: 10px 0;}`

### Возможные глюки

- Плагин требует для работы PHP 5, звиняйте

Вот, пожалуй, и все.

Отзывы, пожелания, баг-репорты и (ну мало ли, вдруг и правда кто поблагодарит?) спасибы можно оставлять прямо здесь, в комментариях.

P.S. Использованные в процессе написания плагина полезные ресурсы: упомянутый maxsite.org, статья на MaulNet.ru, статья на seoups.com (и ее продолжение), сервис для создания виджетов Bookmarkz!

P.P.S. Если вы хотите поставить у себя ссылку на этот плагин, не ставьте ее на сам файл, пожалуйста, так как его адрес и название могут меняться. Поставьте лучше на эту страницу: http://www.dzhan.ru/blog/bookmarkz/. Спасибо!