$(document).ready(function(){    
    $.ajax({
        url: "./php/check_date.php",
        dataType: "json",
        data: "a=1",
        type: "post",        
        async: false,
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            alert("connecting wrong");
        },

        success: function (data) {            
            controller(data);

        }
    })
})

function controller(data){
    var date = def_date(data);
    date_val_ctr(date);
    msg_ctr(date);
}

function def_date(data){
    var data_arr = data;

    //date1 for common item, date2 for us_newhouse
    var date1, 
        date2;

    if(!isNaN(data_arr[0])){
        date1 = parseInt(data_arr[0]) + 1;
    }else{
        //call the insert function
    }
    if(!isNaN(data_arr[1])){
        date2 = parseInt(data_arr[1]) + 1;
    }else{
        //call the insert function
    }
    
    var result = new Array();
    result.push(date1,date2);
    return result;    
}

//control date value
function date_val_ctr(date){
    var def_date = date;
    var tar1 = document.getElementById("input_date"),
        tar2 = document.getElementById("us_date");
    tar1.value = def_date[0];
    tar2.value = def_date[1];
}

//control msg area
function msg_ctr(date){
    //tar1 for common item msg area, tar2 for us_nh item
    var tar1 = document.getElementById("in_date_msg"),
        tar2 = document.getElementById("us_date_msg");
    msg_com = "The price of " + date[0] + " is NuLL in db now."
    msg_us = "The price of " + date[1] + " is NuLL in db now."
    err = "Something wrong. Stop and call manager pls.";
    if(!isNaN(date[0]) && !isNaN(date[1])){
        tar1.innerHTML = msg_com;
        tar2.innerHTML = msg_us;
    }else{
        tar1.innerHTML = err;
        tar2.innerHTML = err;
    }
}