app.controller('Main', ['$scope', '$compile', '$window', function ($scope, $compile, $window) {
        $scope.colors = ['#FFC300', '#E67E22', '#E67E22', '#2E86C1', '#CD5C5C'];
        
        $scope.labels = $window.labels;
        $scope.data = $window.data;

        $scope.datasetOverride = [
            {
                label: "5 year rain avg",
                borderWidth: 1,
                type: 'bar',
                xAxisID: 'x-axis',
                yAxisID: 'y-axis-3',
                display: true,
                fill: true
            },
            {
                label: "10 year rain avg",
                borderWidth: 1,
                type: 'bar',
                yAxisID: 'y-axis-4',
                display: true,
            },
            {
                label: "actual rain",
                borderWidth: 1,
                type: 'bar',
                yAxisID: 'y-axis-4',
                display: true,
            },
            {
                label: "Soil Moisture",
                type: 'line',
                yAxisID: 'y-axis-1',
                display: true,
                fill: false,
                lineTension: 0
            },
            {
                label: "Crop Potential",
                type: 'line',
                yAxisID: 'y-axis-2',
                display: true,
                fill: false,
                lineTension: 0
            }
        ];

        $scope.options = {
            scales: {
                xAxes: [{
                        id: 'x-axis',
                        //type: 'time',
                        scaleLabel: {
                            display: true,
                            labelString: 'Days',
                            fontColor: "#566573",
                            fontSize: 18
                        },
                        ticks: {
                            fontColor: "#566573",
                            fontSize: 12,
                            suggestedMax: 10
                        },
                        /*time: {
                            unit: 'day',
                            min: new Date('2016-05-19'),
                            max: new Date('2016-11-01'),
                            displayFormats: {
                                day: 'll'
                            }
                        }*/
                    }],
                yAxes: [
                    {
                        id: 'y-axis-3',
                        position: 'right',
                        ticks: {
                            fontColor: "#CD5C5C",
                            fontSize: 14,
                            beginAtZero: true,
                            stepSize: 10,
                            suggestedMax: 60
                        }
                    },
                    {
                        id: 'y-axis-4',
                        position: 'right',
                        ticks: {
                            fontColor: "#CD5C5C",
                            fontSize: 14,
                            beginAtZero: true,
                            stepSize: 10,
                            suggestedMax: 60
                        }
                    },
                    {
                        id: 'y-axis-2',
                        position: 'left',
                        scaleOverride: true,
                        scaleSteps: 10,
                        scaleStepWidth: 50,
                        scaleStartValue: 0,
                        ticks: {
                            fontColor: "#CD5C5C",
                            fontSize: 14,
                            beginAtZero: true,
                            stepSize: 10,
                            suggestedMax: 60
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Crop Potential',
                            fontColor: "#CD5C5C",
                            fontSize: 16
                        }
                    },
                    {
                        id: 'y-axis-1',
                        position: 'left',
                        scaleOverride: true,
                        scaleSteps: 10,
                        scaleStepWidth: 50,
                        scaleStartValue: 0,
                        ticks: {
                            fontColor: "#2E86C1",
                            fontSize: 14,
                            beginAtZero: true,
                            stepSize: 50,
                            suggestedMax: 500
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Soil Moisture',
                            fontColor: "#2E86C1",
                            fontSize: 16
                        }
                    }
                ]
            }
        };



    }]);