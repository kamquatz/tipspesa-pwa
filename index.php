<?php
include './includes/config.php';
$today = 'mobile-nav__item--active';
$page = '';
$count = 0;

?>

<!DOCTYPE html>
<html lang="en">
<?php include './includes/head.php'; ?>

<body>
    <nav class="mobile-nav mobile-nav-top">
        <div class="mobile-nav__item">
            <h4 class="mobile-nav__item-content">
                Today Games Predictions
            </h4>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">

            <?php
            include './includes/premium-check.php';
            $sql = <<<SQL
                    SELECT 
                        match_id,kickoff,home_team,away_team,prediction,odd,match_url,
                        average_goals_home,average_goals_away,
                        over_0_5_home_perc,over_0_5_away_perc,
                        over_1_5_home_perc,over_1_5_away_perc,
                        over_2_5_home_perc,over_2_5_away_perc,
                        over_3_5_home_perc,over_3_5_away_perc,overall_prob,
                        CONCAT(home_results,' - ',away_results) AS results, status
                    FROM matches
                    WHERE DATE(kickoff)=CURRENT_DATE 
                    ORDER BY kickoff, home_team
SQL;

            try {
                // Establish a connection to PostgreSQL
                $conn = pg_connect($connection_string);

                if (!$conn) {
                    throw new Exception("Connection failed: " . pg_last_error());
                }

                $result = pg_query($conn, $sql);

                if (!$result) {
                    throw new Exception("Query execution failed: " . pg_last_error($conn));
                }


                $matches = pg_fetch_all($result);
                $count = 0;

                // Loop through the result set
                // Loop through the result set
                foreach ($matches as $match) {
                    $count++;
            ?>
                    <div class="col-12 col-sm-3">
                        <div class="card box-shadow my-2">
                            <div class="card-header text-center">
                                <b>
                                    <?php echo ($count <= $free_games) ? ($match['home_team'] . ' vs ' . $match['away_team']) : '<a href="premium">*** GET PREMIUM TO VIEW ***</a>'; ?>
                                </b>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <small class="col-5 col-sm-7 text-left"><?php echo $match['kickoff']; ?></small>
                                    <small class="col-2 col-sm-2 text-center"><b>
                                            <?php echo strpos($match['status'], "'") ? '<span class="blinking-text text-success">LIVE</span><br/>' . $match['results'] : $match['overall_prob'] . '%'; ?></b>
                                    </small>
                                    <small class="col-5 col-sm-3 text-right"><b><?php echo $match['prediction']; ?></b><br />
                                        <sub class="col-12 col-sm-12 text-center <?php echo strtolower($match['status']); ?>"><b><i class="material-icons"><?php echo $match['status'] == 'LOST' ? 'do_not_disturb_on' : ($match['status'] == 'WON' ? 'check_circle' : ''); ?></i>
                                                <?php echo strpos($match['status'], "'") ? $match['status'] : ''; ?></b></sub>
                                    </small>
                                </div>
                                <div class="text-center">
                                    <small>
                                        <?php echo $match['home_team'] . ' vs ' . $match['away_team']; ?> Statistics
                                    </small>
                                </div>
                                <div class="row">
                                    <b class="col-3 col-sm-3 text-left">Total Goals</b>
                                    <b class="col-3 col-sm-3 text-center">Home</b>
                                    <b class="col-3 col-sm-3 text-center">Away</b>
                                    <b class="col-3 col-sm-3 text-center">Average</b>
                                </div>
                                <!-- <div class="row">
                                    <small class="col-3 col-sm-3 text-left">Total Goals</small>
                                    <small class="col-3 col-sm-3 text-center"><?php echo $match['average_goals_home']; ?></small>
                                    <small class="col-3 col-sm-3 text-center"><?php echo $match['average_goals_away']; ?></small>
                                    <small class="col-3 col-sm-3 text-center"><?php echo ($match['average_goals_home'] + $match['average_goals_away']) / 2; ?></small>
                                </div> -->
                                <div class="row">
                                    <small class="col-3 col-sm-3 text-left" style="background: lightblue">Over 0.5</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_0_5_home_perc'])) ?>"><?php echo $match['over_0_5_home_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color($match['over_0_5_away_perc']) ?>"><?php echo $match['over_0_5_away_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_0_5_home_perc'] + $match['over_0_5_away_perc']) / 2) ?>"><?php echo ($match['over_0_5_home_perc'] + $match['over_0_5_away_perc']) / 2; ?>%</small>
                                </div>
                                <div class="row">
                                    <small class="col-3 col-sm-3 text-left" style="background: powderblue">Over 1.5</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_1_5_home_perc'])) ?>"><?php echo $match['over_1_5_home_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color($match['over_1_5_away_perc']) ?>"><?php echo $match['over_1_5_away_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_1_5_home_perc'] + $match['over_1_5_away_perc']) / 2) ?>"><?php echo ($match['over_1_5_home_perc'] + $match['over_1_5_away_perc']) / 2; ?>%</small>
                                </div>
                                <div class="row">
                                    <small class="col-3 col-sm-3 text-left" style="background: lightskyblue">Over 2.5</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_2_5_home_perc'])) ?>"><?php echo $match['over_2_5_home_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color($match['over_2_5_away_perc']) ?>"><?php echo $match['over_2_5_away_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_2_5_home_perc'] + $match['over_2_5_away_perc']) / 2) ?>"><?php echo ($match['over_2_5_home_perc'] + $match['over_2_5_away_perc']) / 2; ?>%</small>
                                </div>
                                <div class="row">
                                    <small class="col-3 col-sm-3 text-left" style="background: skyblue">Over 3.5</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_3_5_home_perc'])) ?>"><?php echo $match['over_3_5_home_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color($match['over_3_5_away_perc']) ?>"><?php echo $match['over_3_5_away_perc']; ?>%</small>
                                    <small class="col-3 col-sm-3 text-center" style="background: <?php echo get_background_color(($match['over_3_5_home_perc'] + $match['over_3_5_away_perc']) / 2) ?>"><?php echo ($match['over_3_5_home_perc'] + $match['over_3_5_away_perc']) / 2; ?>%</small>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
                // Close the connection
                pg_close($conn);
            } catch (Exception $e) {
                // Handle exceptions
                echo "Error: " . $e->getMessage();
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
    <script>
        setInterval(() => {
            const elements = document.querySelectorAll('.blinking-text');
            elements.forEach(element => {
                element.style.visibility = (element.style.visibility === 'hidden') ? 'visible' : 'hidden';
            });
        }, 1000);
    </script>

    <?php include './includes/navbar-bottom.php'; ?>
    <?php include './includes/scripts.php'; ?>
    <?php include './includes/scripts-games.php'; ?>
</body>

</html>