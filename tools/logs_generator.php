<?php
/**
 * Генератор файлов двух логов
 * ../log1 (дата|время|ip|url откуда пришёл|url куда пришёл)
 * ../log2 (ip|браузер|ос)
 */

$uniq_ip = [];

genLog1(__DIR__ . '/../resources/log1', 20);
genLog2(__DIR__ . '/../resources/log2');

function genLog1(string $filename, int $rowsCount): void
{
    $ret = [];

    $date = new DateTime();

    for ($i = 0; $i < $rowsCount; $i++) {
        $date->modify('+42 min');
        $ret[] = implode('|', [
            $date->format('Y-m-d'),
            $date->format('H:i:s'),
            getIp(),
            getHost().'/'.getUrl(),
            'http://host'.rand(1,10).'.ru/'
        ]);
    }

    file_put_contents($filename, implode("\n", $ret));
}

function genLog2($filename): void
{
    global $uniq_ip;

    $browsers = ['Chrome', 'Opera', 'Firefox', 'Edge', 'Vivaldi'];
    $os = ['Debian', 'Windows', 'Ubuntu', 'MacOS', 'QNX'];

    $ret = [];
    foreach ($uniq_ip as $ip) {
        $ret[] = implode('|', [
            $ip,
            $browsers[array_rand($browsers)],
            $os[array_rand($os)]
        ]);
    }

    file_put_contents($filename, implode("\n", $ret));
}

function getHost(): string
{
    $http_vals = ['http', 'https'];
    $root_vals = ['.com', '.ru'];

    $http = $http_vals[array_rand($http_vals)];
    $root = $root_vals[array_rand($root_vals)];

    return $http . '://' . getWord() . $root;
}

function getUrl(int $min = 2, int $max = 3): string
{
    $ret = [];
    for ($i = 0; $i < rand($min, $max); $i++) {
        $ret[] = getWord();
    }
    return implode('/', $ret);
}

function getWord(): string
{
    $max_word_len = 4;

    $data = "t was in July, 1805, and the speaker was the well-known Anna Pavlovna Scherer, maid of honor and favorite of the Empress Marya Fedorovna. With these words she greeted Prince Vasili Kuragin, a man of high rank and importance, who was the first to arrive at her reception. Anna Pavlovna had had a cough for some days. She was, as she said, suffering from la grippe; grippe being then a new word in
St. Petersburg, used only by the elite";

    $strings = explode (' ', preg_replace('#\W#', ' ', $data));

    $ret = [];
    foreach ($strings as $word) {
        $word = strtolower($word);
        if (strlen($word) >= $max_word_len) {
            $ret[$word] = $word;
        }
    }

    return array_rand($ret);
}

function getIp(): string
{
    global $uniq_ip;

    if (!empty($uniq_ip) && rand(0, 1) === 0) {
        return array_rand($uniq_ip);
    }

    $ret = [];
    for ($i = 0; $i < 4; $i++) {
        $ret[] = rand(10, 255);
    }

    $ip = implode('.', $ret);
    $uniq_ip[$ip] = $ip;

    return $ip;
}