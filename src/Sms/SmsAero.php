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
            return $res['data']['balance'];
        } else {
            echo 'Ошибка: ' . $res['message'];
        }
    }

    public function getMessages()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->login . ':' . $this->key);
        curl_setopt($ch, CURLOPT_ENCODING, 'application/json');
        curl_setopt($ch, CURLOPT_URL, 'https://email:api_key@gate.smsaero.ru/v2/sms/list');

        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        if ($res['success']) {
            return $res['data'];
        } else {
            return false;
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
                    return $res;
                }
            }
        }
    }
}