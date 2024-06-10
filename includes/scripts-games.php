<script>
    $(document).ready(function() {
        $(".my-progress-bar")
            .circularProgress({
                line_width: 6,
                color: "red",
                starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
                percent: 0, // percent starts from
                percentage: '',
                text: "No Games available at this time. Check out later",
            })
            .circularProgress("animate", 0, 2000);

    });
/*
    window.onload = function() {
        if (localStorage.getItem('tipspesa_tkn') !== null) {
            if (!window.location.search.includes('hyxrt')) {
                location.href = location.href + '?' + localStorage.getItem('tipspesa_tkn') + localStorage.getItem('tipspesa_tkn') + localStorage.getItem('tipspesa_tkn') + localStorage.getItem('tipspesa_tkn') + '=' + localStorage.getItem('tipspesa_tkn') +
                    '&hyxrt=' + localStorage.getItem('tipspesa_tkn') + "&" + localStorage.getItem('tipspesa_tkn') + "=" + localStorage.getItem('tipspesa_tkn');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        } else if (window.location.search.includes('hyxrt'))
            location.href = location.href.replace('hyxrt', 'null');
    }
    */
</script>