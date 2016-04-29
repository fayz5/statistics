<?php 
	if (isset($_GET['pid'])) {
		$pers_id = $_GET['pid'];
	}
	include('httpful.phar');
	use \Httpful\Request;
	header("Content-Type: text/html; charset=utf-8");

	$conn = pg_connect("host=127.0.0.1 port=5432 dbname=umksh user=postgres password=postgres") or die("Bazaga ulanishda muammo".pg_last_error());
	$query01 = "SELECT * FROM person_info WHERE person_info.id = ".$pers_id;
	$result01 = pg_query($query01) or die('Zaprosda muammo: ' . pg_last_error());

	while ($line = pg_fetch_array($result01, null, PGSQL_ASSOC)) {
		$person_id = $line["id"];
		$name = $line["name"];		
		$apartment = $line["apartment"];
		$asos = $line["asos"];	
		$authority = $line["authority"];	
		$birthplace = $line["birthplace"];	
		$birthday = $line["birthday"];	
		$date_of_issue = $line["date_of_issue"];
		$dateupdate = date("Y-m-d H:i:s");	
		$date_of_snimat = $line["date_of_snimat"];	
		$home = $line["home"];
		$homephone = $line["homephone"];
		$middle_name = $line["middle_name"];
		$mobile = $line["mobile"];
		$passport_cn = $line["passport_cn"];
		$pk = $line["pk"];
		$position = $line["position"];
		$sex = $line["sex"];
		$street = $line["street"];
		$surname = $line["surname"];
		$work_place = $line["work_place"];
		$deport_id = $line["deport_id"];
		$marital_status_id = $line["marital_status_id"];
		$mfy_id = $line["mfy_id"];
		$nationality_id = $line["nationality_id"];
		$prof_type_id = $line["prof_type_id"];
		$province_id = $line["province_id"];
		$qshfy_id = $line["qshfy_id"];
		$region_id = $line["region_id"];
		$religion_id = $line["religion_id"];
		$snimat_ucheta_id = $line["snimat_ucheta_id"];
		$spec_schet_id = $line["spec_schet_id"];
		$sudimost_id = $line["sudimost_id"];
		$user_id = $line["user_id"];
		$guvohnoma_cn = $line["guvohnoma_cn"];

		$birth_for = date('Y-m-d', strtotime($line["birthday"]));
		$pc = substr($line["passport_cn"],0,2);
		$pn = substr($line["passport_cn"],3,7);
	}
	pg_free_result($result01);
	if ($sex == 0){$sex='Erkak';} else {$sex = 'Ayol';}
	$query02 = "SELECT sub_info.id as sub_id, sub_info.address, sub_info.birgaketganlar, sub_info.comment, sub_info.commentqidiruv, sub_info.date_of_leave,
	sub_info.date_of_return, sub_info.homephone as sub_homephone, sub_info.passportyuqotgan, sub_info.rasmiyqidiruvda, sub_info.takrorketgan, sub_info.aim_leave_id,
	sub_info.ayb_jinoyat_id, sub_info.country_id as sub_country_id, sub_info.ishga_joylash_id, sub_info.jabr_jinoyat_id, sub_info.ketgan_post_id, sub_info.person_info_id,
	sub_info.province_id as sub_province_id, sub_info.qaytish_sabab_id, sub_info.region_id as sub_region_id, sub_info.tezkor_qamrov_id, sub_info.tibbiy_korik_id,
	sub_info.time_leave_id, sub_info.transport_type_id, sub_info.work_type_id FROM sub_info WHERE person_info_id = ".$pers_id;
	
	$result02 = pg_query($query02) or die('Zaprosda muammo: ' . pg_last_error());
	$i = 1;
	while ($line = pg_fetch_array($result02, null, PGSQL_ASSOC)) {		
		$sub_id[$i] = $line["sub_id"];
		$address[$i] = $line["address"];
		$birgaketganlar[$i] = $line["birgaketganlar"];
		$comment[$i] = $line["comment"];
		$commentqidiruv[$i] = $line["commentqidiruv"];
		$date_of_leave[$i] = $line["date_of_leave"];
		$date_of_return[$i] = $line["date_of_return"];
		$sub_homephone[$i] = $line["sub_homephone"];
		$passportyuqotgan[$i] = $line["passportyuqotgan"];
		$rasmiyqidiruvda[$i] = $line["rasmiyqidiruvda"];
		$takrorketgan[$i] = $line["takrorketgan"];
		$aim_leave_id[$i] = $line["aim_leave_id"];
		$ayb_jinoyat_id[$i] = $line["ayb_jinoyat_id"];
		$sub_country_id[$i] = $line["sub_country_id"];
		$ishga_joylash_id[$i] = $line["ishga_joylash_id"];
		$jabr_jinoyat_id[$i] = $line["jabr_jinoyat_id"];
		$ketgan_post_id[$i] = $line["ketgan_post_id"];
		$person_info_id[$i] = $line["person_info_id"];
		$sub_province_id[$i] = $line["sub_province_id"];
		$qaytish_sabab_id[$i] = $line["qaytish_sabab_id"];
		$sub_region_id[$i] = $line["sub_region_id"];
		$tezkor_qamrov_id[$i] = $line["tezkor_qamrov_id"];
		$tibbiy_korik_id[$i] = $line["tibbiy_korik_id"];
		$time_leave_id[$i] = $line["time_leave_id"];
		$transport_type_id[$i] = $line["transport_type_id"];
		$work_type_id[$i] = $line["work_type_id"];
		$date_of_leave1[$i] = date('d.m.Y', strtotime($date_of_leave[$i]));
		$date_of_return1[$i] = date('d.m.Y', strtotime($date_of_return[$i]));
		$i++;
	}
	pg_free_result($result02);

	$birthday1 = date('d.m.Y', strtotime($birthday));
	$date_of_issue1 = date('d.m.Y', strtotime($date_of_issue));
	$date_of_snimat1 = date($date_of_snimat);
	$person_id1 = $person_id;

//----------------
	$url = 'http://172.250.1.206:9876/KO/api/Citizen';
//$url = 'https://mandrillapp.com/api/1.0/messages/send.json';
	$data = array( 
		'P_COUNT' => 50,
		'P_PAGE' => 1,
		'P_LN' => 0,
		'P_NAME' => '',//$name1,
		'P_SURNAME' => '',//$surname1,
		'P_SERYZAGS' => '',
		'P_NUMBERZAGS' => '',
		'P_BIRTHPLACE' => '',
		'P_PATRONYM' => '',//$middlename1,
		//'P_BIRTHTILL'=> $birth,
		'P_BIRTHTILL'=>"",// $birth_for,
		'P_BIRTHFROM'=>"",// $birth_for,
		'P_SERY'=> $pc,
		'P_NUMBER'=> $pn,
		'P_PK'=> ''
	);
	$response = Request::post($url)->sendsJson()->body($data)->send();
	foreach ($response->body as $key){
		$ID = $key->TB_ID;
	}
	//$pk = 32107955920025;
	//$pk = 30911910760029;
	//$pk = 31809840250071;
	$data['P_SERY']="";
	$data['P_NUMBER']="";
	$data['P_PK']=$pk;


	
	$k = 0; $j = 0;
	if(!empty($pk)){
		$response = Request::post($url)->sendsJson()->body($data)->send();
		foreach ($response->body as $key){
			//$ID_form[$k] = $key->TB_ID;
			$name_form[$k] = $key->TB_NAMEL;
			$surname_form[$k] = $key->TB_SURNAMEL;
			$mid_form[$k] = $key->TB_PATRONYML;
			//$bd_form[$k] = $key->TB_BIRTH;
			$doc_form[$k] = $key->TBDOCTYPE;
			$ps_form[$k] = $key->TB_SERY;
			$pn_form[$k] = $key->TB_NUMBER;
			$beg_form[$k] = $key->TB_DATEBEG;
			$end_form[$k] = $key->TB_DATEEND;
			$issued_form[$k] = $key->TB_ISSUEDBY;
			$k++;
		}

		$cheg_query = "SELECT chegara.doc_seria, chegara.doc_number, chegara.direction_type_id, countries.name as cheg_country, chegara.reg_date FROM chegara, countries WHERE chegara.direction_country_id = CAST(countries.sp_country AS varchar) AND pinpp = '".$pk."' ORDER BY reg_date DESC";
		// echo $cheg_query."<br>";
		$cheg_res = pg_query($cheg_query) or die('Zaprosda muammo: ' . pg_last_error());
		
		while ($cheg_row = pg_fetch_array($cheg_res, null, PGSQL_ASSOC)) {	
			$doc_seria[$j] = $cheg_row['doc_seria'];
			$doc_number[$j] = $cheg_row['doc_number'];
			$direction_type[$j] = $cheg_row['direction_type_id'];
			$cheg_country[$j] = $cheg_row['cheg_country'];
			$cheg_date[$j] = $cheg_row['reg_date'];
			$j++;
		}
		// if ($j>0){
		// 	echo $cheg_country;	
		// }else{
		// 	echo "Chegarada Ma'lumot Yo'q".'<br>';
		// }
		
	}
	
//------------
?>

<html>
	<head>
		<title>
		<?php echo $surname.' '.$name;?>
		</title>
		<style>
		@page{
		size:A4;
		margin: 10px;
		}
		@media print{
			html, body{
				width:210mm;
				height:297mm;
			}
		}
		h2{margin-top:-10px,0;
		}
		</style>
		<link href="../styles/bootstrap.min.css" rel="stylesheet">
		 <script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script> 

		<!--<script src="../js/jquery.printPage.js" type="text/javascript"></script>
		-->
		<script src="../js/bootstrap.min.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="container" style="padding:5px">
					
			<div class="row table-responsive">
				
				<div class="col-xs-9">
					<table class="table table-condensed">
						<tr class="info"><td colspan="3"><p class='h3' style="margin-top:5px;"><b>Shaxs haqida malumotlar</b></p></td></tr>
						<tr>
							<td><b>Familiyasi: </b><br><?php if(isset($surname)) {echo $surname;} else {echo "-";}?></td>
							<td><b>Ismi:</b> <br><?php if(isset($name)) {echo $name;} else {echo "-";}?></td>
							<td><b>Sharifi: </b><br><?php if (isset($middle_name)) {echo $middle_name;} else {echo "-";}?></td>
						</tr>	
						<tr>
							<td><b>Tug'ilgan sanasi:</b> <br><?php if(isset($birthday1)){echo $birthday1;} else {echo "-";}?></td>
							<td><b>Pasporti:</b> <br><?php if(isset($passport_cn)){echo $passport_cn;} else {echo "-";}?></td>
							<td><b>Tug'ilganlik guvohnomasi: </b><br><?php if(isset($guvohnoma_cn) && !empty($guvohnoma_cn)){echo $guvohnoma_cn;} else {echo "-";}?></td>
						</tr>
						<tr>
							<td><b>Jinsi:</b> <br><?php if(isset($sex)){echo $sex;} else {echo "-";}?></td>
							<td><b>Berilgan vaqti: </b><br><?php if(isset($date_of_issue1) AND $date_of_issue1!='01.01.1970'){echo $date_of_issue1;} else {echo "-";}?></td>
							<td><b>Kim tomonidan berilgan:</b> <br><?php if(isset($authority) AND !empty($authority)){echo $authority;} else {echo "-";}?></td>
						</tr>
						<tr>
							<td><b>PK(14 ta raqam):</b> <br><?php if(isset($pk)){echo $pk;} else {echo "-";}?></td>
							<td><b>Tug'ilgan joyi: </b><br><?php if(isset($birthplace)){echo $birthplace;} else {echo "-";}?></td>
							<td><b>Millati:</b><br>
								<?php if(isset($nationality_id)){
									$query = 'SELECT * FROM nationalities WHERE id='.$nationality_id;
									$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
									while ($line = pg_fetch_assoc($result)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
						</tr>
						<tr>
							<td><b>Oilaviy ahvoli:</b><br>
								<?php 
		 							if(isset($marital_status_id)){
										$query = 'SELECT * FROM marital_status WHERE id='.$marital_status_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Diniy e'tiqodi: </b><br>
								<?php 
									if (isset($religion_id)){
										$query = 'SELECT * FROM religions WHERE id='.$religion_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}  else {echo "-";}
								?>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><b>Ish va o'qish joyi:</b><br>
								<?php if(isset($work_place) AND !empty($work_place)){echo $work_place;} else {echo "-";}?>
							</td>
							<td><b>Lavozimi: </b><br>
								<?php if(isset($position) AND !empty($position)){ echo $position;} else {echo "-";}?>
							</td>
							<td><b>Prof. toifasi: </b><br>
								<?php 
									if (isset($prof_type_id)){
										$query = 'SELECT * FROM prof_types WHERE id='.$prof_type_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}else{echo "-";}
								?>
							</td>
						</tr>
						<tr>
							<td><b>Sudlanganligi:</b><br>
								<?php 
									if (isset($sudimost_id)){
										$query = 'SELECT * FROM sudimost WHERE id='.$sudimost_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Maxsus (DEO)hisob: </b><br>
								<?php 
									if (isset($spec_schet_id)){
										$query = 'SELECT * FROM spec_schets WHERE id='.$spec_schet_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}  else {echo "-";}
								?>
							</td>
							<td></td>
						</tr>

						<tr>
							<td><b>Viloyat: </b><br>
								<?php 
									if (isset($province_id)){
										$query = 'SELECT * FROM provinces WHERE id='.$province_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}  else {echo "-";}
								?>
							</td>
							<td><b>Tuman, shahar: </b><br>
								<?php 
									if (isset($region_id)){
										$query = 'SELECT * FROM regions WHERE id='.$region_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}  else {echo "-";}
								?>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><b>QSHFY: </b><br>
								<?php 
									if (isset($qshfy_id)){
										$query = 'SELECT * FROM qshfy WHERE id='.$qshfy_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}  else {echo "-";}
								?>

							</td>
							<td><b>MFY: </b><br>
								<?php 
									if (isset($mfy_id)){
										$query = 'SELECT * FROM mfy WHERE id='.$mfy_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}  else {echo "-";}
								?>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><b>Ko'cha: </b><br><?php if(isset($street) AND !empty($street)){echo $street;}  else {echo "-";}?></td>
							<td><b>Uy: </b><br><?php if(isset($home) AND !empty($home)){echo $home;}  else {echo "-";}?></td>
							<td><b>Xona: </b><br><?php if(isset($apartment) AND !empty($apartment)){echo $apartment;}  else {echo "-";}?></td>
							
						</tr>
						<tr>
							<td><b>Telefoni(uy): </b><br><?php if(isset($phone) AND !empty($phone)){echo $phone;} else {echo "-";}?></td>
							<td><b>Uyali tel: </b><br><?php if(isset($mobile) AND !empty($mobile)){echo $mobile;}  else {echo "-";}?></td>
							<td></td>
						</tr>
						<tr>
							<td><b>Hisobdan chiqarildi: <br></b>
								<?php 
									if (isset($snimat_ucheta_id)){
										$query = 'SELECT * FROM snimat_ucheta WHERE id='.$snimat_ucheta_id;
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Sanasi: <br></b><?php if(isset($date_of_snimat1) AND !empty($date_of_snimat1)){echo $date_of_snimat1;} else {echo "-";}?></td>
							<td><b>Hisobdan chiq. asosi: <br></b><?php if(isset($asos) AND !empty($asos)){ echo $asos;} else {echo "-";}?></td>

						</tr>
						<!-- FORMA 1 -->
						<tr class="info">
							<td colspan="3"><p class="h3" style="margin-top:5px;">Forma 1-dan olingan ma'lumot</p></td>
						</tr>
						<?php
							if($k>0){
								for($i=0; $i<$k; $i++){
								
						?>
						<tr class="active">
							<td><p class="h4"><?php echo "Passport: $ps_form[$i] $pn_form[$i]"?></p></td>
							<td colspan="2"><p class="h4"><?php if(isset($doc_form[$i])){echo $doc_form[$i];}else{echo "-";} ?></p></td>
						</tr>
						<tr>
							<td><b>Familiyasi: </b><br><?php if(isset($surname_form[$i])) {echo $surname_form[$i];} else {echo "-";}?></td>
							<td><b>Ismi:</b> <br><?php if(isset($name_form[$i])) {echo $name_form[$i];} else {echo "-";}?></td>
							<td><b>Sharifi: </b><br><?php if (isset($mid_form[$i])) {echo $mid_form[$i];} else {echo "-";}?></td>
						</tr>
						<tr>
							<td><b>Berilgan vaqti: </b><br><?php if(isset($beg_form[$i]) AND substr($beg_form[$i], 0,10)!='1970-01-01'){echo substr($beg_form[$i], 0,10);} else {echo "-";}?></td>
							<td><b>Amal qilish muddati: </b><br><?php if(isset($end_form[$i]) AND substr($end_form[$i], 0,10)!='01.01.1970'){echo substr($end_form[$i], 0,10);} else {echo "-";}?></td>
							<td><b>Kim tomonidan berilgan:</b> <br><?php if(isset($issued_form[$i]) AND !empty($issued_form[$i])){echo $issued_form[$i];} else {echo "-";}?></td>
						</tr>
						<?php
								}
							}else{//if($k>0)
							
						?>
						<tr>
							<td colspan="3"><b> Forma 1-dan ma'lumot topilmadi</b></td>
						</tr>	
						<?php
							
							}//for($i)
						?>
						

					</table>
				</div>
				<div class="col-xs-3 col-xs-print-12">
					<img class="col-xs-4" style="width:180px;" <?php if (isset($ID)){?> src="photo.php?imagekod=<?php echo $ID;?>" <?php } else {?> src="images/User1.png" <?php }?> title="RASM" id="rasm" size=100px border="1">
				</div>
				
			</div>
			
			<div class="row table-responsive">
				<div class="col-xs-9">
					<table class="table table-condensed">
						<!-- CHEGARA -->

						<tr class="info">
							<td colspan="4"><p class="h3" style="margin-top:5px;">Chegara ma'lumotlari</p></td>
						</tr>
						<?php
							if($j>0){
								for($i=0; $i<$j; $i++){
								
						?>
						<tr class="active">
							<td colspan="1"><p class="h4"><?php echo "Passport: $doc_seria[$i] $doc_number[$i]";?></p></td>
							<td colspan="3"><p class="h4"><?php echo "PK: $pk";?></p></td>
						</tr>
						<tr>
							<td><b>Kesib O'tish: </b><br><?php if($direction_type[$i]=='0') {echo "KIRISH";} else {echo "CHIQISH";}?></td>
							<td colspan="2"><b>Yo'nalish:</b> <br><?php if(isset($cheg_country[$i])) {echo $cheg_country[$i];} else {echo "-";}?></td>
							<td><b>Vaqti: </b><br><?php if (isset($cheg_date[$i])) {echo $cheg_date[$i];} else {echo "-";}?></td>
						</tr>
						
						<?php
								}
							}else{//if($k>0)
							
						?>
						<tr>
							<td colspan="3"><b> Chegaradan ma'lumot topilmadi</b></td>
						</tr>	
						<?php
								
							}//for($i)
						?>

					</table>
				</div>
			</div>
			
			<div style="display:block; height:5px;"></div>
			<div class="row table-responsive">
				<div class="col-xs-12">
					<table class="table table-condensed">
						<tr class="info"><td colspan="3" ><p class='h3' style="margin-top:5px;"><b>Qayerga ketganligi haqida ma'lumot</b></p></td></tr>
						<?php
							if(isset($sub_id)){
							for ($i=1; $i<=count($sub_id); $i++){
						?>
						<tr><td class="active" colspan="3"><p class="h4"><?php echo $i."-marta ketganligi haqida ma'lumot";?></p></td></tr>
						<tr>
							<td><b>Ketgan sanasi: </b><br><?php if(isset($date_of_leave1[$i]) AND !empty($date_of_leave1[$i])){echo $date_of_leave1[$i];} else {echo "-";}?></td>
							<td><b>Ketgan transporti:</b><br>
								<?php

									if (isset($transport_type_id[$i]) AND !empty($transport_type_id[$i])){
										$query = 'SELECT name FROM transports WHERE id='.$transport_type_id[$i];

										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result)) {
											echo $line[0];
										}
										pg_free_result($result);
									} else {echo "-";}

								?>
							</td>
							<td><b>Chiqib ketgan joyi: </b><br>
								<?php 
									if (isset($ketgan_post_id[$i])){
										$query = 'SELECT * FROM ketgan_post WHERE id='.$ketgan_post_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							
						</tr>
						<tr>
							<td><b>Birga ketganlar:</b><br><?php if(isset($birgaketganlar[$i]) AND !empty($birgaketganlar[$i])){echo $birgaketganlar[$i];} else {echo "-";}?></td>
							<td><b>Ketish maqsadi:</b><br>
								<?php 
									if (isset($aim_leave_id[$i])){
										$query = 'SELECT * FROM aim_leave WHERE id='.$aim_leave_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Ishlagan soha: </b><br>
								<?php 
									if (isset($work_type_id[$i])){
										$query = 'SELECT * FROM work_types WHERE id='.$work_type_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							
						</tr>
						<tr>
							<td><b>Davlat:</b><br>
								<?php 
									if (isset($sub_country_id[$i])){
										$query = 'SELECT * FROM countries WHERE id='.$sub_country_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Viloyat:</b><br>
								<?php 
									if (isset($sub_province_id[$i])){
										$query = 'SELECT * FROM provinces WHERE id='.$sub_province_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Shahar,tuman:</b> <br>
								<?php 
									if (isset($sub_region_id[$i])){
										$query = 'SELECT * FROM regions WHERE id='.$sub_region_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							
						</tr>
						<tr>
							<td><b>Manzili:</b><br> <?php if(isset($address[$i]) AND !empty($address[$i])){echo $address[$i];} else{echo "-";}?></td>
							<td><b>Izoh: </b><br><?php if(isset($comment[$i]) AND !empty($comment[$i])){echo $comment[$i];} else {echo "-";}?></td>
							<td><b>Telefoni: </b><br><?php if(isset($sub_homephone[$i]) AND !empty($sub_homephone[$i])){echo $sub_homephone[$i];} else {echo "-";}?></td>
							
						</tr>
						<tr class="active">
							<td colspan="3"><p class="h4">Qaytganligi haqida ma'lumot</p></td>
						</tr>
						<tr>
							<td><b>Qaytib kelgan sanasi:</b><br><?php if(isset($date_of_return1[$i]) AND $date_of_return1[$i]!='01.01.1970'){echo $date_of_return1[$i];} else {echo "-";}?></td>
							<td><b>Qaytish sababi: </b><br>
								<?php 
									if (isset($qaytish_sabab_id[$i])){
										$query = 'SELECT * FROM qaytish_sababi WHERE id='.$qaytish_sabab_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Tezkor qamrov natijasi: </b><br>
								<?php 
									if (isset($tezkor_qamrov_id[$i])){
										$query = 'SELECT * FROM tezkor_qamrov WHERE id='.$tezkor_qamrov_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							
						</tr>
						<tr>
							<td><b>Tibbiy ko'rik natijasi: </b><br>
								<?php 
									if (isset($tibbiy_korik_id[$i])){
										$query = 'SELECT * FROM tibbiy_korik WHERE id='.$tibbiy_korik_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Ishga joylash natijasi: </b><br>
								<?php 
									if (isset($ishga_joylash_id[$i])){
										$query = 'SELECT * FROM ishga_joylash WHERE id='.$ishga_joylash_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td></td>
							
						</tr>
						<tr>
							<td><b>Pasport yo'qotgan: <br></b><?php if($passportyuqotgan[$i] == 'f'){ echo '-';} else {echo "Yo'qotgan";}?></td><td><b>Rasmiy qidiruvda: <br></b><?php if($rasmiyqidiruvda[$i] == 'f'){ echo '-';} else {echo "Qidiruvda";}?></td>
							<td></td>
						</tr>
						<tr>
							<td class="active" colspan="3"><p class="h4">Chet elda qonunbuzarliklar</p></td>
						</tr>
						<tr>
							<td><b>Ayblanuvchi:</b><br>
								<?php 
									if (isset($jabr_jinoyat_id[$i])){
										$query = 'SELECT * FROM ishga_joylash WHERE id='.$jabr_jinoyat_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									} else {echo "-";}
								?>
							</td>
							<td><b>Jabrlanuvchi: </b><br>
								<?php 
									if (isset($ayb_jinoyat_id[$i])){
										$query = 'SELECT * FROM jinoyat_types WHERE id='.$ayb_jinoyat_id[$i];
										$result = pg_query($query) or die('Zaprosda muammo: ' . pg_last_error());
										while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
											echo $line["name"];
										}
										pg_free_result($result);
									}else {echo "-";}
								?>
							</td>
							<td></td>
						</tr>

						<?php }pg_close($conn);} ?><!--// Ends For Loop -->
					</table>
				</div> 

			</div>

			<div class="button-group pull-right" style="position:fixed; bottom:10px; right:10px">
				<button id="print_btn" class="btn btn-primary btn-lg">
					<span class="glyphicon glyphicon-print"></span>
				</button>
				
			</div>

		</div>

		<script>
			function print_text(){
				var txt = document.getElementById("span4").innerHTML;
				document.getElementsByTagName("body")[0].innerHTML = txt;
				var txt=document;
				print(txt);
				location.reload(true);
			}
			
			console.log('Loaded');
			$("#print_btn").click(function(){
				$(this).hide();
				window.print();
				$(this).show();
			});
			
		</script>
	</body>
</html>

