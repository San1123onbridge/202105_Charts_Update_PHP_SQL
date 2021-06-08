function all_ajax() {
    $.ajax({
        url: "./php/all_charts.php",
        dataType: "json",
        type: "get",
        data: "a=1",
        async: false,
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },

        success: function (data) {
            //console.log(data);
            //all_make_attr(data);
        }

    })
}
function all_make_attr(data) {
    var item = ["CJ_ALU", "CJ_COPPER", "CJ_ZINC", "GOLD", "HOT_RSS", "LON_COPPER", "LON_NICKEL", "NY_BASEOIL", "PA", "PP", "SCREW", "SPRING", "US_NEWHOUSE"];
    for (var i = 0; i < 13; i++) {
        var sent_result = make_attr(data[i], item[i]);
        controller(sent_result);
    }
    return true;
}



function search_ajax() {
    $.ajax({
        url: "./php/search_index.php",
        dataType: "json",
        type: "POST",
        data: $("#main_item").serialize(),
        async: "false",
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },

        success: function (temp_data) {
            //console.log(temp_data); 
            var item_title = $("select[name='item_sel']").val();
            //console.log(item_title);
            var data = make_attr(temp_data, item_title);
            controller(data);

        }
    })
}

function controller(data) {
    ChartOutput(data[0], data[1], data[2], data[3]);
    msg_ctrl(data[0], data[1]);
    view_ctrl(data[0]);
}

//定義 Javascript 使用之陣列
function make_attr(data, item_title) {

    // attr for charting
    // 52 weeks    

    var title = item_title;      // charts title
    var currency = "";
    var loca = "";      //charts on which div id

    var temp_date = new Array();     // charts categories      
    var temp_price = new Array();
    var temp_sma_m = new Array();
    var temp_sma_y = new Array();

    var temp = new Array();
    temp = data;

    loca += "#" + title;
    currency = temp[0][3];

    if (title == "US_NEWHOUSE") {
        for (var i = 0, j = 0; i < 36; i++) {
            temp_date[i] = temp[i][1];
            temp_price[i] = temp[i][2];
            currency = temp[0][3];
            temp_sma_m[i] = temp[i][4];
            temp_sma_y[i] = temp[i][5];
        }
        parseInt(temp_date);
        parseFloat(temp_price);
        parseFloat(temp_sma_m);
        parseFloat(temp_sma_y);
        var temp_year = parseInt(temp_date[26] / 100);
    } else {
        for (var i = 0, j = 0; i < 156; i++) {
            temp_date[i] = temp[i][1];
            temp_price[i] = temp[i][2];
            currency = temp[0][3];
            temp_sma_m[i] = temp[i][4];
            temp_sma_y[i] = temp[i][5];
        }
        parseInt(temp_date);
        parseFloat(temp_price);
        parseFloat(temp_sma_m);
        parseFloat(temp_sma_y);
        var temp_year = parseInt(temp_date[120] / 100);
    }
    

    var y1 = temp_year,
        y2 = y1 - 1,
        y3 = y2 - 1,
        sma_m_title = temp_year + "-sma_month",
        sma_y_title = temp_year + "-sma_year";

    var price1 = new Array(),
        price2 = new Array(),
        price3 = new Array(),
        sma_m = new Array(),
        sma_y = new Array();
    
    if (title == "US_NEWHOUSE") {
        //2019
        price3 = temp_price.slice(0, 12);
        //2020
        price2 = temp_price.slice(12, 24);
        //2021
        price1 = temp_price.slice(24);

        //sma_y and sma_m just take the standard year

        sma_y = temp_sma_y.slice(24);
        sma_m = temp_sma_m.slice(24);
    } else {        
        //2019
        price3 = temp_price.slice(0, 52);
        //2020
        price2 = temp_price.slice(52, 104);       
        //2021
        price1 = temp_price.slice(104);

        //sma_y and sma_m just take the standard year

        sma_y = temp_sma_y.slice(104);
        sma_m = temp_sma_m.slice(104);
    }


    var result1 = new Array(),
        result2 = new Array(),
        result3 = new Array(),
        result4 = new Array();


    /*  1. Item title 
        1. currency
        1. Location

        2. y1 y2 y3  ex. 2021 2020 2019  
        
        3. price1 price2 price3
        
        4. sma_m_title sma_y_title sma_m sma_y
    */

    result1.push(title, currency, loca);
    result2.push(y1, y2, y3);
    result3.push(price1, price2, price3);
    result4.push(sma_m_title, sma_y_title, sma_m, sma_y);
    
    //show the msg for selecting
    /*
    var msg_ctrl = document.getElementById("msg_ctrl");    
    msg_ctrl.style.display = 'block';
    var msg = "You select item " + title + " and from " + y3 + " to " + y1;
    var msg_area = document.getElementById("msg_area");
    msg_area.innerHTML = msg;
    */

    //show the charts div
    /*
    var frame = result1[0] + "_fram";    
    var dis = document.getElementById(frame);
    dis.style.display = 'block';
    */

    return Array(result1, result2, result3, result4);

}

function msg_ctrl(data1, data2) {
    //show the msg for selecting
    var msg_ctrl = document.getElementById("msg_ctrl");
    msg_ctrl.style.display = 'block';
    var msg = "You select item " + data1[0] + " and from " + data2[2] + " to " + data2[0];
    var msg_area = document.getElementById("msg_area");
    msg_area.innerHTML = msg;
}

function view_ctrl(data) {
    //show the charts div
    var frame = data[0] + "_fram";
    var dis = document.getElementById(frame);
    dis.style.display = 'block';
}


function ChartOutput(arr1, arr2, arr3, arr4) {
    var title = arr1[0],   //arr1 
        currency = arr1[1],
        location = arr1[2];

    var y1 = arr2[0],   //arr2
        y2 = arr2[1],
        y3 = arr2[2];

    var price1 = arr3[0],   //arr3
        price2 = arr3[1],
        price3 = arr3[2];

    var sma_m_title = arr4[0],
        sma_y_title = arr4[1],
        sma_m = arr4[2],
        sma_y = arr4[3];

    if (title == "US_NEWHOUSE") {
        sma_m_title = y1 + "-sma_season";
    }

    var week = new Array();
    for (var j = 1; j < 53; j++) {
        week.push(j);
    }

    //
    var temp_price = new Array();

    for (var i = 0; i < price1.length; i++) {
        price1[i] = parseFloat(price1[i]);
        price2[i] = parseFloat(price2[i]);
        price3[i] = parseFloat(price3[i]);
        sma_m[i] = parseFloat(sma_m[i]);
        sma_y[i] = parseFloat(sma_y[i]);
    }

    price1 = price1.filter(function(value){
        return value > 0;
    })

    var title = {
        text: ''
    };

    var subtitle = {
        text: ''
    };

    var xAxis = {
        categories: week
    };

    var yAxis = {
        title: {
            text: currency
        },
        plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
        }]
    };

    var legend = {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
    };    

    var series = [
        {
            name: y1,
            data: price1
        },
        {
            name: y2,
            data: price2
        },
        {
            name: y3,
            data: price3
        },
        {
            name: sma_m_title,
            lineWidth: 5,
            data: sma_m
        },
        {
            name: sma_y_title,
            lineWidth: 5,
            data: sma_y
        }        
    ];


    
    // get max min (between two years)
    var r_Max_Min = new Array();
    r_Max_Min = getMaxMin(price1, price2);
    //console.log(r_Max_Min);
    
            
     
    var plotOptions = {
        line: {
            dataLabels: {
                enabled: true,
                formatter: function () {  

                    if (this.y == r_Max_Min[0]){
                        return '<span style="color: red">' + this.series.name + 'max:' + this.y + '</span>'; 
                    }else if(this.y == r_Max_Min[1]){
                        return '<span style="color: green;">' + this.series.name + 'min:' + this.y + '</span>';
                    }else if(this.y == r_Max_Min[2]){
                        return '<span style="color: black;">' + this.series.name + 'now:' + this.y + '</span>';
                    }
                    

                    /*
                    if (this.y == this.series.dataMax) {
                            return '<span style="color: red">' + this.series.name + 'max:' + this.y + '</span>';                        
                    }else{
                        if(this.y == this.series.dataMin){                            
                            return '<span style="color: green;">' + this.series.name + 'min:' + this.y + '</span>';                            
                        }else{
                            return null;
                        }
                    }
                    */
                },

                enableMouseTracking: false
            }
        }
    };
  
    
    var json = {};
    json.title = title;
    json.subtitle = subtitle;
    json.xAxis = xAxis;
    json.yAxis = yAxis;
    json.legend = legend;
    
    for(var z = 0 ; z < 5 ; z++){
        var p = 0;
        while(p < 52){
            if(series[z].data[p] == 0){
                series[z].data[p] = NuLL;
                p++;
            }
        }        
    }

    json.series = series;  
    json.plotOptions = plotOptions;   
    


    $(location).highcharts(json, function (c) {
        //highLightExtreme(c);
    });
}
function getMaxMin(arr1, arr2){
    var t_arr1 = new Array(),
        t_arr2 = new Array();

    for(var p = 0 ; p < arr1.length ; p++){
        t_arr1.push(arr1[p]);
        t_arr2.push(arr2[p]);
    }
    
    for(var i = 0 ; i < arr1.length ; i++){
        if(isNaN(t_arr1[i])){
            t_arr1[i] = 0;
        }
        if(isNaN(t_arr2[i])){
            t_arr2[i] = 0;
        }
    }

    t_arr1 = t_arr1.filter(function(num){
        return num > 0;
    })
    t_arr2 = t_arr2.filter(function(num){
        return num > 0;
    })

    var lastnum = t_arr1[t_arr1.length-1];    

    var tmax1 = Math.max.apply(null, t_arr1),
        tmax2 = Math.max.apply(null, t_arr2),
        tmin1 = Math.min.apply(null, t_arr1),
        tmin2 = Math.min.apply(null, t_arr2);
    var max = 0, min = 100000000000000;
    if(max < tmax1){
        max = tmax1;
        if(max < tmax2){
            max = tmax2
        }
    }
    if(min > tmin1){
        min = tmin1;
        if(min > tmin2){
            min = tmin2;
        }
    }
    
    
        
    var result = new Array();
    result.push(max, min, lastnum);
    return result;   
}


function highLightExtreme(chart) {
    Highcharts.each(chart.series, function (s) {
        Highcharts.each(s.points, function (p) {            
            if (p.y === s.dataMax) {
                p.update({
                    color: 'red',
                    marker: {
                        enabled: true,
                        symbol: 'diamond',
                        radius: 5
                    },                    
                }, false);
            } /*else if (p.y === s.dataMin) {
                p.update({
                    color: 'green',
                    marker: {
                        enabled: true,
                        symbol: 'diamond',
                        radius: 5
                    }
                }, false);
            }*/
        });
    });
    chart.redraw();
}