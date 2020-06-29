<?php

/**
 * Настройка соедения с бд
 *
 * @return resource
 */
function getDb()
{
    $config = [
        'user=sxwtpuff',
        'password=KDhmH-LYmCLLSjJWGNmI9-Nygo9bWBME',
        'host=balarama.db.elephantsql.com',
    ];

    return pg_connect(implode(' ', $config));
}