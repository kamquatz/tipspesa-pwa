<?php
include './includes/config.php';

$matchDay = filter_input(INPUT_POST, 'matchDay') ?? date('Y-m-d');

$sql = <<<SQL
        SELECT `id`,`kickoff`,`home`,`away`,`result`
            FROM `matches`
                    WHERE DATE(`kickoff`)=? AND `prediction` IN ('1','2','1X','X2')
                        ORDER BY `kickoff`, `home`
SQL;

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s",$matchDay);
    $stmt->execute();
    $stmt->bind_result($id, $kickoff, $home, $away,$result);

    $data = array();

    while ($stmt->fetch()) {
        $count++;
        $row = array(
            "id" => $id,
            "kickoff" => $kickoff,
            "home" => $home,
            "away" => $away,
            "result" => $result
        );
        $data[] = $row;
    }

    $stmt->close();

    // Output the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
