<?php
	//List of the possible region id's
	$id_list = array("country_id","province_id","region_id","qshfy_id"); 
	//Columns in the SQL Database included in the Group Statistics search:
	$group_list = array("","sub_info.aim_leave_id","sub_info.qaytish_sabab_id","sub_info.transport_type_id","sub_info.ayb_jinoyat_id","person_info.spec_schet_id", "person_info.snimat_ucheta_id");

	$dbhost="localhost";
	$dbuser="postgres";
	$pass="postgres";
	$dbname="umksh";
	$port = 5432;
	$uzb_id=192;
?>