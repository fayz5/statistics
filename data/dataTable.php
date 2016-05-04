<?php
	
	if (isset($_POST)){

		if (isset($_POST['offset_val'])){


			tableData($_POST);

		}


	}

	function tableData($post){


		include 'vars.php';
		include 'procFunctions.php';

		$startDate = $post['groupRequest']['startDate'];	//Date from	
		$endDate = $post['groupRequest']['endDate'];		//Date until
		$dateType = $post['groupRequest']['dateType'];		//Either Date of leave or return

		$offset = $post['offset_val'];
		$limit = $post['step'];

		
		$where_str = formWhereString($post); //Form the WHERE part
		

		$query = sprintf("SELECT person_info.id  AS id, person_info.surname AS sname, person_info.name, person_info.middle_name AS mid, person_info.passport_cn AS pass, provinces.name AS prov, regions.name as reg, person_info.street AS str FROM person_info
			JOIN sub_info ON sub_info.person_info_id=person_info.id
			JOIN regions ON person_info.region_id = regions.id
			JOIN provinces ON person_info.province_id = provinces.id
			WHERE (sub_info.$dateType >'%s') AND (sub_info.$dateType <'%s') $where_str
			ORDER BY person_info.id
			OFFSET $offset
			LIMIT $limit;",$startDate,$endDate); 

		// echo $query;
		performPgSQLquery($query);

	}//tableData

	function performPgSQLquery($query_str){
		include 'vars.php';

		$pgconn = pg_connect("host=$dbhost port=$port dbname=$dbname user=$dbuser password=$pass");

		//print_r($dbname);
		

        if (!$pgconn) {
                exit;

        }else{

        	$query_res = pg_query($pgconn, $query_str);

        	$data=array();
        	while ($row = pg_fetch_assoc($query_res)) {
				$data[] = $row;
			}



        	pg_free_result($query_res);
            pg_close($pgconn);

            $json_data = json_encode($data);

            

            echo $json_data;

        }
	}


?>