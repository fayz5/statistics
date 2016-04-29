<?php
	
	error_reporting(E_ALL ^ E_NOTICE);
	
    if (isset($_POST)){
    
        include 'vars.php';

        $query = "SELECT id, name FROM countries WHERE id<>$uzb_id";

    
        if (isset($_POST['country_id'])){
                $cid=$_POST['country_id'];
                $query_regions = "SELECT id, name FROM provinces WHERE country_id=$cid ORDER BY id";
                $query=$query_regions;
            }
        if (isset($_POST['province_id'])){
                $pr_id=$_POST['province_id'];
                $query_2 = "SELECT id, name, sp_region FROM regions WHERE province_id=$pr_id ORDER BY id";
                $query=$query_2;
            }

        if (!isset($_POST['region_id'])){
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