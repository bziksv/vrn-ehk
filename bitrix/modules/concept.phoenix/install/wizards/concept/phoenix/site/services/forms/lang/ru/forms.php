<?
$MESS['EVENT_NAME.PHOENIX_USER_INFO'] = 'Сообщения Феникс';
$MESS['EVENT_DESCRIPTION.PHOENIX_USER_INFO'] = '#NAME# - Имя
#PHONE# - Телефон
#EMAIL# - E-Mail пользователя
#EMAIL_TO# - E-mail администратора (получатель письма)
#TEXT# - Сообщение
#MORE_INFO# - Доп. информация
#CHECK_VALUE# - Выбранные параметры
#ADDRESS# - Адрес
#COUNT# - Количество
#DATE# - Дата
#CODE# - URL страницы
#URL# - адрес страницы
#PAGE_NAME# - название страницы
#MESSAGE# - все заполненные на сайте сообщения
';
$MESS['TEMPLATE_SUBJECT.PHOENIX_USER_INFO'] = 'Заявка с сайта «#PAGE_NAME#»';
$MESS['TEMPLATE_MESSAGE.PHOENIX_USER_INFO'] = '<b>Поздравляем, Вам пришла заявка! </b> <br><br>
Она пришла с сайта — «#PAGE_NAME#» из блока «#HEADER#» из формы «#FORM_NAME#»<br>
Полный адрес страницы: #URL# <br>
<br>
<br>
-----<i> Информация из формы </i>----- 
<br><br>
#MESSAGE#';



/****************************/
$MESS['EVENT_NAME.PHOENIX_FOR_USER'] = 'Сообщения для пользователя Феникса';
$MESS['EVENT_DESCRIPTION.PHOENIX_FOR_USER'] = '#THEME# - Тема сообщения
#MESSAGE_FOR_USER# - Сообщение
#EMAIL_TO# - E-mail получателя
';
$MESS['TEMPLATE_SUBJECT.PHOENIX_FOR_USER'] = '#THEME#';
$MESS['TEMPLATE_MESSAGE.PHOENIX_FOR_USER'] = '#MESSAGE_FOR_USER#';

/****************************/
$MESS['EVENT_NAME.PHOENIX_ONECLICK_SEND'] = 'Феникс Заказ в 1 клик';
$MESS['EVENT_DESCRIPTION.PHOENIX_ONECLICK_SEND'] = '#SITE_NAME# - наименование сайта
#NAME_USER# - имя пользователя
#PHONE_USER# - телефон пользователя
#EMAIL_USER# - e-mail пользователя
#BASKET_TABLE# - список заказанных товаров
#FORM_INFO# - вся информация из полей формы
#TOTAL_INFO# - вся итоговая информация из корзины
#TOTAL_SUM# - сумма заказа
#DISCOUNT# - скидка
#URL# - адрес сайта
#URL_ORDER# - адрес заказа в административной части
#ORDER_ID# - номер заказа
';
$MESS['TEMPLATE_SUBJECT.PHOENIX_ONECLICK_SEND'] = '#THEME#';
$MESS['TEMPLATE_MESSAGE.PHOENIX_ONECLICK_SEND'] = '#MESSAGE#';



/****************************/
$MESS['EVENT_NAME.PHOENIX_SEND_REG_SUCCESS'] = 'Письмо пользователю после успешной регистрации';
$MESS['EVENT_DESCRIPTION.PHOENIX_SEND_REG_SUCCESS'] = '#EMAIL_FROM# - Эл. адрес отправителя
#EMAIL_TO# - Эл. адрес получателя
#SITENAME# - Название сайта из настроек сайта Феникс
#EMAIL# - Эл. адрес пользователя
#NAME# - Имя пользователя
#LOGIN# - Логин пользователя (такой же как эл. адрес)
#PASSWORD# - Пароль пользователя
#SITE_URL# - Адрес сайта
';
$MESS['TEMPLATE_SUBJECT.PHOENIX_SEND_REG_SUCCESS'] = 'Вы успешно зарегистрированы';
$MESS['TEMPLATE_MESSAGE.PHOENIX_SEND_REG_SUCCESS'] = 'Поздравляем с успешной регистрацией на сайте <a href="#SITE_URL#">#SITENAME#</a>!<br><br>
Ваши данные для доступа к личному кабинету:<br>
Логин: #LOGIN#<br>
Пароль: #PASSWORD#<br>';


/****************************/
$MESS['EVENT_NAME.PHOENIX_USER_PASS_REQUEST'] = 'Запрос на смену пароля';
$MESS['EVENT_DESCRIPTION.PHOENIX_USER_PASS_REQUEST'] = '#LINK_CHANGE_PASS# - Ссылка на страницу смены пароля
#SITE_NAME# - Название сайта из настроек сайта Феникс
#EMAIL_FROM# - Эл. почта отправителя из настроек сайта Феникс
#EMAIL_TO# - Эл. почта пользователя
';
$MESS['TEMPLATE_SUBJECT.PHOENIX_USER_PASS_REQUEST'] = '#SITENAME#: Запрос на смену пароля';
$MESS['TEMPLATE_MESSAGE.PHOENIX_USER_PASS_REQUEST'] = 'Информационное сообщение сайта #SITE_NAME#<br>
------------------------------------------<br>
<br>
Для смены пароля перейдите по следующей ссылке:<br>
#LINK_CHANGE_PASS#<br>
<br>';


/****************************/
$MESS['EVENT_NAME.PHOENIX_CHANGE_PASSWORD_SUCCESS'] = 'Сообщение об успешном изменении пароля';
$MESS['EVENT_DESCRIPTION.PHOENIX_CHANGE_PASSWORD_SUCCESS'] = '#EMAIL_FROM# - Эл. почта отправителя
#EMAIL_TO# - Эл. почта получателя
#SITENAME# - Название сайта из настроек сайта Феникс
#EMAIL# - Эл. почта пользователя
#NAME# - Имя пользователя
#LOGIN# - Логин пользователя (такой же как эл. почта)
#PASSWORD# - Пароль пользователя
#SITE_URL# - Адрес сайта
';
$MESS['TEMPLATE_SUBJECT.PHOENIX_CHANGE_PASSWORD_SUCCESS'] = '#SITENAME#: Пароль успешно изменён';
$MESS['TEMPLATE_MESSAGE.PHOENIX_CHANGE_PASSWORD_SUCCESS'] = 'Пароль успешно изменён на сайте <a href="#SITE_URL#">#SITENAME#</a>!<br><br>
Ваши данные для доступа к личному кабинету:<br>
Логин: #LOGIN#<br>
Пароль: #PASSWORD#<br>';


/****************************/
$MESS['EVENT_NAME.PHOENIX_NEW_REVIEW_PRODUCT'] = 'Новый отзыв к товару';
$MESS['EVENT_DESCRIPTION.PHOENIX_NEW_REVIEW_PRODUCT'] = '#SITE_URL# - адрес сайта
#EMAIL_FROM# - адрес отправителя
#EMAIL_TO# - адрес получателя
#PRODUCT_NAME# - название товара
#REVIEW_URL# - ссылка на редактирование отзыва
';
$MESS['TEMPLATE_SUBJECT.PHOENIX_NEW_REVIEW_PRODUCT'] = 'Новый отзыв к товару';
$MESS['TEMPLATE_MESSAGE.PHOENIX_NEW_REVIEW_PRODUCT'] = 'На Вашем сайте #SITE_URL# оставлен новый отзыв к товару #PRODUCT_NAME#<br/><br/>
<a href="#REVIEW_URL#">Перейти к просмотру/редактированию отзыва</a>';


/****************************/
$MESS['EVENT_NAME.PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED'] = 'Письмо с информацией о товарах после оплаты';
$MESS['EVENT_DESCRIPTION.PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED'] = '';
$MESS['TEMPLATE_SUBJECT.PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED'] = '#THEME#';
$MESS['TEMPLATE_MESSAGE.PHOENIX_INFO_ORDER_ITEMS_FOR_USER_AFTER_PAYED'] = '#MESSAGE#';


/****************************/
$MESS['EVENT_NAME.PHOENIX_NEW_COMMENTS'] = 'Сообщение о новом комментарии';
$MESS['EVENT_DESCRIPTION.PHOENIX_NEW_COMMENTS'] = '#EMAIL_FROM# - адрес отправителя
#COMMENT_URL# - ссылка на страницу редактирования комментария
#ELEMENT_NAME# - наименование элемента 
#ELEMENT_URL# - ссылка на страницу элемента
#EMAIL_TO# - email для заявки
#SITE_URL# - Адрес сайта';
$MESS['TEMPLATE_SUBJECT.PHOENIX_NEW_COMMENTS'] = 'Новый комментарий к #TYPE_ELEMENT#';
$MESS['TEMPLATE_MESSAGE.PHOENIX_NEW_COMMENTS'] = 'На Вашем сайте #SITE_URL# оставлен новый комментарий на странице «#ELEMENT_NAME#»<br>
<br>
#COMMENT_CONTENT#<br><br><br>
 <a href="#COMMENT_URL#">Перейти к просмотру/редактированию комментария</a>';

?>