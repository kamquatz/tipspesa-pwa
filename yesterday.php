<?php
include './includes/config.php';
$yesterday = 'mobile-nav__item--active';
$page = 'yesterday';
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
                    SELECT 
                        match_id,kickoff,home_team,away_team,prediction, overall_prob,
                        CONCAT(home_results,' - ',away_results) AS results, status
                    FROM matches
                    WHERE DATE(kickoff) = CURRENT_DATE-1
                    ORDER BY kickoff DESC, home_team
SQL;

            try {
                // Establish a connection to PostgreSQL
                $conn = pg_connect($connection_string);
            } catch (Exception $e) {
                // Handle exceptions
                echo $e->getMessage();
            }

            if ($conn) {

                $result = pg_query($conn, $sql);

                if ($result) {

                    $matches = pg_fetch_all($result);
                    $count = $won = 0;

                    // Loop through the result set
                    foreach ($matches as $match) {
                        $count++;
                        $won += ($match['status'] == 'LOST' ? 0 : 1);
            ?>
                        <div class="col-12 col-sm-4 text-center">
                            <small><?php echo $match['home_team'] . ' vs ' . $match['away_team']; ?></small>
                            <div class="row">
                                <small class="col-5 col-sm-5 text-left"><?php echo $match['kickoff']; ?></small>
                                <small class="col-2 col-sm-2 text-center"><b><?php echo $match['results']; ?></b></small>
                                <small class="col-5 col-sm-5 text-right"><b><?php echo str_replace("*", '<sup class="text-danger">HOT</sup>', $match['prediction']); ?></b></small>
                                <small class="col-5 col-sm-5 text-left"></small>
                                <small class="col-12 col-sm-2 text-center <?php echo strtolower($match['status']); ?>"><b><i class="material-icons"><?php echo $match['status'] == 'LOST' ? 'do_not_disturb_on' : 'check_circle'; ?></i></b></small>
                                <small class="col-5 col-sm-5 text-right"><?php echo $match['overall_prob']; ?>%</small>
                            </div>
                        </div>
                        <hr class="my-2" />
            <?php

                    }
                    $winPerc = $count > 0 ? (100 * $won / $count) : 100;
                    // Close the connection
                    pg_close($conn);
                }
            }
            ?>
            
        </div>
    </div>

    <?php include './includes/navbar-bottom.php'; ?>

    <?php include './includes/scripts.php'; ?>

    <script>
        $(document).ready(function() {
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