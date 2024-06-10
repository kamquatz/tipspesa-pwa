<?php

include './includes/config.php';

$matchDay = '%'.filter_input(INPUT_GET, 'matchDay') ?? date('Y-m-d').'%';
$market = '%'.filter_input(INPUT_GET, 'market') ?? ''.'%';
$limit = filter_input(INPUT_GET, 'limit') ?? 10;

$sql = <<<SQL
        SELECT `sms_id`,DATE(`kickoff`),TIME(`kickoff`),`home`,`away`,`prediction`,`odd`,`probability`,`result`, `status`
            FROM `matches`
                WHERE DATE(`kickoff`) LIKE ? AND `prediction` LIKE ?
                        ORDER BY `kickoff` DESC, `home` ASC
                            LIMIT ?
SQL;

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("ssi",$matchDay, $market,$limit);
    $stmt->execute();
    $stmt->bind_result($sms_id, $kickoff_date, $kickoff_time, $home, $away, $prediction, $odd, $probability, $result, $status);

    $data = array();

    while ($stmt->fetch()) {
        $count++;
        $row = array(
            "sms_id" => $sms_id,
            "kickoff_date" => $kickoff_date,
            "kickoff_time" => $kickoff_time,
            "home" => $home,
            "away" => $away,
            "odd" => $odd,
            "prediction" => $prediction,
            "probability" => $probability,
            "result" => $result,            
            "status" => $status
        );
        $data[] = $row;
    }

    $stmt->close();

    // Output the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}

