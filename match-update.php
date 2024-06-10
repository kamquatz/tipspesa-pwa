<?php

include 'includes/config.php';
if (isset($_REQUEST["update_match"])) {
    $kickoff = trim($_REQUEST["kickoff"]);
    $home = strtoupper(trim($_REQUEST["home"]));
    $away = strtoupper(trim($_REQUEST["away"]));
    $prediction = strtoupper(trim($_REQUEST["prediction"]));
    $probability = $_REQUEST["probability"];
    $interval = $_REQUEST["interval"];
    $odd = $_REQUEST["odd"];
    $result = $_REQUEST["result"];
    $status = $_REQUEST["status"];
    $sms_id = $_REQUEST["sms_id"];

    $sql = <<<SQL
        INSERT INTO `matches`(`sms_id`,`match_day`,`match_time`,`kickoff`,`home`,`away`,`prediction`,`probability`,`odd`,`result`,`status`) 
        VALUES(?,DATE(?),TIME(?),DATE_ADD(?, INTERVAL ? HOUR),?,?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE `sms_id`=?, `match_day`=DATE(?),`match_time`=TIME(?), `home`=?,`away`=?,`prediction`=?,`probability`=?,`odd`=?,`result`=?,`status`=?
SQL;

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param(
            "ssssisssddssssssssddss",
            $sms_id,
            $kickoff,
            $kickoff,
            $kickoff,
            $interval,
            $home,
            $away,
            $prediction,
            $probability,
            $odd,
            $result,
            $status,
            $sms_id,
            $kickoff,
            $kickoff,
            $home,
            $away,
            $prediction,
            $probability,
            $odd,
            $result,
            $status
        );
        $stmt->execute();
        $stmt->close();

        $data = array(
            "sms_id" => $sms_id,
            "kickoff" => $kickoff,
            "home" => $home,
            "away" => $away,
            "prediction" => $prediction,
            "odd" => $odd,
            "result" => $result
        );

        // Output the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

if (isset($_REQUEST["update_sportpesa_match"])) {
    $sms_id = filter_input(INPUT_POST, 'sms_id');
    $kickoff = filter_input(INPUT_POST, 'kickoff');
    $home = "%" . strtoupper(trim(filter_input(INPUT_POST, 'home'))) . "%";
    $away = "%" . strtoupper(trim(filter_input(INPUT_POST, 'away'))) . "%";

    $sql = <<<SQL
        UPDATE`matches`
        SET `sms_id`=?,`kickoff`=?,`match_day`=DATE(?),`match_time`=TIME(?)
        WHERE (`home` LIKE ? OR `away` LIKE ?)
        AND DATE(`kickoff`)=CURDATE()
SQL;

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("isssss", $sms_id, $kickoff, $kickoff, $kickoff, $home, $away);
        $stmt->execute();
        $stmt->close();
        echo 'success';
    }
}


if (isset($_REQUEST["update_stream"])) {
    $kickoff = date('Y-m-d H:i:s', strtotime(trim(filter_input(INPUT_POST, 'kickoff'))));
    $match = strtoupper(trim(filter_input(INPUT_POST, 'match')));
    $link = strtolower(trim(filter_input(INPUT_POST, 'link')));
    $interval = filter_input(INPUT_POST, 'interval');

    $sql = <<<SQL
        INSERT INTO `streams`(`kickoff`,`match`,`link`) 
        VALUES(DATE_ADD(?, INTERVAL ? HOUR),?,?)
        ON DUPLICATE KEY UPDATE `kickoff`=DATE_ADD(?, INTERVAL ? HOUR),`match`=?,`link`=?
SQL;

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sisssiss", $kickoff, $interval, $match, $link, $kickoff, $interval, $match, $link);
        $stmt->execute();
        $stmt->close();
        echo 'success';
    }
}
