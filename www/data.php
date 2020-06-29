<?php

require_once __DIR__ . '/../db.php';

$db = getDb();

$field = $_POST['filter'][0]['field'] ?? null;
$ip_value = $_POST['filter'][0]['data']['value'] ?? null;

if ($field === 'ip' && $ip_value !== null) {
    $where = ' WHERE TEXT(ip) LIKE $1';
} else {
    $where = '';
}

$sql = "
SELECT 
    ip, 
    os, 
    browser,
    (SELECT referer FROM log1 WHERE log1.ip=l2.ip ORDER BY date ASC, time ASC LIMIT 1) AS referer,
    (SELECT url FROM log1 WHERE log1.ip=l2.ip ORDER BY date DESC, time DESC LIMIT 1) AS url,
    (SELECT COUNT(DISTINCT url) FROM log1 WHERE log1.ip=l2.ip) AS count
FROM
    log2 AS l2
$where    
";

$ret = ['log' => []];
$total = 0;
$res = empty($where) ? pg_query($db, $sql) : pg_query_params($db, $sql, ['%'.$ip_value.'%']);
while ($row = pg_fetch_assoc($res)) {
    $ret['log'][] = $row;
    $total++;
}
pg_free_result($res);

$ret['total'] = $total;

echo json_encode($ret);