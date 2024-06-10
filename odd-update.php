<?php

include 'includes/config.php';
if (isset($_REQUEST["odd_value"])) {
    $parent_match_id = filter_input(INPUT_POST, 'parent_match_id');
    $market_id = filter_input(INPUT_POST, 'sub_type_id');
    $name = strtoupper(trim(filter_input(INPUT_POST, 'name')));
    $display = strtoupper(trim(filter_input(INPUT_POST, 'display')));
    $odd_key = strtoupper(trim(filter_input(INPUT_POST, 'odd_key')));
    $odd_value = filter_input(INPUT_POST, 'odd_value');
    $outcome_id = filter_input(INPUT_POST, 'outcome_id');

    $sql = <<<SQL
        INSERT INTO `market`(`market_id`,`name`) 
        VALUES(?,?)
        ON DUPLICATE KEY UPDATE `name`=?
SQL;

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sss", $market_id, $name, $name);
        $stmt->execute();
        $stmt->close();
    }

    $sql = <<<SQL
        INSERT INTO `odd`(`parent_match_id`,`market_id`,`display`,`odd_key`,`odd_value`,`outcome_id`) 
        VALUES(?,?,?,?,?,?)
SQL;

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssssds", $parent_match_id, $market_id, $display, $odd_key, $odd_value, $outcome_id);
        $stmt->execute();
        $stmt->close();
    }
}
