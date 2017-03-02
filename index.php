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

        $date = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
        date_default_timezone_set('UTC');
        $date = (strtotime($date) * 1000) - (strtotime('01-01-1970 00:00:00') * 1000);

        if ($temp[8] == 'Rain Gauge') {
            $aData[0][] = [$date, getAvgRain(5, $temp[0], $temp[1], $temp[2]) * 10];
            $aData[1][] = [$date, getAvgRain(10, $temp[0], $temp[1], $temp[2]) * 10];
            $aData[2][] = [$date, $temp[9] * 10];
        } else if ($temp[8] == 'Soil Moisture') {
            $aData[3][] = [$date, (int) $temp[9]];
        }
    }
    fclose($handle);
}

$aData[4][] = [];
//echo count($aData[0]);

$mdWidth = count($aData[0]) * 67;

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
        <style type="text/css">
            ::-webkit-scrollbar {
                width: 14px;
                height: 14px;
            }
            ::-webkit-scrollbar-button {
                width: 0px;
                height: 0px;
            }
            ::-webkit-scrollbar-thumb {
                background: #e1e1e1;
                border: 1px none #ffffff;
                border-radius: 50px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #c0c0c0;
            }
            ::-webkit-scrollbar-thumb:active {
                background: #808080;
            }
            ::-webkit-scrollbar-track {
                background: #666666;
                border: 1px none #ffffff;
                border-radius: 13px;
            }
            ::-webkit-scrollbar-track:hover {
                background: #666666;
            }
            ::-webkit-scrollbar-track:active {
                background: #c0c0c0;
            }
            ::-webkit-scrollbar-corner {
                background: transparent;
            }
        </style>
    </head>
    <body ng-controller="Main">
        <div class="container-fluid">
            <div class="row">
                <div id="charts" class="col-xs-12" style="">

                </div>
            </div>
        </div>
        <script>
                    var data = JSON.parse('<?= json_encode($aData); ?>');
                    var mdWidth = JSON.parse('<?= json_encode($mdWidth); ?>');
                    var mdHeight = 400;
        </script>
        <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
        <script src="js/angular.min.js"></script>
        <script src="js/moment.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="js/app.js"></script>
        <script src="js/main1.js"></script>
        <script>

                    var chart = Highcharts.chart('charts', {
                        chart: {
                            zoomType: 'x',
                            resetZoomButton: {
                                position: {
                                    align: 'left',
                                    x: 150
                                }
                            },
                            panning: false,
                            pinchType: false
                        },
                        title: {
                            text: 'Soil Moisture  / Rain & Crop Potential'
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: [{
                                type: 'datetime',
                                title: {
                                    text: 'Date'
                                }
                            }],
                        yAxis: [
                            {
                                max: 150,
                                tickInterval: 10,
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: '#F9E79F'
                                    }
                                },
                                title: {
                                    text: '5 year rain avg',
                                    style: {
                                        color: '#F9E79F'
                                    }
                                },
                                visible: false
                            },
                            {
                                max: 150,
                                tickInterval: 10,
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: '#F4D03F'
                                    }
                                },
                                title: {
                                    text: '5 year rain avg',
                                    style: {
                                        color: '#F4D03F'
                                    }
                                },
                                visible: false
                            },
                            {
                                max: 150,
                                tickInterval: 10,
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: '#EB984E'
                                    }
                                },
                                title: {
                                    text: 'Actual Rain',
                                    style: {
                                        color: '#EB984E'
                                    }
                                },
                                visible: false
                            },
                            {
                                max: 500,
                                tickInterval: 50,
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: Highcharts.getOptions().colors[0]
                                    }
                                },
                                title: {
                                    text: 'Soil Moisture',
                                    style: {
                                        color: Highcharts.getOptions().colors[0]
                                    }
                                },
                                plotLines: [{
                                        value: 0,
                                        width: 1,
                                        color: '#ff0000'
                                    }]
                            }
                        ],
                        tooltip: {
                            shared: true,
                            followTouchMove: false
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'left',
                            x: 120,
                            verticalAlign: 'top',
                            y: 100,
                            floating: true,
                            backgroundColor: '#FFFFFF'
                        },
                        scrollbar: {
                            enabled: true
                        },
                        series: [
                            {
                                name: '10 year rain avg',
                                color: '#F9E79F',
                                type: 'column',
                                yAxis: 0,
                                data: data[1],
                                tooltip: {
                                    valueSuffix: ''
                                }
                            },
                            {
                                name: '5 year rain avg',
                                color: '#F4D03F',
                                type: 'column',
                                yAxis: 0,
                                data: data[0],
                                tooltip: {
                                    valueSuffix: ''
                                }
                            },
                            {
                                name: 'Actual Rain',
                                color: '#EB984E',
                                type: 'column',
                                yAxis: 0,
                                data: data[2],
                                tooltip: {
                                    valueSuffix: ''
                                }
                            },
                            {
                                name: 'Soil Moisture',
                                color: Highcharts.getOptions().colors[0],
                                type: 'spline',
                                yAxis: 3,
                                data: data[3],
                                tooltip: {
                                    valueSuffix: ''
                                }
                            },
                            /*{
                             name: 'Crop Potential',
                             color: '#CD5C5C',
                             type: 'spline',
                             yAxis: 2,
                             pointInterval: 3600 * 1000,
                             data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                             tooltip: {
                             valueSuffix: ''
                             }
                             
                             }*/
                        ]
                    });
                            function setLabelsSize(ch, size) {
                                if (size == 'md') {
                                    var title = '30px';
                                    var label = '15px';
                                    var ticks = '15px';

                                } else if (size == 'sm') {
                                    var title = '45px';
                                    var label = '30px';
                                    var ticks = '30px';
                                } else {
                                    var title = '60px';
                                    var label = '40px';
                                    var ticks = '40px';
                                }

                                var x = [0];
                                var y = [3];


                                ch.title.update({
                                    style: {
                                        "fontSize": label,
                                    }
                                });
                                ch.legend.update({
                                    itemStyle: {
                                        "fontSize": label,
                                    }
                                });

                                ch.tooltip.update({
                                    style: {
                                        "fontSize": label
                                    }
                                });

                                x.forEach(function (e, i) {
                                    ch.xAxis[e].update({
                                        title: {
                                            style: {
                                                "fontSize": label,
                                            }
                                        },
                                        labels: {
                                            style: {
                                                "fontSize": ticks,
                                            }
                                        }
                                    });
                                })


                                y.forEach(function (e, i) {
                                    ch.yAxis[e].update({
                                        title: {
                                            style: {
                                                "fontSize": label,
                                            }
                                        },
                                        labels: {
                                            style: {
                                                "fontSize": ticks,
                                            }
                                        }
                                    });
                                })




                            }

                    function setChartSize(ch) {
                        var wHeight = $(window).height() - 20;

                        if ($(window).width() >= 992) {
                            ch.setSize(mdWidth, wHeight);
                            setLabelsSize(ch, 'md');
                        } else if ($(window).width() >= 768) {
                            ch.setSize(mdWidth * 2.13, wHeight);
                            setLabelsSize(ch, 'sm');

                        } else {
                            ch.setSize(mdWidth * 3.6, wHeight);
                            setLabelsSize(ch, 'xs');
                        }
                    };




                    setChartSize(chart);


                    $(window).resize(function () {
                        setChartSize(chart);

                    });
        </script>
    </body>
</html>
