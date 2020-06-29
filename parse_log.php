<?php
// Читает файлы лога и пишет в бд

require_once __DIR__ . '/db.php';

$db = getDb();

// чистим таблицы лога
pg_query($db, 'truncate log1');
pg_query($db, 'truncate log2');

// читает файлы лога и пишет в бд
write($db, 'log1', ['date', 'time', 'ip', 'referer', 'url'], read(__DIR__.'/resources/log1'));
write($db, 'log2', ['ip', 'browser', 'os'], read(__DIR__.'/resources/log2'));

/**
 * Чтение файла лога
 * Возвращает одномерный массив значений
 * Для больших логов не подходит, надо заменить на чанки
 *
 * @param string $filename Имя файла лога
 * @return array
 */
function read(string $filename): array
{
    $data = file_get_contents($filename);
    $rows = explode("\n", $data);
    $values = [];
    foreach ($rows as $row) {
        $values = array_merge($values, explode('|', $row));
    }
    return $values;
}

/**
 * Пишет в БД
 *
 * @param resource $db Соединение с БД
 * @param string $tablename Имя таблицы
 * @param array $fields Имена полей, которые будут записываться
 * @param array $values Одномерный массив значений
 */
function write($db, string $tablename, array $fields, array $values): void
{
    $fields_str = implode(',', $fields);

    $params = buildParams(count($fields), count($values));

    $sql = "INSERT INTO $tablename ($fields_str) VALUES $params";

    pg_query_params($db, $sql, $values);
}

/**
 * Собирает и возвращает строку с нужным количеством параметров
 *
 * @param int $fieldsCount Количество полей в строке
 * @param int $valuesCount Количество всех значений
 * @return string
 */
function buildParams(int $fieldsCount, int $valuesCount): string
{
    if ($fieldsCount <= 0 || $valuesCount <= 0) {
        throw new InvalidArgumentException('fieldsCount or valuesCount must be grate zero');
    }

    $ret = [];
    for ($i = 0; $i < $valuesCount; $i += $fieldsCount) {
        $line = [];
        for ($j = 1; $j <= $fieldsCount; $j++) {
            $line[] = '$' . ($i + $j);
        }
        $ret[] = '(' . implode(',', $line) . ')';
    }

    return implode(',', $ret);
}