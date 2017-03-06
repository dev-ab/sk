<html>
    <head>
        <title>South Country Equipment</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
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
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1" style="text-align: center;position: fixed;z-index: 9999;">
                    <h1>Soil Moisture  / Rain & Crop Potential</h1>
                    <ul id="legend-list" class="list-inline">
                        <li><i class="fa fa-circle" style="color:#F9E79F;"></i> 10 year rain avg</li>
                        <li><i class="fa fa-circle" style="color:#F4D03F;"></i> 5 year rain avg</li>
                        <li><i class="fa fa-circle" style="color:#EB984E;"></i> Actual Rain</li>
                        <li><i class="fa fa-minus" style="color:#7cb5ec;"></i> Soil Moisture</li>
                        <li><i class="fa fa-minus" style="color:#B03A2E;"></i> Crop Potential</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div id="resize" class="col-xs-12" style="display: none;">
                    <h2>Resizing...</h2>
                </div>

                <div id="charts" class="col-xs-12" style="margin-top: 50px;">
                </div>
            </div>
        </div>
        <script>
            var data = JSON.parse('<?= json_encode($aData); ?>');
            var mdWidth = JSON.parse('<?= json_encode($mdWidth); ?>');
            var mdHeight = 300;
        </script>
        <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
