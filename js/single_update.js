function show_table(){
    $.ajax({
            url: "./php/single_show_table.php",            
            type: "POST",
            dataType: "json",
            data: $("#show_table_form").serialize(),
            async: "false",
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
    
            success: function (temp_data) {
                //console.log(temp_data);                
                var title_tw = find_chinese($("select[name='show_sel']").val());                
                get_data(temp_data, title_tw);                
            }
        })    
}

function single_update(){
    $.ajax({
        url: "./php/single_update.php",            
        type: "POST",
        dataType: "json",
        data: $("#single_update_form").serialize(),
        async: "false",
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },

        success: function (temp_data) {
            console.log(temp_data);
            var title_tw = find_chinese($("select[name='item_sel_update']").val()); 
            get_data_update(temp_data[0], title_tw);
                         
        }
    })  
}

function get_data(temp_data, title){
    var dis = document.getElementById("msg_area");
    dis.style.display = 'block';
    var target = document.getElementById("item_title");
    target.innerHTML = title;
    
    createTableRED(temp_data[0]);
    createTableCommon(temp_data[1], 2);
    createTableCommon(temp_data[2], 3);

    function createTableRED(data){
        var tableData = "<tr class='table-danger'>"
        for(var i = 0 ; i < data.length ; i++){
            tableData += "<td>" + data[i] + "</td>";
        }
        tableData += "</tr>";
        $("#data1").html(tableData);
    }
    function createTableCommon(data, num){
        var tableData = "<tr class='table-active'>"
        for(var i = 0 ; i < data.length ; i++){
            tableData += "<td>" + data[i] + "</td>";
        }
        tableData += "</tr>";
        var str = "#data" + num; 
        $(str).html(tableData);
    }
      
}

function get_data_update(temp_data, title){
    var dis = document.getElementById("msg_area2");
    dis.style.display = 'block';
    var target = document.getElementById("item_title2");
    target.innerHTML = title;
    
    createTableRED(temp_data);
    
    function createTableRED(data){
        var tableData = "<tr class='table-danger'>"
        for(var i = 0 ; i < data.length ; i++){
            tableData += "<td>" + data[i] + "</td>";
        }
        tableData += "</tr>";
        $("#datau1").html(tableData);
    }    
}

function find_chinese(title){    
    var item_all = ["CJ_COPPER", "LON_COPPER", "CJ_ALU", "CJ_ZINC", "HOT_RSS", "SCREW", "SPRING", "PA", "PP", "LON_NICKEL", "NY_BASEOIL", "GOLD", "US_NEWHOUSE"];
    var item_tw = ["長江銅", "倫敦銅", "長江鋁", "長江鋅", "熱軋版", "螺絲", "彈簧", "PA", "PP", "倫敦鎳", "紐約原油", "黃金", "美國新屋"];
    var num = 0;
    for(var i = 0 ; i < item_all.length ; i++){
        if(title == item_all[i]){
            num = i;
        }
    }
    return item_tw[num];
}