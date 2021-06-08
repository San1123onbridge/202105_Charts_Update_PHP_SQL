<?php   
    require_once('config.php');

    $item = array("CJ_COPPER", "LON_COPPER", "CJ_ALU", "CJ_ZINC", "HOT_RSS", "SCREW", "SPRING", "PA", "PP", "LON_NICKEL", "NY_BASEOIL", "GOLD");
    $item_c = array("RMB/MT", "USD/MT", "RMB/MT", "RMB/MT", "NTD/MT", "RMB/MT", "RMB/MT", "USD/MT", "USD/MT", "USD/MT", "USD/MT", "NTD/CHAN");
    $item_q = array("CJ_COPPER_TEST", "LON_COPPER_TEST", "CJ_ALU_TEST", "CJ_ZINC_TEST", "HOT_RSS_TEST", "SCREW_TEST", "SPRING_TEST", "PA_TEST", "PP_TEST", "LON_NICKEL_TEST", "NY_BASEOIL_TEST", "GOLD_TEST");
    $us = array("US_NEWHOUSE");
    //$date_com = $_POST['date_com'];
    //$date_us = $_POST['date_us'];
    //$us_p = $_POST['US_NEWHOUSE'];

    //$date = $_POST["date_com"]; 
	//echo "date: " . $date . "<br>";
       
    /*
    for($z = 0 ; $z < count($item) ; $z++){ 
        $per_m = 4;
        $per_y = 52;
        $id = 0;
        $stand = 0;       
        $price_m = array();
        $price_y = array();		
		$item_p = $_POST[$item[$z]]; 
		echo "item_price: " . $item_p . "<br>";

        $t_query = "SELECT ID, Date, Week_price FROM $item[$z] WHERE Date > 202000 AND Date < 202200";
        $query = $t_query;
        echo $query . "<br>";
        $stmt = $db_pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
			echo "hey price is $item_p <br>";
            array_push($price_m, $item_p);
            array_push($price_y, $item_p);        
            $sma_m = sma($price_m, $per_m);
            $sma_y = sma($price_y, $per_y);
            echo "sma_m is $sma_m <br>" . "sma_y is $sma_y <br>";

			$result = array();
			echo "$item_q[$z] is " . $_POST[$item[$z]] . "<br>";
			array_push($result, $item_q[$z], $date, $item_p, $item_c[$z], $sma_m, $sma_y);
			echo json_encode($result) . "<br>";
			com_update($result);
    }  
    */
    $_POST["date_us"] = 202104;
    $date_us = 202104;
    $_POST["US_NEWHOUSE"] = 5000;
    $us_p = 5000;

    if(($_POST["date_us"] !== 0) && ($_POST["US_NEWHOUSE"] !== 0)){
        $date = $_POST["date_us"];
        $price_now = $_POST["US_NEWHOUSE"];        
        $stand_us = 0;
        $price_ssn = array();
        $price_y = array();

        $t_query = "SELECT ID, Date, Week_price FROM US_NEWHOUSE WHERE Date > 202000 AND Date < 202200";
        $query = $t_query;

        $stmt = $db_pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        for($b = 0 ; $b < count($rows) ; $b++){
            $dat = $rows[$b]['Date'];
            if($dat == $date){
                $stand_us = $rows[$b]['ID'];                                
            }
        }

        for($a = 0 ; $a < count($rows) ; $a++){
            $id = $rows[$a]['ID'];
            $dat = $rows[$a]['Date'];
            $price = $rows[$a]['Week_price'];            
            if($id < $stand_us && $id > ($stand_us-3)){
                array_push($price_ssn, $price);                
            }
            if($id < $stand_us && $id > ($stand_us-12)){
                array_push($price_y, $price);                
            }            
        }
        array_push($price_ssn, $price_now);
        array_push($price_y, $price_now);
        $sma_ssn_us = sma($price_ssn, 3);
        $sma_y_us = sma($price_y, 12);

        $result_us = array();
        //$item_q[$z], $date, $item_p[$z], $item_c[$z], $sma_m, $sma_y
        array_push($result_us, "US_NEWHOUSE_TEST", $date_us, $us_p, "USD/MT", $sma_ssn_us, $sma_y_us);
        echo json_encode($result_us) . "<br>";
        com_update($result_us);
    }
   
    
    
        


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

function com_update($arr){
	//$item_q[$z], $date, $item_p[$z], $item_c[$z], $sma_m, $sma_y
	require('config.php');
	$data = array();
	for($i = 0; $i < count($arr); $i++){
		array_push($data, $arr[$i]);
	}
	//					item					item_p					sma_m				
	$t_query = "UPDATE $data[0] SET Week_price = $data[2], Currency = '$data[3]', SMA_month = $data[4], SMA_year = $data[5] WHERE Date = $data[1]";
	$query = $t_query;
	echo $query;
	$stmt = $db_pdo->prepare($query);
	$stmt->execute();
	
}

?>