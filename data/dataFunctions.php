<?php
	
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('memory_limit', '-1');
	$tables_list = array("","aim_leave","qaytish_sababi","transports","jinoyat_types","spec_schets", "snimat_ucheta");
	
	if (isset($_POST)){

		if (isset($_POST['get_list'])){
			$gl_indx = $_POST['get_list'];
			
			getGroupStatNames($tables_list[$gl_indx]);

		}else{

			createQueryString($_POST);
		}


	}

	function getGroupStatNames($table_name){
		include 'vars.php';

		$pgconn = pg_connect("host=$dbhost port=$port dbname=$dbname user=$dbuser password=$pass");

		//print_r($dbname);
		
		$query_str = "SELECT id, name FROM $table_name";
        if (!$pgconn) {
                exit;

        }else{
                
            $query_res = pg_query($pgconn, $query_str);

            $data=array();
            
			while ($row = pg_fetch_array($query_res)) {
				$id = intval($row[0]);
				$data[$id] = $row[1];
			}
			
	        pg_free_result($query_res);
            pg_close($pgconn);

            $json_data = json_encode($data);
            echo $json_data;
        }


	}			

	

	function createQueryString($post){
		
		include 'vars.php';
		include 'procFunctions.php';

		 $startDate = $post['groupRequest']['startDate'];	//Date from	
		 $endDate = $post['groupRequest']['endDate'];		//Date until
		 $dateType = $post['groupRequest']['dateType'];		//Either Date of leave or return



		$group_stat = intval($post['groupRequest']['group_by']); 

		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$group_flag = 0;
		//Form SELECT part of the SQL query initial value ""
		
		if ($group_stat > 0){
			
			$select_str = "{$group_list[$group_stat]},";
			
			$group_flag = 1; 
		}

		//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		$where_str = formWhereString($_POST); //Form the WHERE part

		$years = intval($_POST['years']);
		// if difference in years is > 2 years then grouping only by YEAR is done
		if ($years >= 2){


			
			$select_part = "SELECT $select_str date_part('year',date(sub_info.$dateType)) as YEAR, COUNT(*)";
			$from_where = sprintf(" FROM person_info JOIN sub_info ON sub_info.person_info_id=person_info.id WHERE (sub_info.$dateType >'%s') AND (sub_info.$dateType <'%s') $where_str AND person_info.region_id IS NOT NULL AND person_info.province_id IS NOT NULL",$startDate,$endDate);
			$group_part = " GROUP BY $select_str YEAR ORDER BY $select_str YEAR;";
			$query = $select_part . $from_where . $group_part;	

		}else{

			$select_part = "SELECT $select_str date_part('year',date(sub_info.$dateType)) as YEAR, date_part('month',date(sub_info.$dateType)) as MONTH, COUNT(*)";
			$from_where = sprintf(" FROM person_info JOIN sub_info ON sub_info.person_info_id=person_info.id WHERE (sub_info.$dateType >'%s') AND (sub_info.$dateType <'%s') $where_str AND person_info.region_id IS NOT NULL AND person_info.province_id IS NOT NULL",$startDate,$endDate);
			$group_part = " GROUP BY $select_str YEAR, MONTH ORDER BY $select_str YEAR, MONTH;";
			$query = $select_part . $from_where . $group_part;


		}
		
		$cat = createCategories($years,$startDate,$endDate);

		//Perform SQL Query
		performPgSQLquery($query,$cat,$group_flag,$years);
		
		
	}

	function createCategories($years,$startDate,$endDate){
		$cat = array();
		//Extracting Starting date and month
		
		$parts = explode('-', $startDate);
		$min_y = intval($parts[0]);
		$min_m = intval($parts[1]);
		//Extracting Ending date and month
		$parts = explode('-', $endDate);
		$max_y = intval($parts[0]);
		$max_m = intval($parts[1]);
		
		$cat_indx = 0;
		$m_indx = $min_m;
		$y_indx = $min_y;
		
		if ($years >= 2){
			do {
				$cat["$y_indx"] = $cat_indx;
				$cat_indx++;
				$y_indx++;		

			}while($y_indx<=$max_y);
			
		}else{
			do {
		
				$cat["$m_indx/$y_indx"] = $cat_indx;
				$cat_indx++;
				$m_indx++;
				if ($m_indx > 12) {
					$m_indx = 1;
					$y_indx++;
				}
			} while (($y_indx!==$max_y)||($m_indx<=$max_m));
				

		}
		return $cat;
	}


	function performPgSQLquery($query_str, $cat, $flag, $years){

		include 'vars.php';

		$pgconn = pg_connect("host=$dbhost port=$port dbname=$dbname user=$dbuser password=$pass");

        if (!$pgconn) {
                exit;

        }else{
                
            $query_res = pg_query($pgconn, $query_str);

            $data=array();
            $data['cat'] = $cat;
            // $data['query'] = $query_str;
            $year_flag = ($years >= 2) ? 2 : 4;

			switch ($flag + $year_flag) {
				case 2:
					while ($row = pg_fetch_array($query_res)) {
						$pos = $cat[$row[0]];
						$data['chart'][$pos] = $row[1];
						$data['total'] += intval($row[1]);
					}
					break;
				case 3:
					while ($row = pg_fetch_array($query_res)) {
						$pos = $cat[$row[1]];
						$data['id'][$row[0]]['chart'][$pos] = $row[2];
						$data['total'] += intval($row[2]);
					}
					break;
				case 4:
					while ($row = pg_fetch_array($query_res)) {
						$pos = $cat[$row[1]."/".$row[0]];
						$data['chart'][$pos] = $row[2];
						$data['total'] += intval($row[2]);
					}
					break;
				case 5:
					while ($row = pg_fetch_array($query_res)) {
						$pos = $cat[$row[2]."/".$row[1]];
						$data['id'][$row[0]]['chart'][$pos] = $row[3];
						$data['total'] += intval($row[3]);
					}

					break;					
			}
            
			      

	        pg_free_result($query_res);
            pg_close($pgconn);

            $json_data = json_encode($data);

            

            echo $json_data;
        }


	}//performPgSQLquery
	
?>
