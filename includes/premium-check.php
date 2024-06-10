<?php
if (isset($_REQUEST["hyxrt"])) {
    $tkn = filter_input(INPUT_GET, 'hyxrt');
    $sql = 'SELECT `id`
            FROM `premium`
            WHERE SHA2(`phone`,512)=? AND DATE(`expires_at`)>CURDATE()';

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $tkn);
        $stmt->execute();
        $stmt->bind_result($id);
        while ($stmt->fetch()) {
            $free_games = $max_games;
        }
        $stmt->close();
        ?>
        <?php
    }
}