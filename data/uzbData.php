<?php
    error_reporting(E_ALL ^ E_NOTICE);
    if (isset($_POST)){

        include 'vars.php';

        //Default provinces in Uzbekistan
        if (isset($_POST['country_id'])){
            $query = "SELECT id, name FROM provinces WHERE country_id=$uzb_id ORDER BY id";
        }

        
        //If POST request received with PROVINCE_ID:
        if (isset($_POST['province_id'])){
            $province=$_POST['province_id'];
            $query_2 = "SELECT id, name FROM regions WHERE province_id=$province ORDER BY id";
            $query=$query_2;   
        } 
        //If POST request received with PROVINCE_ID:
        if (isset($_POST['region_id'])){
            $region=$_POST['region_id'];
            $query_3 = "SELECT id, name FROM qshfy WHERE region_id=$region ORDER BY id";
            $query=$query_3;
        }
        

        if ($query){
            $pgconn = pg_connect("host=$dbhost port=$port dbname=$dbname user=$dbuser password=$pass");


            if (!$pgconn) {
                exit;

            }
            else{
                $query_res=pg_query($pgconn, $query);

                $customer=array();
                while ($row = pg_fetch_assoc($query_res)) {
                    $customer[] = $row;
                }


                $json_query = json_encode($customer);

                pg_free_result($query_res);
                pg_close($pgconn);

                echo $json_query;
            }


        }
        else{
            echo json_encode(array('response' => 'not processed'));
        }



    }
        

?>