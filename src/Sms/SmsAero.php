<?php

namespace App\Sms;

class SmsAero
{
    public function __construct(private string $login, private string $key, private string $from)
    {}

    public function getBalance()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->key);
        curl_setopt($ch, CURLOPT_ENCODING, 'application/json');
        curl_setopt($ch, CURLOPT_URL, 'https://gate.smsaero.ru/v2/balance');

        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        if ($res['success']) {
            echo $res['data']['balance'];
        } else {
            echo 'Ошибка: ' . $res['message'];
        }
    }

    public function send($number, $text)
    {
        if (!empty($number)) {
            $numbers = explode(',', $number);
            foreach ($numbers as $row) {
                $row = preg_replace('/[^0-9]/', '', $row);
                if (!empty($row)) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->key);
                    curl_setopt($ch, CURLOPT_URL, 'https://gate.smsaero.ru/v2/sms/send?number=' . $row . '&text=' . urlencode($text) . '&sign=' . $this->from);
                    $res = curl_exec($ch);
                    curl_close($ch);

                    // Лог для отладки
                    $log = date('Y-m-d H:i:s') . "\r\n";
                    $log .= $res;
                    file_put_contents(__DIR__ . '/log_sms.txt', $log . "\r\n\r\n", FILE_APPEND);
                }
            }
        }
    }
}