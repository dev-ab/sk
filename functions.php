<?php

function getHData() {
    $hData = [];

    if (($handle = fopen("Historic Data.csv", "r")) !== FALSE) {
        $header = false;
        while (($temp = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (!$header) {
                $header = true;
                continue;
            }
            $d = new DateTime($temp[0]);
            $hData[$d->format('Y')][$d->format('m')][$d->format('d')] = $temp[1];
        }
        fclose($handle);
    }

    return $hData;
}

function getAvgRain($hData, $num, $year, $month, $day) {
    $total = 0;

    for ($i = $num; $i > 0; $i--) {
        $total += $hData[$year - $i][$month][$day];
    }

    return round($total / $num, 2);
}

function prepareDataArrays($hData, $rData, $seedDate, $endDate) {
    $avgs = [];
    $accRain = [];

    $date = "$seedDate[year]-$seedDate[month]-$seedDate[day]";

    $cur = new DateTime($date);
    $curRain = 0;

    $go = true;
    while ($go) {
        $year = $cur->format('Y');
        $month = $cur->format('m');
        $day = $cur->format('d');

        //$avgs[$year][$month][$day] = getAvgRain($hData, 30, $year, $month, $day);

        $newRain = $rData[$year][$month][$day]['rain'];
        $curRain = $accRain[$year][$month][$day] = $curRain + $newRain;

        if (strtotime("$year-$month-$day") >= strtotime("$endDate[year]-$endDate[month]-$endDate[day]"))
            $go = false;

        $cur = new DateTime(date('Y-m-d', strtotime('+1 day', strtotime("$year-$month-$day"))));
    }

    return [$accRain, $avgs];
}

function prepareAccAvgRain($hData, $seedDate, $endDate, $num = 30) {

    $accAvgs = [];

    $date = "$endDate[year]-$endDate[month]-$endDate[day]";
    $cur = new DateTime($date);
    $curAvgRain = 0;

    $go = true;
    while ($go) {
        $year = $cur->format('Y');
        $month = $cur->format('m');
        $day = $cur->format('d');

        $newAvgRain = getAvgRain($hData, $num, $year, $month, $day);
        $curAvgRain = $accAvgs[$year][$month][$day] = $curAvgRain + $newAvgRain;

        if (strtotime("$year-$month-$day") <= strtotime("$seedDate[year]-$seedDate[month]-$seedDate[day]"))
            $go = false;

        $cur = new DateTime(date('Y-m-d', strtotime('-1 day', strtotime("$year-$month-$day"))));
    }

    return $accAvgs;
}

function getCropPotential($avgs, $rains, $rData, $seedDate, $endDate) {
    $data = [];

    $h2o = 4;
    $canola = 7;

    date_default_timezone_set('UTC');

    $date = "$seedDate[year]-$seedDate[month]-$seedDate[day]";

    $moisture = $rData[$seedDate['year']][$seedDate['month']][$seedDate['day']]['moisture'];


    $cur = new DateTime(date('Y-m-d', strtotime('+1 day', strtotime($date))));

    $go = true;
    while ($go) {
        $year = $cur->format('Y');
        $month = $cur->format('m');
        $day = $cur->format('d');

        if (strtotime("$year-$month-$day") >= strtotime("$endDate[year]-$endDate[month]-$endDate[day]"))
            $go = false;

        $d = $day . '-' . $month . '-' . $year;
        $d = (strtotime($d) * 1000) - (strtotime('01-01-1970 00:00:00') * 1000);

        $cur = new DateTime(date('Y-m-d', strtotime('+1 day', strtotime("$year-$month-$day"))));

        $rain = $rains[$year][$month][$day];

        if (isset($avgs[$cur->format('Y')][$cur->format('m')][$cur->format('d')]))
            $avg = $avgs[$cur->format('Y')][$cur->format('m')][$cur->format('d')];
        else
            $avg = 0;

        $val = ((($moisture + $rain + $avg) / 25.4) - $h2o) * $canola;

        $data[] = [$d, round($val, 2)];
    }

    return $data;
}
