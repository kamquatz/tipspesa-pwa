<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- Script JS -->
<script src="./js/plugin.js"></script>
<script src="./js/script.js"></script>   
<script>
    var pStart = {x: 0, y: 0};
    var pStop = {x: 0, y: 0};

    function swipeStart(e) {
        if (typeof e["targetTouches"] !== "undefined") {
            var touch = e.targetTouches[0];
            pStart.x = touch.screenX;
            pStart.y = touch.screenY;
        } else {
            pStart.x = e.screenX;
            pStart.y = e.screenY;
        }
    }

    function swipeEnd(e) {
        if (typeof e["changedTouches"] !== "undefined") {
            var touch = e.changedTouches[0];
            pStop.x = touch.screenX;
            pStop.y = touch.screenY;
        } else {
            pStop.x = e.screenX;
            pStop.y = e.screenY;
        }

        swipeCheck();
    }

    function swipeCheck() {
        var changeY = pStart.y - pStop.y;
        var changeX = pStart.x - pStop.x;
        if (isPullDown(changeY, changeX)) {
            window.location.reload();
        }
    }

    function isPullDown(dY, dX) {
        // methods of checking slope, length, direction of line created by swipe action
        return (
                dY < 0 &&
                ((Math.abs(dX) <= 100 && Math.abs(dY) >= 300) ||
                        (Math.abs(dX) / Math.abs(dY) <= 0.3 && dY >= 60))
                );
    }

    document.addEventListener(
            "touchstart",
            function (e) {
                swipeStart(e);
            },
            false
            );
    document.addEventListener(
            "touchend",
            function (e) {
                swipeEnd(e);
            },
            false
            );
</script>


