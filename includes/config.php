<?php
session_start();
$free_games = 100;
$max_games = 100;

$db_host = 'aws-0-eu-central-1.pooler.supabase.com';
$db_port = '5432';
$db_name = 'postgres';
$db_user = 'postgres.rjqbkiuwthhyriaybcpr';
$db_password = 'Mmxsp65$$$Mmxsp65';
$connection_string = "host={$db_host} port={$db_port} dbname={$db_name} user={$db_user} password={$db_password}";

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
