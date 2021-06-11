<?php
    require('config.php');

    $item_all = array("CJ_COPPER", "LON_COPPER", "CJ_ALU", "CJ_ZINC", "HOT_RSS", "SCREW", "SPRING", "PA", "PP", "LON_NICKEL", "NY_BASEOIL", "GOLD", "US_NEWHOUSE");
    $item_c = array("RMB/MT", "USD/MT", "RMB/MT", "RMB/MT", "NTD/MT", "RMB/MT", "RMB/MT", "USD/MT", "USD/MT", "USD/MT", "USD/MT", "NTD/CHAN", "USD/MT");

    //table for testing
    $item_q = array("CJ_COPPER_TEST", "LON_COPPER_TEST", "CJ_ALU_TEST", "CJ_ZINC_TEST", "HOT_RSS_TEST", "SCREW_TEST", "SPRING_TEST", "PA_TEST", "PP_TEST", "LON_NICKEL_TEST", "NY_BASEOIL_TEST", "GOLD_TEST");

    //define data
    $item = $_POST["item_sel_update"];
    $date = $_POST["item_date"];
    $price_this = $_POST["item_price"];
    $curr = "";

    $per_m = 4;
    $per_y = 52;
    if($item == "US_NEWHOUSE"){
        $per_m = 3;
        $per_y = 12;
    }

    $id = 0;
    $stand = 0;       
    $price_m = array();
    $price_y = array();		
    

    //define currency
    $num = 0;
    for($i = 0 ; $i < count($item_all) ; $i++){
        if($item == $item_all[$i]){
            $num = $i;
        }
    }
    $curr = $item_c[$num];

    //query
    $t_query = "SELECT ID, Date, Week_price FROM $item WHERE Date > 202000 AND Date < 202200";
    $query = $t_query; 
    $stmt = $db_pdo->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //catch a standard ID to pick 4 numbers to cal sma_m
    //or 52 numbers to cal sma_y
    for($j = 0 ; $j < count($rows) ; $j++){
        $dat = $rows[$j]['Date'];
        if($dat == $date){
            $stand = $rows[$j]['ID'];                
        }
    } 
    for($i = 0 ; $i < count($rows) ; $i++){
        $id = $rows[$i]['ID'];
        $dat = $rows[$i]['Date'];
        $price = $rows[$i]['Week_price'];            
        if($id < $stand && $id > ($stand-4)){
            array_push($price_m, $price);
        }
        if($id < $stand && $id > ($stand-52)){
            array_push($price_y, $price);
        }
            
    }
    array_push($price_m, $price_this);
    array_push($price_y, $price_this);    

    $sma_m = sma($price_m, $per_m);
    $sma_y = sma($price_y, $per_y);

    $result = array();
    
    array_push($result, $item, $date, $price_this, $curr, $sma_m, $sma_y);
    com_update($result);


    $t_query = "SELECT * FROM $item WHERE Date > 202100 AND Week_PRICE is not null order by ID desc limit 1";
    $query = $t_query;
    $stmt = $db_pdo->prepare($query);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    $result_query = json_encode($rows);
    echo $result_query;

    

    function sma($p, $period){
        $per = $period;    
        $price = array();
        $j = 0;
    
        while ($j < count($p)) {
            array_push($price, $p[$j]);
            $j++;
        }
        $sum = 0;
        $count = 0;
    
        for ($i = 0; $i < $per; $i++) {
            if ($price[$i] == NULL) {
                $price[$i] = 0;
            }
            $sum += $price[$i];
            $count++;
            if ($price[$i] == NULL) {
                $count--;
            }
            $temp_r = $sum / $count;
            $temp_r = sprintf('%.2f', $temp_r);
        }
    
        return $temp_r;
    }
    
    // using to update, just need var to working
    function com_update($arr){
        //$item_q[$z], $date, $item_p[$z], $item_c[$z], $sma_m, $sma_y
        require('config.php');
        $data = array();
        for($i = 0; $i < count($arr); $i++){
            array_push($data, $arr[$i]);
        }
        if($data[0] == "US_NEWHOUSE"){
            $t_query = "UPDATE $data[0] SET Week_price = $data[2], Currency = '$data[3]', SMA_season = $data[4], SMA_year = $data[5] WHERE Date = $data[1]";
        }else{
            $t_query = "UPDATE $data[0] SET Week_price = $data[2], Currency = '$data[3]', SMA_month = $data[4], SMA_year = $data[5] WHERE Date = $data[1]";
        }
        //					item					  item_p			                            sma_m				
        //$t_query = "UPDATE $data[0] SET Week_price = $data[2], Currency = '$data[3]', SMA_month = $data[4], SMA_year = $data[5] WHERE Date = $data[1]";
        $query = $t_query;
        //echo $query . "<br>";
        $stmt = $db_pdo->prepare($query);
        $stmt->execute();        
    }
?>