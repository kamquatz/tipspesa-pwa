<?php
include './includes/config.php';
$yesterday = 'mobile-nav__item--active';
?>

<!DOCTYPE html>
<html lang="en">
    <?php include './includes/head.php'; ?>

    <body>
        <nav class="mobile-nav mobile-nav-top">
            <div class="mobile-nav__item">
                <h4 class="mobile-nav__item-content">
                    Yesterday's Predictions Results
                </h4>		
            </div>
        </nav>

        <div class="container py-5">
            <div class="row">
                <div class="col-12 col-sm-offset-4 text-center">
                    <div class="my-progress-bar"></div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center">	
                <?php
                $sql = <<<SQL
                        SELECT `id`,`kickoff`,`home`,`away`,`prediction`,`probability`,`result`, `status`
                            FROM `matches`
                                    WHERE DATE(`kickoff`)=DATE_SUB(CURDATE(),INTERVAL 1 DAY)
                                           AND `posted`=1
                                            ORDER BY `kickoff` DESC, `home`
SQL;

                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->execute();
                    $stmt->bind_result($id, $kickoff, $home, $away, $prediction, $probability, $result, $status);
                    $count = $won = 0;
                    while ($stmt->fetch()) {
                        $count++;
                        $won += ($status == 'LOST' ? 0 : 1);
                        ?>
                        <div class="col-12 col-sm-4 text-center">
                            <small><?php echo $home . ' vs ' . $away; ?></small>
                            <div class="row">
                                <small class="col-5 col-sm-5 text-left"><?php echo $kickoff; ?></small>
                                <small class="col-2 col-sm-2 text-center"><b><?php echo $result; ?></b></small>
                                <small class="col-5 col-sm-5 text-right"><b><?php echo str_replace("*", '<sup class="text-danger">HOT</sup>', $prediction); ?></b></small>
                                <small class="col-5 col-sm-5 text-left"></small>
                                <small class="col-12 col-sm-2 text-center <?php echo strtolower($status); ?>"><b><i class="material-icons"><?php echo $status == 'LOST' ? 'do_not_disturb_on' : 'check_circle'; ?></i></b></small>
                                <small class="col-5 col-sm-5 text-right"><?php echo $probability; ?>%</small>
                            </div>
                        </div>
                        <hr class="my-2" />
                        <?php
                    }
                    $winPerc = $count > 0 ? (100 * $won / $count) : 100;
                    $stmt->close();
                }
                ?>
            </div>
        </div>

        <?php include './includes/navbar-bottom.php'; ?>

        <?php include './includes/scripts.php'; ?>

        <script>
            $(document).ready(function () {
                $(".my-progress-bar")
                        .circularProgress({
                            line_width: 6,
                            color: "<?php echo $winPerc > 75 ? 'green' : ($winPerc > 50 ? 'yellow' : ($winPerc > 25 ? 'orange' : 'red')); ?>",
                            starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
                            percent: 0, // percent starts from
                            percentage: true,
                            text: "<?php echo $won . ' of ' . $count; ?> Predicted Games Won"
                        })
                        .circularProgress("animate", <?php echo $winPerc; ?>, 1000);
            });
        </script>

    </body>
</html>