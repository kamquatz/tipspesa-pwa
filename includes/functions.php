<?php
function get_background_color($perc)
{
    if ($perc == 100) {
        return 'green';
    } elseif ($perc >= 90) {
        return 'limegreen';
    } elseif ($perc >= 80) {
        return 'lime';
    } elseif ($perc >= 70) {
        return 'lawngreen';
    } elseif ($perc >= 60) {
        return 'yellow';
    } elseif ($perc >= 50) {
        return 'orange';
    } elseif ($perc >= 40) {
        return 'darkorange';
    } elseif ($perc >= 30) {
        return 'tomato';
    } elseif ($perc >= 20) {
        return 'orangered';
    } else {
        return 'red';
    }
}

function highlight_analysis($analysis)
{
    $analysis = str_replace("Low Chance", '<span style="background-color: tomato">Low Chance</span>', $analysis);    
    $analysis = str_replace("Uncertainty", '<span style="background-color: yellow">Uncertainty</span>', $analysis);
    $analysis = str_replace("Medium Chance", '<span style="background-color: lime">Medium Chance</span>', $analysis);
    $analysis = str_replace("High Chance", '<span style="background-color: green">High Chance</span>', $analysis);

     

    return $analysis;
}
