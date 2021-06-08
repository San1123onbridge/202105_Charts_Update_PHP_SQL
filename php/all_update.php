<?php
require_once('config.php');

    //original object
    $item = array("CJ_COPPER", "LON_COPPER", "CJ_ALU", "CJ_ZINC", "HOT_RSS", "SCREW", "SPRING", "PA", "PP", "LON_NICKEL", "NY_BASEOIL", "GOLD");
    //object currency
    $item_c = array("RMB/MT", "USD/MT", "RMB/MT", "RMB/MT", "NTD/MT", "RMB/MT", "RMB/MT", "USD/MT", "USD/MT", "USD/MT", "USD/MT", "NTD/CHAN");
    //testing object
    $item_q = array("CJ_COPPER_TEST", "LON_COPPER_TEST", "CJ_ALU_TEST", "CJ_ZINC_TEST", "HOT_RSS_TEST", "SCREW_TEST", "SPRING_TEST", "PA_TEST", "PP_TEST", "LON_NICKEL_TEST", "NY_BASEOIL_TEST", "GOLD_TEST");
    //testing object for us_newhouse
    $us = array("US_NEWHOUSE");    

    //date for common item
    $date = $_POST["date_com"]; 
	echo "Common date: " . $date . "<br>";
       
	//looping 12 items for quering price of each to calucator sma_m and sma_y
    //then throw into update function
    for($z = 0 ; $z < count($item) ; $z++){ 
        $per_m = 4;
        $per_y = 52;
        $id = 0;
        $stand = 0;       
        $price_m = array();
        $price_y = array();		
		$item_p = $_POST[$item[$z]]; 
		echo "item_price: " . $item_p . "<br>";

        //need to improve the dead number 202000 202200
        $t_query = "SELECT ID, Date, Week_price FROM $item[$z] WHERE Date > 202000 AND Date < 202200";
        $query = $t_query;
        echo $query . "<br>";
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
			
            array_push($price_m, $item_p);
            array_push($price_y, $item_p);    

            $sma_m = sma($price_m, $per_m);
            $sma_y = sma($price_y, $per_y);

            echo "sma_m is $sma_m <br>" . "sma_y is $sma_y <br>";

			$result = array();

			array_push($result, $item_q[$z], $date, $item_p, $item_c[$z], $sma_m, $sma_y);
			echo json_encode($result) . "<br>";
			com_update($result);
    }  
    
	
    $date_us = $_POST["date_us"];
	echo "US date: " . $date_us . "<br>";    
    $us_p = $_POST["US_NEWHOUSE"];

    //the filter to catch us_newhouse item
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
        array_push($result_us, "US_NEWHOUSE_TEST", $date_us, $price_now, "USD/MT", $sma_ssn_us, $sma_y_us);
        echo json_encode($result_us) . "<br>";
        com_update($result_us);
    }
    
    
        

// cal sma, need price and period
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
	//					item					  item_p			                            sma_m				
	$t_query = "UPDATE $data[0] SET Week_price = $data[2], Currency = '$data[3]', SMA_month = $data[4], SMA_year = $data[5] WHERE Date = $data[1]";
	$query = $t_query;
	echo $query . "<br>";
	$stmt = $db_pdo->prepare($query);
	$stmt->execute();
	
}

function show_result(){
    
}    
    
   



/*

    

    $table = "
    <html>

    <head>
        <title>HeavyPower - Updating</title>
        <meta name='viewpoint' charset='UTF-8' content='width=device-width, initial-scale=1'>
        <link href='../bootstrap/bootstrap.min.css' rel='stylesheet'>
        <link href='../css/global.css' rel='stylesheet'>
    </head>

    <body>
        <!-- navbar start -->

        <div>
            <nav class='navbar navbar-expand-lg navbar-dark bg-primary'>
                <div class='container-fluid'>
                    <a class='navbar-brand' href='#'>Heavypower</a>
                    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarColor01'
                        aria-controls='navbarColor01' aria-expanded='false' aria-label='Toggle navigation'>
                        <span class='navbar-toggler-icon'></span>
                    </button>

                    <div class='collapse navbar-collapse' id='navbarColor01'>
                        <ul class='navbar-nav me-auto'>
                            <li class='nav-item'>
                                <a class='nav-link' href='index.html'>Charting</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link active' href='all_updating.html'>Updating</a>
                            </li>
                            </li>
                        </ul>
                        <form class='d-flex'>
                            <input class='form-control me-sm-2' type='text' placeholder='Search'>
                            <button class='btn btn-secondary my-2 my-sm-0' type='submit'>Search</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

        <!-- navbar end -->

        <!-- banner start -->

        <div class='container'>
            <div class='main'>
                <h1>Result</h1>
            </div>
        </div>
        <div class='container'>
            <div class='main'>
                <h1>CJ_COPPER</h1>
                <table class='table table-hover'>
                    <thead>
                        <tr>
                            <th scope='col'>ID</th>
                            <th scope='col'>Date</th>
                            <th scope='col'>Week_price</th>
                            <th scope='col'>Currency</th>
                            <th scope='col'>SMA_month</th>
                            <th scope='col'>SMA_year</th>
                            <th scope='col'>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='table-danger'>
                            <th scope='row'>input result</th>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                        </tr>
                        <tr class='table-active'>
                            <th scope='row'>history</th>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                        </tr>
                        <tr class='table-primary'>
                            <th scope='row'>histroy</th>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                            <td>Column content</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- banner end-->

    </body>

    </html>
        ";



    echo "$table";

*/
    
?>