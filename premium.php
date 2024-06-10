<script>
    $redirect = false;
</script>
<?php
include './includes/config.php';
$premium = 'mobile-nav__item--active';
$page = 'premium';

if (isset($_REQUEST["subscribe"])) {
    $phone = preg_replace('/\s+/', '', filter_input(INPUT_POST, 'phone'));
    $phone = '254' . substr($phone, -9);
    $amount = filter_input(INPUT_POST, 'amount');
    switch ($amount) {
        case 99:
            $validity = 1;
            break;
        case 499:
            $validity = 7;
            break;
        case 1499:
            $validity = 30;
            break;
        default:
            $validity = -1;
            break;
    }

    include './includes/express-stk.php';

    $sql = 'INSERT INTO `premium`
            (`phone`,`validity`,`amount`) 
            VALUES(?,?,?)
            ON DUPLICATE KEY UPDATE `validity`=?,`amount`=?';
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("siiii", $phone, $validity, $amount, $validity, $amount);
        $stmt->execute();
        $stmt->close();
        $_SESSION['tipspesa_phone'] = $phone;
        $_SESSION['tipspesa_tkn'] = hash('sha512', $phone);
?>
        <script>
            localStorage.setItem('tipspesa_tkn', '<?php echo hash('sha512', $phone); ?>');
            $redirect = true;
        </script>
    <?php
    }
}

if (isset($_REQUEST["fetch"])) {
    $phone = preg_replace('/\s+/', '', filter_input(INPUT_POST, 'phone'));
    $phone = '254' . substr($phone, -9);
    $_SESSION['tipspesa_phone'] = $phone;
    $_SESSION['tipspesa_tkn'] = hash('sha512', $phone);
    ?>
    <script>
        localStorage.setItem('tipspesa_tkn', '<?php echo hash('sha512', $phone); ?>');
        $redirect = true;
    </script>
<?php
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include './includes/head.php'; ?>

<body>
    <nav class="mobile-nav mobile-nav-top">
        <div class="mobile-nav__item">
            <h4 class="mobile-nav__item-content">
                Get Access To Premium Subscription
            </h4>
        </div>
    </nav>

    <div class="container">
        <section class="pricing-table">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="block-heading"></div>
                    <div class="col-md-5 col-lg-4">
                        <div class="item">
                            <div class="heading">
                                <h3>DAILY <sub>24 hrs</sub></h3>
                            </div>
                            <p>Get Full Access to more than 20 Matches</p>
                            <div class="price">
                                <h4 class="text-warning">KSh. 99<br />
                                    <button id="open-popup-modal" class="btn btn-warning" onclick="displayModal(99)">Subscribe</button>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="item">
                            <div class="heading">
                                <h3>WEEKLY <sub>7 days</sub></h3>
                            </div>
                            <p>Get Full Access to more than 100 Matches</p>
                            <div class="price">
                                <h4 class="text-primary">
                                    KSh. 499<br />
                                    <button id="open-popup-modal" class="btn btn-primary" onclick="displayModal(499)">Subscribe</button>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="item">
                            <div class="ribbon">Best Value</div>
                            <div class="heading">
                                <h3>MONTHLY <sub>30 days</sub></h3>
                            </div>
                            <p>Get Full Access to more than 500 Matches</p>
                            <div class="price">
                                <h4 class="text-success">
                                    KSh. 1499<br />
                                    <button id="open-popup-modal" class="btn btn-success" onclick="displayModal(1499)">Subscribe</button>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="block-heading">
                    <div class="item">
                        <div class="heading">
                            <h3>BTC <sub>BitPay</sub></h3>
                        </div>
                        <p>Pay using crypto - BTC Network via below address</p>
                        <p><b>14zMii2uGJz1UjLk5EtKNG2dTSSE2eCU3Y</b></p>
                        <div class="price">
                            <h5 class="text-primary text-bold">
                                $1 <sub> - 1 day</sub><br />
                                $5 <sub> - 7 days</sub><br />
                                $15 <sub> - 30 days</sub>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="block-heading">
                    <h6><button id="open-popup-modal" class="btn btn-success" onclick="displayModal2()">Already Paid?</button></h6>
                    <p>Whatsapp: <a class="text-bold" href="https://wa.me/254105565532">(+254)105 565532</a></p>
                </div>
            </div>
        </section>
    </div>

    <div id="subscribe-modal" class="modal">
        <div class="modal-content animated bounce">
            <a class="modal-close">×</a>
            <div class="modal-text">
                <h2>Subscribe Now</h2>
                <form action="" method="post">
                    Input MPESA Phone Below<br />
                    You will Receive STK Push prompting you to enter MPESA pin<br />
                    <!-- or PAYPAL: <b>info.tipspesa@gmail.com</b><br /> -->
                    <input id="amount" name="amount" type="hidden" />
                    <input name="phone" type="number" class="modal-input" placeholder="Enter Phone No." required /><br />
                    <input name="subscribe" name="value" type="submit" class="modal-submit-btn" value="Submit" />
                </form>
            </div>
        </div>
    </div>

    <div id="fetch-modal" class="modal">
        <div class="modal-content animated bounce">
            <a class="modal-close">×</a>
            <div class="modal-text">
                <h2>Already Paid</h2>
                <form action="" method="post">
                    Input MPESA Phone used to Make Payment Below<br />
                    <input name="phone" type="number" class="modal-input" placeholder="Enter Phone No." required /><br />
                    <input name="fetch" type="submit" class="modal-submit-btn" value="Fetch Transaction" />
                </form>
            </div>
        </div>
    </div>

    <div id="redirect-modal" class="modal">
        <div class="modal-content animated bounce">
            <div class="modal-text">
                <h2>MPESA STK Push Prompt Sent</h2>
                <form action="/" method="post">
                    Once Your Payment has been Received You will be able to Enjoy Your Subscription<br />
                    Just refresh the page when you receive your MPESA Confirmation Message!!!<br />
                    <input name="redirect" type="submit" class="modal-submit-btn" value="Okay" />
                </form>
            </div>
        </div>
    </div>

    <?php include './includes/navbar-bottom.php'; ?>
    <?php include './includes/scripts.php'; ?>
    <script>
        var subscribe = document.getElementById('subscribe-modal');
        var fetch = document.getElementById('fetch-modal');
        var redirect = document.getElementById('redirect-modal');
        var btn = document.getElementById("open-popup-modal");
        var span = document.getElementsByClassName("modal-close")[0];

        function displayModal(amount) {
            subscribe.style.display = "block";
            $("#amount").val(amount);
        }

        function displayModal2() {
            fetch.style.display = "block";
        }

        span.onclick = function() {
            subscribe.style.display = "none";
            fetch.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == subscribe) {
                subscribe.style.display = "none";
            }
        }

        window.onload = function() {
            if ($redirect) {
                redirect.style.display = "block";
                setTimeout(function() {
                    location.href = '/';
                }, 2000);
            }
        }
    </script>
</body>

</html>