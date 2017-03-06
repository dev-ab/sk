<?php

ini_set('auto_detect_line_endings', true);
ini_set('serialize_precision', '5');

include 'functions.php';

$seedDate = ['year' => 2016, 'month' => '05', 'day' => 20];
$endDate = ['year' => 2016, 'month' => '08', 'day' => 15];

//get history data
$hData = getHData();

//prepare actual data
$aData = [];
$rData = [];
if (($handle = fopen("John Deer Data.csv", "r")) !== FALSE) {
    $header = false;
    while (($temp = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (!$header || !intval($temp[0])) {
            $header = true;
            continue;
        }

        $d = new DateTime($temp[0] . '-' . $temp[1] . '-' . $temp[2]);

        $year = $d->format('Y');
        $month = $d->format('m');
        $day = $d->format('d');

        $date = $day . '-' . $month . '-' . $year;
        date_default_timezone_set('UTC');
        $date = (strtotime($date) * 1000) - (strtotime('01-01-1970 00:00:00') * 1000);

        $val = (float) $temp[9];

        if ($temp[8] == 'Rain Gauge') {
            $aData[0][] = [$date, (float) getAvgRain($hData, 5, $year, $month, $day)];
            $aData[1][] = [$date, (float) getAvgRain($hData, 10, $year, $month, $day)];
            $aData[2][] = [$date, $val];
            $rData [$year][$month][$day]['rain'] = $val;
        } else if ($temp[8] == 'Soil Moisture') {
            $aData[3][] = [$date, $val];
            $rData [$year][$month][$day]['moisture'] = $val;
        }
    }

    fclose($handle);
}

$data = prepareDataArrays($hData, $rData, $seedDate, $endDate);
$avgs = prepareAccAvgRain($hData, $seedDate, $endDate);
$aData[4] = getCropPotential($data[0], $avgs, $rData, $seedDate, $endDate);


//print_r($data[0]);
//print_r($avgs);
//print_r($aData[4]);
//print_r($rData);


$mdWidth = count($aData[0]) * 67;

include 'main.php';
