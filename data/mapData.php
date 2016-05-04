<?php
	if (isset($_POST)){

		formMapQuery($_POST);
	
	}

	function formMapQuery($post){
		include 'vars.php';
		include 'procFunctions.php';

		$startDate = $post['groupRequest']['startDate'];	//Date from	
		$endDate = $post['groupRequest']['endDate'];		//Date until
		$dateType = $post['groupRequest']['dateType'];		//Either Date of leave or return

		$where_str = formWhereString($post); //Form the WHERE part
		 
		$query_uzb = sprintf("SELECT person_info.province_id AS province,  COUNT(*) AS value FROM person_info JOIN sub_info ON sub_info.person_info_id=person_info.id WHERE (sub_info.$dateType >'%s') AND (sub_info.$dateType <'%s') $where_str AND sub_info.date_of_return > sub_info.date_of_leave AND person_info.region_id IS NOT NULL AND person_info.province_id IS NOT NULL GROUP BY province ORDER BY province",$startDate,$endDate);
		$query_w = sprintf("SELECT countries.alpha3 AS code,  COUNT(*) AS value FROM person_info JOIN sub_info ON sub_info.person_info_id=person_info.id JOIN countries ON sub_info.country_id=countries.id WHERE (sub_info.$dateType >'%s') AND (sub_info.$dateType <'%s') $where_str AND sub_info.date_of_return > sub_info.date_of_leave AND sub_info.country_id IS NOT NULL GROUP BY code ORDER BY code",$startDate,$endDate);

		performPgSQLquery($query_uzb, $query_w);
		// echo $query_w+'<br>'+$query_uzb;
	}

	function performPgSQLquery($query_uzb,$query_w){
		include 'vars.php';

		$pgconn = pg_connect("host=$dbhost port=$port dbname=$dbname user=$dbuser password=$pass");

		//print_r($dbname);
		

        if (!$pgconn) {
            exit;

        }else{

        	

        	$data=array();
        	// $data['query'] = $query_uzb;
        	$query_res = pg_query($pgconn, $query_uzb);
        	while ($row = pg_fetch_assoc($query_res)) {
				$data['uzb'][] = array('province_id' => $row['province'], 'value'=>$row['value']);
			}

			$query_res = pg_query($pgconn, $query_w);
			while ($row = pg_fetch_assoc($query_res)) {
				$data['wo'][] = $row;
			}


			pg_free_result($query_res);
            pg_close($pgconn);

            $json_data = json_encode($data);

            

            echo $json_data;
        }
	}

?>