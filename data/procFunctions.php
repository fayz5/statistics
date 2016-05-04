<?php
	/*******************************************************
	This file contains functions used to form 'WHERE' part
	of the SQL query performed at the server side
	********************************************************/

	function processAgeRange($min_age, $max_age){

		$query = "AND person_info.birthday > '1900-01-01' AND date_part('YEAR', age(person_info.birthday)) >= $min_age";
		
		if (!empty($max_age)){
			$query = $query . " AND date_part('YEAR', age(person_info.birthday)) <= $max_age";
		}

		return $query;

	}

	function processSex($value){
		if ($value === "male"){
			$query = "AND person_info.sex = 0";
		}else{
			$query = "AND person_info.sex = 1";
		}
		return $query;
	}

	function processAbsencePeriod($data){
		$interval = Array(
				"d" => Array('day','days'),
				"w" => Array('week','weeks'),
				"m" => Array('month','months'),
				"y" => Array(' year',' years'),
			);

		$index = (intval($data[1]) === 1)? 0: 1;
		$query = "AND sub_info.date_of_return > sub_info.date_of_leave AND age(sub_info.date_of_return, sub_info.date_of_leave) >= '{$data[1]} {$interval[$data[0]][$index]}'::interval";
		return $query;
	}

	function processAdditionals($data){
		$query = "";
		//Aim of leave
		$aim_leave = intval($data['radio_al']);
		if(!empty($aim_leave)){
			if($aim_leave===-1){
				$query = $query . " AND sub_info.aim_leave_id > 6";
			}else{
				$query = $query . " AND sub_info.aim_leave_id = $aim_leave";
			}
		}

		//Aim of return
		$aim_return = intval($data['radio_ar']);
		if(!empty($aim_return)){
			if($aim_return===-1){
				$query = $query . " AND sub_info.qaytish_sabab_id > 3 AND sub_info.qaytish_sabab_id <20";
			}else{
				$query = $query . " AND sub_info.qaytish_sabab_id = $aim_return";
			}
		}

		//Transport type
		$transport = intval($data['radio_tp']);
		if(!empty($transport)){
			if($transport===-1){
				$query = $query . " AND sub_info.transport_type_id > 2";
			}else{
				$query = $query . " AND sub_info.transport_type_id = $transport";
			}
		}
		
		//Crime type
		$crime_id = intval($data['radio_cr']);
		if(!empty($crime_id)){
			if($crime_id===-1){
				$query = $query . " AND sub_info.ayb_jinoyat_id > 9";
			}else{
				$query = $query . " AND sub_info.ayb_jinoyat_id = $crime_id";
			}
		}

		//Special list
		$spec_list = $data['radio_sl'];
		if(!empty($spec_list)){
			$query = $query . " AND person_info.spec_schet_id = $spec_list";
		}

		//Special list
		$ex = $data['radio_ex'];
		if(!empty($ex)){
			$query = $query . " AND person_info.spec_schet_id = $ex";
		}


		return $query;
	}//processAdditionals

	function formWhereString($post){
		
		include 'vars.php';

		$regindx_uz = intval($post['fr']['regType']); 		//Determine region type from $id_list (UZB)
		$regindx_wo = intval($post['to']['regType']); 		//Determine region type from $id_list (WORLD)

		$reg_idUz = intval($post['fr']['region_id']); 		//Requested id of the region in UZB 
		$reg_idW = intval($post['to']['region_id']);  		//Requested id of the world region

		
		$min_age = $post['searchCriteria']['min_age'];		// If min(age) is set this variable will exist
		$max_age = $post['searchCriteria']['max_age'];		// If max(age) is set this variable will exist
		$sx_value = $post['searchCriteria']['radio_sx'];	// If sex is set this variable will exist
		$abs_period = $post['searchCriteria']['period'];



		$group_stat = intval($post['groupRequest']['group_by']); 

		//Form WHERE part of the SQL query initial value ""
		$where_str = ""; // Refining SEARCH

		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		
		if ($group_stat > 0){
			$where_str = $where_str . "AND {$group_list[$group_stat]} IS NOT NULL";
		}

		
		//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

		//Determine region in UZBEKISTAN
		if ( $regindx_uz > 0){
			//$regindx_uz > 0 : at least a province in Uzb was selected from the 'From' select menu
			$where_str = $where_str . " AND (person_info.{$id_list[$regindx_uz]} = $reg_idUz)";
			
		}
		//Determine country and area in the world
		if ($regindx_wo > -1){
			//$regindx_uz > 0 : at least a country was selected from the 'To' select menu tab
			$where_str = $where_str . " AND (sub_info.{$id_list[$regindx_wo]} = $reg_idW)";
		}

		
		

		//Set the Sex
		if (!empty($sx_value)){
			
			$where_str = $where_str ." ". processSex($sx_value);
		}

		//Set the age range
		if (!empty($min_age)){
			
			$where_str = $where_str ." ". processAgeRange($min_age, $max_age);
		}	

		//Set the absence period
		if (!empty($abs_period)){
			
			$where_str = $where_str ." ".  processAbsencePeriod($abs_period);

		}else{
			
			//Otherwise simply check that in all selections  date of return is greater than one for leave
			$where_str = $where_str ." AND sub_info.date_of_return > sub_info.date_of_leave"; 
		}

		//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		$add_crit = processAdditionals($post['searchCriteria']);
		//echo $add_crit;

		return $where_str . $add_crit;

	}//formWhereString

?>