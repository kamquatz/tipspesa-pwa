<?php
session_start();
$free_games = 100;
$max_games = 100;

$db_host = 'aws-0-eu-central-1.pooler.supabase.com';
$db_port = '5432';
$db_name = 'postgres';
$db_user = 'postgres.rjqbkiuwthhyriaybcpr';
$db_password = 'Mmxsp65$$$Mmxsp65';

// Establish a connection to the PostgreSQL database
$dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;user=$db_user;password=$db_password";
try {
    $conn = new PDO($dsn);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

function getStatus($prediction, $result) {
    $status = '';

    if (preg_match('/\d/', $result)) {
        $results = explode(" - ", $result);
        switch ($prediction) {
            case 'OV1.5':
                $status = ((int) $results[0] + (int) $results[1]) > 1 ? 'WON' : 'LOST';
                break;
            default:
                // Handle other cases here
                break;
        }
    } else {
        // Handle error case here
    }

    return $status;
}
