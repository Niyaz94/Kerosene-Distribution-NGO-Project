$(document).ready(function() {
    require.config({
        paths: {
            echarts: '../API/assets/js/plugins/visualization/echarts'
        }
    });
    data=[];
    $.ajax({
        url: "models/_dashboard.php",
        type: "POST",
        data: {
            "type":"given"
        },
        dataType: "json",
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
            data=res;
        },
        fail: function (err){
            oneAlert("error","Error!!!",res.data)
        },
        always:function(){
            console.log("complete");
        }
    });
    setTimeout(function(){
        familyGiven(data[0],data[1],data[2]);    
        familyGiven2(data[0],data[1],data[2],data[3]);    
        familyPerCamp(data[4]);    
    },2000)    
});
function familyGiven(title,data1,data2){
    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],
        function (ec, limitless) {
            var basic_columns = ec.init(document.getElementById('basic_columns'), limitless);
            basic_columns_options = {
                grid: {x: 40,x2: 40,y: 35,y2: 25},
                tooltip: {trigger: 'axis'},
                legend: {data: ['Received', 'Not Received']},
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: title
                }],
                yAxis: [{type: 'value'}],
                series: [
                    {
                        name: 'Received',
                        type: 'bar',
                        data: data1,
                        itemStyle: {
                            normal: {
                                color: 'blue',
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        }
                    },
                    {
                        name: 'Not Received',
                        type: 'bar',
                        data: data2,
                        itemStyle: {
                            normal: {
                                color: 'red',
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        }
                    }
                ]
            };
            basic_columns.setOption(basic_columns_options);
            
        }
    );
}
function familyGiven2(title,data1,data2,data3){
    col1=[],col2=[];
    for (let index = 0; index < data3.length; index++) {
        console.log(data1[index],data3[index]);
        col1[index]=data3[index];
        col2[index]=(data3[index]/data1[index])*data2[index];

    }
    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],
        function (ec, limitless) {
            var basic_columns = ec.init(document.getElementById('basic_columns2'), limitless);
            basic_columns_options = {
                grid: {x: 40,x2: 40,y: 35,y2: 25},
                tooltip: {trigger: 'axis'},
                legend: {data: ['Received Liter', 'Not Received Liter']},
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: title
                }],
                yAxis: [{type: 'value'}],
                series: [
                    {
                        name: 'Received Liter',
                        type: 'bar',
                        data: col1,
                        itemStyle: {
                            normal: {
                                color: 'green',
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        }
                    },
                    {
                        name: 'Not Received Liter',
                        type: 'bar',
                        data: col2,
                        itemStyle: {
                            normal: {
                                color: '#2196f3',
                                label: {
                                    show: true,
                                    textStyle: {
                                        fontWeight: 500
                                    }
                                }
                            }
                        }
                    }
                ]
            };
            basic_columns.setOption(basic_columns_options);
            
        }
    );
}

function familyPerCamp(data){
    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/pie',
            'echarts/chart/funnel'
        ],
        function (ec, limitless) {
            var multiple_donuts = ec.init(document.getElementById('multiple_donuts'), limitless);

            output=[];
            height=-100;
            for (index = 0; index < data.length; index++) { 
                if (index%5==0) {
                    height+=300;
                    output.push({
                        type: 'pie',
                        center: ['10%', `${height}px`],
                        radius: [60, 100],
                        data: [
                            {name: 'Received', value: data[index]["total_received"],itemStyle:{normal: { 
                                color: '#4caf50',
                                label: {
                                    show: true,
                                    position: 'center',
                                    formatter: `${data[index]["CMPName"]}`,
                                    textStyle: {
                                        baseline: 'middle',
                                        fontWeight: 300,
                                        fontSize: 15
                                    }
                                },
                                labelLine: {
                                    show: false
                                }
                        
                            }}},
                            {name: 'Not Received', value: data[index]["total"],itemStyle:{normal: { 
                                color: '#59b1ef',
                                label:{show:false},
                                labelLine: {
                                    show: false
                                }
                            }}}
                        ]
                    });
                }else if(index%5==1){
                    output.push({
                        type: 'pie',
                        center: ['30%', `${height}px`],
                        radius: [60, 100],
                        data: [
                            {name: 'Received', value: data[index]["total_received"],itemStyle:{normal: { 
                                color: '#4caf50',
                                label: {
                                    show: true,
                                    position: 'center',
                                    formatter: `${data[index]["CMPName"]}`,
                                    textStyle: {
                                        baseline: 'middle',
                                        fontWeight: 300,
                                        fontSize: 15
                                    }
                                },
                                labelLine: {
                                    show: false
                                }
                        
                            }}},
                            {name: 'Not Received', value: data[index]["total"],itemStyle:{normal: { 
                                color: '#59b1ef',
                                label:{show:false},
                                labelLine: {
                                    show: false
                                }
                            }}}
                        ]
                    });
                }else if(index%5==2){
                    output.push({
                        type: 'pie',
                        center: ['50%', `${height}px`],
                        radius: [60, 100],
                        data: [
                            {name: 'Received', value: data[index]["total_received"],itemStyle:{normal: { 
                                color: '#4caf50',
                                label: {
                                    show: true,
                                    position: 'center',
                                    formatter: `${data[index]["CMPName"]}`,
                                    textStyle: {
                                        baseline: 'middle',
                                        fontWeight: 300,
                                        fontSize: 15
                                    }
                                },
                                labelLine: {
                                    show: false
                                }
                        
                            }}},
                            {name: 'Not Received', value: data[index]["total"],itemStyle:{normal: { 
                                color: '#59b1ef',
                                label:{show:false},
                                labelLine: {
                                    show: false
                                }
                            }}}
                        ]
                    });
                }else if(index%5==3){
                    output.push({
                        type: 'pie',
                        center: ['70%', `${height}px`],
                        radius: [60, 100],
                        data: [
                            {name: 'Received', value: data[index]["total_received"],itemStyle:{normal: { 
                                color: '#4caf50',
                                label: {
                                    show: true,
                                    position: 'center',
                                    formatter: `${data[index]["CMPName"]}`,
                                    textStyle: {
                                        baseline: 'middle',
                                        fontWeight: 300,
                                        fontSize: 15
                                    }
                                },
                                labelLine: {
                                    show: false
                                }
                        
                            }}},
                            {name: 'Not Received', value: data[index]["total"],itemStyle:{normal: { 
                                color: '#59b1ef',
                                label:{show:false},
                                labelLine: {
                                    show: false
                                }
                            }}}
                        ]
                    });
                }else if(index%5==4){
                    output.push({
                        type: 'pie',
                        center: ['90%', `${height}px`],
                        radius: [60, 100],
                        data: [
                            {name: 'Received', value: data[index]["total_received"],itemStyle:{normal: { 
                                color: '#4caf50',
                                label: {
                                    show: true,
                                    position: 'center',
                                    formatter: `${data[index]["CMPName"]}`,
                                    textStyle: {
                                        baseline: 'middle',
                                        fontWeight: 300,
                                        fontSize: 15
                                    }
                                },
                                labelLine: {
                                    show: false
                                }
                        
                            }}},
                            {name: 'Not Received', value: data[index]["total"],itemStyle:{normal: { 
                                color: '#59b1ef',
                                label:{show:false},
                                labelLine: {
                                    show: false
                                }
                            }}}
                        ]
                    });                    
                }
                
            }
            multiple_donuts_options = {
                title: {
                    text: 'The Distribution Per Camp',
                    subtext: 'Just For The Curent Round',
                    x: 'center'
                },
                tooltip: {
                    show: true,
                    formatter: "{b}: {c}"
                },
                series: output
            };
            multiple_donuts.setOption(multiple_donuts_options);
        }
    );
    height=200+((parseInt(data.length/5)+1)*300);
    $("#multiple_donuts").prop("style",`height:${height}px;`);
}
