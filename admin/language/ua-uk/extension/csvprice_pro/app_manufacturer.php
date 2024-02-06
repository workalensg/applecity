<?php
$_['heading_title'] = 'Виробники';
$_['heading_title_normal'] = 'CSV Price Pro import/export OC3';
$_['text_module'] = 'Модулі';
$_['text_extension'] = 'Розширення';
$_['text_default'] = ' <b>(За промовчанням)</b>';
$_['text_yes'] = 'Так';
$_['text_no'] = 'Ні';
$_['text_enabled'] = 'Включено';
$_['text_disabled'] = 'Відключено';
$_['text_select_all'] = 'Виділити все';
$_['text_unselect_all'] = 'Зняти виділення';
$_['text_select'] = 'Виділити';
$_['text_show_all'] = 'Показати все';
$_['text_hide_all'] = 'Приховати не позначені';
$_['text_all'] = 'Всі';
$_['text_no_results'] = 'Немає даних!';
$_['text_none'] = ' --- Не вибрано --- ';
$_['text_as'] = 'У вигляді %s';
$_['text_confirm_delete'] = 'Видалення неможливо скасувати! Ви впевнені, що бажаєте це зробити?';
$_['text_success_macros'] = 'Настройки макрсоов успешно обновлены!';
$_['text_import_mode_both'] = 'Оновити і Додати';
$_['text_import_mode_insert'] = 'Тільки додати';
$_['text_import_mode_update'] = 'Тільки оновити';
$_['text_success_import'] = 'Імпорт даних завершено!<br />Всього оброблено <b>%s</b> рядків!<br /><br /> Оновлено: <b>%s</b><br />Додано: <b>%s</b></b><br />Пропущено: <b>%s</b>';
$_['tab_export'] = 'Експорт';
$_['tab_import'] = 'Імпорт';
$_['tab_macros'] = 'Макроси';
$_['button_export'] = 'Експорт';
$_['button_import'] = 'Імпорт';
$_['button_insert'] = 'Додати новий';
$_['button_remove'] = 'Видалити';
$_['entry_table'] = 'Таблиця';
$_['entry_field_name'] = 'Ім&#39;я поля';
$_['entry_csv_name'] = 'Заголовок CSV';
$_['entry_caption'] = 'Найменування';
$_['entry_type'] = 'Тип';
$_['entry_manufacturer'] = 'Виробники';
$_['entry_file_encoding'] = 'Кодування файлу';
$_['entry_languages'] = 'Локалізація';
$_['entry_csv_delimiter'] = 'Роздільник полів CSV';
$_['entry_csv_text_delimiter'] = 'Роздільник тексту';
$_['entry_store'] = 'Магазини';
$_['entry_import_mode'] = 'Режим імпорту';
$_['entry_key_field'] = 'Ключове поле для оновлення';
$_['entry_sort_order'] = 'Порядок сортування';
$_['entry_status'] = 'Статус';
$_['entry_import_file'] = 'Імпорт даних з файлу';
$_['entry_import_img_download'] = 'Увімкнути докачку зображень по URL';
$_['entry_import_id'] = 'Імпортувати id виробника з файлу';
$_['entry_image_url'] = 'URL зображень';
$_['help_export_file_encoding'] = 'Кодування файлу. Ваш магазин працює у кодуванні UTF-8';
$_['help_export_csv_delimiter'] = 'Роздільник полів формату CSV';
$_['help_import_mode'] = 'У режимах Тільки оновити і Оновити і Додати, пошук йде у базі за ключовим полем, якщо збіг знайдено, то активується режим з оновленням';
$_['help_import_key_field'] = 'Ключове поле, за яким йде пошук співпадінь позицій у базі, і у разі знаходження проводиться оновлення';
$_['help_import_img_download'] = 'Поле із зображенням _IMAGE_ повинно містити URL-посилання для скачування';
$_['help_export_all_manufacturer'] = 'Якщо виробники не обрані - експортує всіх виробників';
$_['help_import_id'] = 'При додаванні нового виробника позиції буде імпортовано поле _ID_ як manufacturer_id. manufacturer_id може бути імпортовано тільки якщо такого ж id немає в базі.';
$_['prop_descr'] = ' 
prop_descr[0]="<p><b>Кодування CSV-файлу</b></p><p>Ваш магазин працює у кодуванні UTF-8, що б уникнути проблем з імпортом та експортом використовуйте кодування UTF-8.</p>";
prop_descr[1]="<p><b>Роздільник полів CSV</b></p><p>Символ, який буде використаний як роздільник для окремих колонок (значень) у CSV-файлі.</p>";
prop_descr[2]="<p><b>Локалізація</b></p><p>Якою мовою будуть експортуватися дані, наприклад назва виробника або опис виробника</p>";
prop_descr[3]="<p><b>Магазини</b></p><p>Якщо магазини не обрані - експортує виробників з усіх магазинів (за замовчуванням).</p>";
prop_descr[4]="<p><b>Виробники</b></p><p>Якщо виробники не обрані - експортує всіх виробників (за замовчуванням).</p>";
prop_descr[5]="<p><b>Режим імпорту</p></b><p><i>Тільки оновити</i> - у цьому режимі здійснюється пошук виробника за ключовим полем і якщо співпадіння знайдено то виробника буде оновлено з CSV-файлу.</p><p><i>Тільки додати</i> - у цьому режимі всі категорії будуть додані як нові з CSV-файлу незалежно від того, чи є вони у базі магазину або ні.</p><p><i>Оновити і Додати</i> - у цьому режимі здійснюється пошук товару за ключовим полем, якщо співпадіння знайдено то категорія буде оновлена з CSV-файлу, якщо співпадінь немає то категорія буде додана як нова.</p>";
prop_descr[6]="<p><b>Ключове поле</b></p><p>Ключове поле, за яким йде пошук співпадіння виробника у базі магазину, використовується у режимах <i>Тільки оновити</i>, <i>Оновити і Додати</i>.</p>";
prop_descr[7]="<p><b>Імпортувати id виробника з файлу</b></p><p>При додаванні нового виробника буде імпортовано поле _ID_ як manufacturer_id, імпорт виконується за умови, що такого manufacturer_id немає в базі магазину і число не перевищує максимально припустимого значення для manufacturer_id.</p>";
prop_descr[8]="<p><b>Увімкнути докачку зображень по URL</b></p><p>Закачує зображення за посиланнями у полях _IMAGE_ та _IMAGES_.</p><p>Посилання повинні мати такий вигляд:<br /><br /> http://www.example.com/dir/image_name.jpg</p>";
prop_descr[9]="<p><b>Роздільник тексту</b></p><p>Символ для обрамлення текстових полів, а так само значень, що містять зарезервовані символи (подвійна лапка, кома, крапка з комою, новий рядок).</p>";
prop_descr[32]="<p><b>URL зображень</b></p><p>Експортує значення _IMAGE_ і _IMAGES_ як посилання на зображення виду http://www.example.com/dir/image_name.jpg</p>";
';
$_['error_permission'] = 'У Вас немає прав для зміни CSV Price Pro import/export!';
$_['error_directory_not_available'] = 'Робоча директорія модуля недоступна для запису або не існує';
$_['error_move_uploaded_file'] = 'Помилка копіювання файлу!';
$_['error_uploaded_file'] = 'Файл не завантажено!';
$_['error_copy_uploaded_file'] = 'Не вдалося скопіювати файл!';
$_['error_export_empty_rows'] = 'Немає даних для експорту!';
$_['_ID_'] = 'id Виробники';
$_['_NAME_'] = 'Найменування';
$_['_SEO_KEYWORD_'] = 'SEO Keyword';
$_['_META_H1_'] = 'HTML-тег H1';
$_['_META_TITLE_'] = 'Meta-тег Title';
$_['_META_KEYWORDS_'] = 'Мета-тег Keywords';
$_['_META_DESCRIPTION_'] = 'Мета-тег Description';
$_['_DESCRIPTION_'] = 'Текст з описом';
$_['_IMAGE_'] = 'Зображення';
$_['_SORT_ORDER_'] = 'Порядок сортування';
$_['_STATUS_'] = 'Статус';
$_['_STORE_ID_'] = 'id магазинів';
$_['_URL_'] = 'URL виробника';
