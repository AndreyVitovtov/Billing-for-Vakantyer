EMAIL

Настройка аккаунта Google для отправки через SMTP
Чтобы Google разрешил отправку писем через SMTP нужно произвести настройку учетной записи, иначе в логе PHPMailer может
появится сообщение об ошибке: "SMTP ERROR: Password command failed: 534-5.7.14 Please log in via your web browser and
then try again" (свойство $mail->SMTPDebug должно быть установлено в 1 или 2).

Включить доступ для менее безопасных устройств (именно такими Гугл считает сторонние SMTP сервера) по ссылке:
myaccount.google.com. Также письмо со ссылкой на изменение этой настройки придет после первого безрезультатного
подключения.
Просмотреть недавно используемые устройства: security.google.com.
Также может помочь эта ссылка: accounts.google.com.
Следует учитывать, что Google разрешает отправлять через свои почтовые аккаунты Gmail не более 99 писем в сутки или до
2000 (500 - пробный аккаунт), с использованием платной версии почтового приложения G Suite. При превышении ограничения
на отправку писем по SMTP пользователь теряет возможность отправлять новые письма на 24 часа, но доступ к своему
аккаунту остается. Поскольку правила могут измениться в будущем, то посмотреть ограничения можно на support.google.com.

Google будет автоматически изменять поле "От" любого сообщения, отправленного через SMTP на адрес электронной почты,
записанный в настройках аккаунта. Чтобы изменить адрес "от" в настройках аккаунта нужно перейти на вкладку "Аккаунты и
импорт" и нажать на "Добавить другой адрес электронной почты", после чего назначить его используемым по умолчанию.


Бонус:
Bonus - https://vakantyer.az/bonus/accrue/{userId}/1

Оплата картой:
100 - https://vakantyer.az/payment/pay/{userId}/2
50 - https://vakantyer.az/payment/pay/{userId}/3
15 - https://vakantyer.az/payment/pay/{userId}/4
5 - https://vakantyer.az/payment/pay/{userId}/5

Оплата "банк":
100 - https://vakantyer.az/payment/bank/{userId}/2
50 - https://vakantyer.az/payment/bank/{userId}/3
15 - https://vakantyer.az/payment/bank/{userId}/4
5 - https://vakantyer.az/payment/bank/{userId}/5


https://docs.google.com/document/d/1nlK-tHBIH0d2bBHXdS2crja5hv9WhhU7aICHw6YB-18/edit#heading=h.gjdgxs