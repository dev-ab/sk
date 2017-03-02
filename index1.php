<?php
ini_set('auto_detect_line_endings', true);
//prepare history data
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

//prepare actual data
$aData = [];
$labels = [];
if (($handle = fopen("John Deer Data.csv", "r")) !== FALSE) {
    $header = false;
    while (($temp = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (!$header || !intval($temp[0])) {
            $header = true;
            continue;
        }

        $d = new DateTime($temp[0] . '-' . $temp[1] . '-' . $temp[2]);

        $temp[0] = $d->format('Y');
        $temp[1] = $d->format('m');
        $temp[2] = $d->format('d');

        $day = $temp[0] . '-' . $temp[1] . '-' . $temp[2];
        if (!in_array($day, $labels))
            $labels[] = $day;
        if ($temp[8] == 'Rain Gauge') {
            $aData[0][] = getAvgRain(5, $temp[0], $temp[1], $temp[2]) * 10; //['x' => $day, 'y' => getAvgRain(5, $temp[0], $temp[1], $temp[2]) * 10];
            $aData[1][] = getAvgRain(10, $temp[0], $temp[1], $temp[2]) * 10; //['x' => $day, 'y' => getAvgRain(10, $temp[0], $temp[1], $temp[2]) * 10];
            $aData[2][] = $temp[9] * 10; //['x' => $day, 'y' => $temp[9] * 10];
        } else if ($temp[8] == 'Soil Moisture') {
            $aData[3][] = ['x' => $day, 'y' => $temp[9]];
        }
    }
    fclose($handle);
}

$aData[4][] = [];

function getAvgRain($num, $year, $month, $day) {
    global $hData;
    $total = 0;
    for ($i = $num; $i > 0; $i--) {
        $total += $hData[$year - $i][$month][$day];
    }
    return round($total / $num, 2);
}
?>

<html ng-app="MyApp">
    <head>
        <title>South Country Equipment</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>
            .chartWrapper {
                position: relative;
            }

            .chartWrapper > canvas {
                position: absolute;
                left: 0;
                top: 0;
                pointer-events:none;
            }

            .chartAreaWrapper {
                width: 10000px;
                /*overflow-x: scroll;*/
            }
        </style>
    </head>
    <body ng-controller="Main">
        <div class="container-fluid">
            <div class="row" style="margin-top: 100px;">
                <div class="col-md-10 col-md-offset-1" style="overflow: scroll">
                    <div class="chartWrapper">   
                        <div class="chartAreaWrapper">
                            <canvas id="base" class="chart-bar" height="20"
                                    chart-data="data" chart-labels="labels" chart-colors="colors"
                                    chart-dataset-override="datasetOverride" chart-options='options'>
                            </canvas> 
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script>
            var data = JSON.parse('<?= json_encode($aData); ?>');
            var labels = JSON.parse('<?= json_encode($labels); ?>');
        </script>

        <script src="js/angular.min.js"></script>
        <script src="js/moment.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/angular-chart.min.js"></script>
        <script src="js/app.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
