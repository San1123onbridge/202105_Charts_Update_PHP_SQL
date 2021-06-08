<?php
    require_once('config.php');

    $date = 202119;
    

    $t_query = "SELECT * FROM CJ_COPPER WHERE Date <= $date ORDER BY ID DESC LIMIT 3";
    $query = $t_query;
    $stmt = $db_pdo->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    
    //echo json_encode($rows);
    
    $data1 = array();
    $data2 = array();
    $data3 = array();    

    for($i = 0 ; $i < count($rows[0]) ; $i++){
        array_push($data1, $rows[0][$i]);
        array_push($data2, $rows[1][$i]);
        array_push($data3, $rows[2][$i]);
    }
    echo json_encode($data1);
    echo json_encode($data2);
    echo json_encode($data3);



$header = "
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

        <!-- banner end-->
";


$section = "
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
                            <th scope='row'>$data1[0]</th>
                            <td>$data1[1]</td>
                            <td>$data1[2]</td>
                            <td>$data1[3]</td>
                            <td>$data1[4]</td>
                            <td>$data1[5]</td>
                            <td>$data1[6]</td>
                        </tr>
                        <tr class='table-active'>
                            <th scope='row'>$data2[0]</th>
                            <td>$data2[1]</td>
                            <td>$data2[2]</td>
                            <td>$data2[3]</td>
                            <td>$data2[4]</td>
                            <td>$data2[5]</td>
                            <td>$data2[6]</td>
                        </tr>
                        <tr class='table-primary'>
                            <th scope='row'>$data3[0]</th>
                            <td>$data3[1]</td>
                            <td>$data3[2]</td>
                            <td>$data3[3]</td>
                            <td>$data3[4]</td>
                            <td>$data3[5]</td>
                            <td>$data3[6]</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
";

$footer = "
    </body>

    </html>"
;


    echo $header . $section . $footer;
?>
