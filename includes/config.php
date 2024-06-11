<?php
session_start();
$free_games = 100;
$max_games = 100;

$db_host = 'aws-0-eu-central-1.pooler.supabase.com';
$db_port = '5432';
$db_name = 'postgres';
$db_user = 'postgres.rjqbkiuwthhyriaybcpr';
$db_password = 'Mmxsp65$$$Mmxsp65';
$connection_string = "host={$db_host} port={$db_port} dbname={$db_name} user={$db_name} password={$db_password}";

try {
    $conn = pg_connect($connection_string);
} catch (Exception $e) {
    die('Connection failed: ' . $e->getMessage());
}


// Establish a connection to the PostgreSQL database
$dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;user=$db_user;password=$db_password";


$conn = pg_connect($connection_string);
try {
    $conn = new PDO($dsn);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

function getStatus($prediction, $result)
{
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


function get_background_color($perc)
{
    if ($perc == 100) {
        return 'green';
    } elseif ($perc >= 90) {
        return 'limegreen';
    } elseif ($perc >= 80) {
        return 'lime';
    } elseif ($perc >= 70) {
        return 'lawngreen';
    } elseif ($perc >= 60) {
        return 'yellow';
    } elseif ($perc >= 50) {
        return 'orange';
    } elseif ($perc >= 40) {
        return 'darkorange';
    } elseif ($perc >= 30) {
        return 'tomato';
    } elseif ($perc >= 20) {
        return 'orangered';
    } else {
        return 'red';
    }
}
