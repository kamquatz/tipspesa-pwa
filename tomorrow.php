<?php
include './includes/config.php';
$tomorrow = 'mobile-nav__item--active';
$page = 'tomorrow';
?>

<!DOCTYPE html>
<html lang="en">
    <?php include './includes/head.php'; ?>

    <body>
        <nav class="mobile-nav mobile-nav-top">
            <div class="mobile-nav__item">
                <h4 class="mobile-nav__item-content">
                    Tomorrow Games Predictions
                </h4>		
            </div>
        </nav>

        <div class="container py-5">
            <div class="row justify-content-center">						

                <?php
                include './includes/premium-check.php';
                $sql = <<<SQL
                    SELECT `id`,`sms_id`,`kickoff`,`home`,`away`,`prediction`,`probability`,`odd`,`result`,`status`
                        FROM `matches`
                            WHERE DATE(`kickoff`)=DATE_ADD(CURDATE(),INTERVAL 1 DAY)
                                ORDER BY `kickoff`, `home`
SQL;
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->execute();
                    $stmt->bind_result($id, $sms_id, $kickoff, $home, $away, $prediction, $probability, $odd, $result, $status);
                    $count = 0;
                    while ($stmt->fetch()) {
                        $hot = (strpos($prediction, '*') !== false) ? '<sup class="text-danger"><blink>HOT</blink></sup>' : '***';
                        $count++;
                        ?>
                        <div class="col-12 col-sm-3">
                            <div class="card box-shadow my-2">
                                <div class="card-header text-center">
                                    <?php echo $count <= $free_games ? ($home . ' vs ' . $away) : '<a href="premium">*** GET PREMIUM TO VIEW ***</a>'; ?>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <small class="col-5 col-sm-7 text-left"><?php echo $kickoff; ?></small>
                                        <small class="col-2 col-sm-2 text-center"><b><?php echo $probability; ?>%</b></small>
                                        <small class="col-5 col-sm-3 text-right"><b><?php echo $prediction; ?></b><br />
                                            <sub class="col-12 col-sm-12 text-center <?php echo strtolower($status); ?>"><b><i class="material-icons"><?php echo $status == 'LOST' ? 'do_not_disturb_on' : ($status == 'WON' ? 'check_circle' : ''); ?></i></b></sub>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>				
                        <?php
                    }
                    $stmt->close();
                }
                ?>

            </div>
        </div>

        <?php if ($count == 0) { ?>
            <div class="container py-5">
                <div class="row">
                    <div class="col-12 col-sm-offset-4 text-center">
                        <div class="my-progress-bar"></div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php include './includes/navbar-bottom.php'; ?>
        <?php include './includes/scripts.php'; ?>
        <?php include './includes/scripts-games.php'; ?>
    </body>
</html>