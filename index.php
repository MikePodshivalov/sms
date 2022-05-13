<?php

use App\Sms\SmsAero;

require_once __DIR__ . '/vendor/autoload.php';

$config = require 'config.php';
$sms = new SmsAero($config['email'], $config['key'], $config['from']);

//отправка сообщения
if (isset($_POST['phone']) && isset($_POST['message'])) {
    $result = $sms->send($_POST['phone'], $_POST['message']);
}

//получаем список сообщений
$messages = $sms->getMessages();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js" type="text/javascript"></script>
    <title>Отправка sms</title>
</head>
<body>
    <div class="content">
        <div class="row">
            <p>Ваш баланс: <?php echo $sms->getBalance() ?></p>
        </div>
        <form id="message-send" action="index.php" method="post">
            <label for="phone">Телефон:</label>
            <div class="row">
                <input type="text" name="phone" id="phone" placeholder="Телефон" class="phone_mask">
            </div>

            <label for="message">Сообщение:</label>
            <div class="row">
                <textarea name="message" id="message" placeholder="Сообщение"></textarea>
            </div>

            <div class="row">
                <button type="submit" id="button-send">Отправить sms</button>
            </div>
        </form>
    </div>

    <table>
        <caption>Отправленные сообщения</caption>
        <tr>
            <th>Кому</th>
            <th>Сообщение</th>
            <th>Время отправки</th>
        </tr>
        <?php foreach ($messages as $key => $message) {?>
            <?php if (is_int($key)) {?>
                <tr>
                    <td><?php echo $message['number'] ?? ''?></td>
                    <td><?php echo $message['text'] ?? ''?></td>
                    <td><?php echo date('m/d/Y h:i:s',$message['dateSend'])?></td>
                </tr>
            <?php }
        }?>
    </table>
    <script>
        $(".phone_mask").mask("+7(999)999-99-99");
    </script>
</body>
</html>
