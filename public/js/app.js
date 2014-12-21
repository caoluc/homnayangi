$(function() {
    var pointData = [];
    $.each(mealPointsData, function (key, value) {
        pointData.push([
            value[0],
            parseInt(value[1])
        ]);
    });
    $('#point-chart').highcharts({
        chart: {
            backgroundColor: null,
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Điểm số và tỉ lệ của từng món ăn hiện tại',
            style: {
                'font-size': '2em',
                'color': '#3498db'
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<span style="color: #e74c3c"><b>{point.name}</b></span>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || '#00bc8c'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Meal Point',
            data: pointData
        }]
    });

    var columnData = [];
    $.each(mealCountData, function (key, value) {
        columnData.push({
            name: value[0],
            y: parseInt(value[1])
        });
    });

    $('#count-chart').highcharts({
        chart: {
            type: 'column',
            backgroundColor: null,
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        credits: {
            enabled: false
        },
        title: {
            text: 'Thống kê số lần ăn',
            style: {
                'font-size': '2em',
                'color': '#3498db'
            }
        },
        xAxis: {
            type: 'category',
            labels: {
                style: {
                    'color': '#00bc8c'
                }
            }
        },
        yAxis: {
            title: {
                text: 'Số lần ăn'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color: #e74c3c">{point.name}</span>: <b>{point.y}</b><br/>'
        },

        series: [{
            colorByPoint: true,
            type: 'column',
            name: 'Số lần ăn',
            data: columnData
        }]
    });
});