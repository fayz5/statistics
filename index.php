<?php //require('../includes/session.php');?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale = 1.0">

	<title>Статистика</title>
	<link href="styles/jquery-ui.custom.css" rel="stylesheet">
	<link href="styles/datatables.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="styles/mainStyle.css">
	
	<!--NEWLY ADDED-->
	<link rel="stylesheet" type="text/css" media="all" href="styles/bootstrap.custom.css" />

</head>
<body class="">
	<!-- 	<header class="bg-default">
			
		</header> -->
	<?php require('data/includes/navigation_b3.php');?>
	<div id="container">

		
		<!-- <button id="clear_btn" class="btn btn-danger" name = "clear_btn" style="position:fixed; right:10px; bottom:10px;"><img src="./styles/images/clean.ico" style="height:25px"></button> -->
		<button id="clear_btn" class="btn btn-warning" name = "clear_btn" style="position:fixed; right:10px; bottom:10px;"><span class="glyphicon glyphicon-refresh"></span></button>
		
		<div class="main">
			<aside style="margin-top:0;">

			<div class="well">
				<div id="direction" class="row">
					<ul class="nav nav-tabs">
						<li class="active" data-index=0 data-show="#fromTab"><a href="#">Откуда</a></li>
				    	<li data-index=1 data-show="#toTab"><a href="#">Куда</a></li>
					</ul>
				</div>
				<div class="row">
					<br>
					<div id="datesBtnSet" class="btn-group btn-group-justified">
						<div class="btn-group">
							<button type="button" class="btn btn-sm btn-default active" name = "radio_from" id="dol" data-disable="#dor">Дата выезда</button>
						</div>
						<div class="btn-group">
							<button type="button" class="btn btn-sm btn-default" name = "radio_from" id="dor" data-disable="#dol">Дата въезда</button>
						</div>
					</div>
				</div>

				<br>	
				<div class="row form-inline">
					<div class="form-group">
						<label for="startDate" class="date_label control-label">Начальная дата:</label>
						<input type="text" size="10" id="startDate" class="form-control" readonly="readonly" placeholder=" чч/мм/гггг">
					</div>
					
					<div class="space"></div>
					<div class="form-group">
						<label for="endDate" class="date_label control-label">Конечная дата:</label>
						<input size="10" id="endDate" class="form-control" readonly="readonly" placeholder=" чч/мм/гггг">
					</div>
				</div>


				<div id="fromTab" value="u">
				  <div class="space"></div>

			  		<div style="width:110%">
				   		<!-- Provinces Uzb-->
		                <div class="bigspace province"></div><div class="space"></div>
		                <div class="dropdown form-inline" id="provinceList">
							<label class="control-label region-label" for="provincesUZB">Область:</label>
						</div>

						<!-- Regions Uzb-->
						<div class="dropdown hidden form-inline" id="spRegionList">
							<label class="control-label region-label" id="labelSpReg" for="spRegionsUZB">Регион:</label>
						</div>

						<!-- Regions Uzb-->
						<div class="dropdown hidden form-inline" id="qshfyList">
							<label class="control-label region-label" id="labelqshfy" for="qshfy">Қ.Ш.Ф.Й.:</label>
						</div>

					</div>	
			    </div>
			    <div id="toTab" value="w" class="hidden">
					<div class="space"></div>

			  		<div style="width:110%">

	                
	                	<div class="bigspace country"></div><div class="space"></div>
		                <div class="dropdown form-inline" id="countryList">
							<label class="control-label region-label" for="countries">Страна:</label>
						</div>

						<!-- Provinces World -->
						<div class="dropdown hidden form-inline" id="provinceListWorld">
							<label class="control-label region-label" id="labelRegWorld" for="provincesWorld"> Область:</label>
						</div>

						<!-- Regions World-->
						<div class="dropdown hidden form-inline" id="worldRegionList">
							<label class="control-label region-label" id="labelWrldReg" for="wrldRegions"> Регион:</label>
						</div>


	                </div>
	                <!-- Countries  -->
	                


			    </div>

			    <div id="dropdown" class="row">
			    	<button class="btn btn-sm btn-block btn-primary" style="margin:10px 0px 0px">Общая статистика</button>
			    	<div id="selectedStats" class="hidden" style="border: 1px solid rgba(0,0,100,0.2); border-radius:5px" name="text">
			    		<div class="btn-group  btn-group-justified">
			    			<div class="btn-group">
				    			<button type="button" class="btn btn-sm btn-default" name = "radio-stats" id="aim_leave" value = 1 data-hide="#al_panel">Причина выезда</button>
				    		</div>
				    		<div class="btn-group">
				    			<button type="button" class="btn btn-sm btn-default" name = "radio-stats" id="aim_return" value = 2 data-hide="#ar_panel">Причина въезда</button>
				    		</div>
			    		</div>
			    		<div class="btn-group  btn-group-justified">
				    		<div class="btn-group">
				    			<button type="button" class="btn btn-sm btn-default" name = "radio-stats" id="aim_leave" value = 3 data-hide="#tp_panel">Транспорт</button>
				    		</div>
				    		<div class="btn-group">
				    			<button type="button" class="btn btn-sm btn-default" name = "radio-stats" id="aim_return" value = 4 data-hide="#cr_panel">Преступлениe</button>
				    		</div>
			    		</div>
			    		<div class="btn-group  btn-group-justified">
				    		<div class="btn-group">
				    			<button type="button" class="btn btn-sm btn-default" name = "radio-stats" id="aim_leave" value = 5 data-hide="#sl_panel">Спец учет</button>
				    		</div>
				    		<div class="btn-group">
				    			<button type="button" class="btn btn-sm btn-default" name = "radio-stats" id="aim_return" value = 6 data-hide="#ex_panel">Снятие с учета</button>
				    		</div>
			    		</div>
			    		<div class="btn-group  btn-group-justified">
			    			<div class="btn-group">
				    			<button type="button" class="btn btn-sm btn-default active" name = "radio-stats" id="total_stat" value = 0>Общее количество</button>
				    		</div>
						</div>
					</div>
			    </div>
			    
			    <div class="row">
			    	<div class="space"></div>
			    	<button id="dp_btn" class="btn btn-sm btn-block btn-primary" style="margin:10px 0px 0px" data-status=0>Доп. параметры</button>

			    </div>

			    <div class="clear"></div>
			    <!-- <div id="loadprogress" ></div> -->
				<div class="row requestDiv">
						<button id="requestBtn" class="btn btn-sm btn-success pull-right" style="width:140px">Отправить запрос</button>
						<img id="loadprogress" src="./styles/images/loading.gif" class="pull-right">
				</div>

			</div>

			<div id="criteria" class="well" style="padding:5px;">
				<div class="panel-custom panel panel-primary">
					<div class="panel-heading">Возраст</div>
					<div class="panel-body hidden" data-status='0'>
						<div id="add_age">
	  						<label for="amount"></label>
	  						<input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">

							<div id="age_range" style="margin:20px;"></div>
						</div>

					</div>
				</div>
				<div class="panel-custom panel panel-primary">
					<div class="panel-heading">Пол</div>
					<div class="panel-body hidden" data-status='0'>
						<div id="add_sx">
							<div id="select_sx" class="btn-group  btn-group-justified">
								<div class="btn-group">
									<button type="button" id="sx_m" class="btn btn-sm btn-default" name = "radio_sx" value = "male">Мужской</button>
								</div>
								<div class="btn-group">
									<button type="button" id="sx_f" class="btn btn-sm btn-default" name = "radio_sx" value = "female">Женский</button>		
								</div>
							</div>
						
						</div>

					</div>
				</div>
				<div class="panel-custom panel panel-primary">
					<div class="panel-heading">Период отсутствия</div>
					<div class="panel-body hidden" data-status='0'>

						<div id="add_period">
							<div id = "period_cont" class="form-inline centered" style="padding:10px;">
								<!-- <label for="num_dwmy"></label> -->
								<input type="number" id="num_dwmy" class="form-control" name = "num_dwmy">
								<select id="period_type" class="form-control" name="period_type" >
								    <option value="d" selected="selected">дней</option>
								    <option value="w" >недель</option>
								    <option value="m" >месяцев</option>
								    <option value="y" >года</option>
								</select>
							</div>		
							
	  							
						</div>

					</div>
				</div>
				<div id="al_panel" class="panel-custom panel panel-primary">
					<div class="panel-heading">Причина выезда</div>
					<div class="panel-body hidden button-groups" data-status='0'>
						<div id="add_al">
							<button type="button" class="btn btn-sm btn-default form-control" id="al_1" name = "radio_al" value = 1>Работа</button>
							<button type="button" class="btn btn-sm btn-default form-control" id="al_2" name = "radio_al" value = 2>Учеба</button>
							<button type="button" class="btn btn-sm btn-default form-control" id="al_3" name = "radio_al" value = 3>Путешествие</button>
							<button type="button" class="btn btn-sm btn-default form-control" id="al_4" name = "radio_al" value = 4>Лечение</button>
							<button type="button" class="btn btn-sm btn-default form-control" id="al_5" name = "radio_al" value = 8>Неизвестно</button>
							<button type="button" class="btn btn-sm btn-default form-control" id="al_6" name = "radio_al" value = -1>Другое</button>
						</div>

					</div>
				</div>

				<div id="ar_panel" class="panel-custom panel panel-primary" >
					<div class="panel-heading">Причина въезда</div>
					<div class="panel-body hidden button-groups" data-status='0'>
						<div id="add_ar">
							<button type="button" id="ar_1" class="btn btn-default btn-sm form-control" name = "radio_ar" value = 1>Добровольно</button>
							<button type="button" id="ar_2" class="btn btn-default btn-sm form-control" name = "radio_ar" value = 2>Принудительно</button>
							<button type="button" id="ar_3" class="btn btn-default btn-sm form-control" name = "radio_ar" value = 3>Истечение тр. дог.</button>
							<button type="button" id="ar_5" class="btn btn-default btn-sm form-control" name = "radio_ar" value = 5>Сертификат</button>
							<button type="button" id="ar_4" class="btn btn-default btn-sm form-control" name = "radio_ar" value = -1>Другое</button>
						</div>

					</div>
				</div>


				<div id="tp_panel" class="panel-custom panel panel-primary" >
					<div class="panel-heading">Транспорт</div>
					<div class="panel-body hidden button-groups" data-status='0'>
						<div id="add_tp">
							<button type="button" id="tp_1" class="btn btn-default btn-sm form-control" name = "radio_tp" value = 1>Самолёт</button>
							<button type="button" id="tp_3" class="btn btn-default btn-sm form-control" name = "radio_tp" value = 3>Железная дорога</button>
							<button type="button" id="tp_2" class="btn btn-default btn-sm form-control" name = "radio_tp" value = -1>Др. транспорт</button>
						</div>

					</div>
				</div>

				<div id="cr_panel" class="panel-custom panel panel-primary" >
					<div class="panel-heading">Преступления</div>
					<div class="panel-body hidden button-groups" data-status='0'>
						<div id="add_cr">
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_1" name = "radio_cr" value = 1>Убийство</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_2" name = "radio_cr" value = 2>ОТЖЕ</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_3" name = "radio_cr" value = 3>Изнасилование</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_4" name = "radio_cr" value = 4>Разбой</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_5" name = "radio_cr" value = 5>Грабеж</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_6" name = "radio_cr" value = 6>Мошенничество</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_7" name = "radio_cr" value = 7>Угон машин</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_8" name = "radio_cr" value = 8>Кража</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_9" name = "radio_cr" value = 9>Хулиганство</button>
							<button type="button" class="btn btn-default btn-sm form-control" id="cr_10" name = "radio_cr" value = -1>Другое</button>
						</div>

					</div>
				</div>

				<div id="sl_panel" class="panel-custom panel panel-primary" >
					<div class="panel-heading">Спец учет</div>
					<div class="panel-body hidden button-groups" data-status='0'>
						<div id="add_sl">
							<button type="button" id="sl_1" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 1>Хизб ут-Тахрир</button>
							<button type="button" id="sl_2" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 2>Акрамит</button>
							<button type="button" id="sl_3" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 3>Салафия</button>
							<button type="button" id="sl_4" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 4>Нур</button>
							<button type="button" id="sl_5" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 5>Джихадист</button>
							<button type="button" id="sl_6" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 6>ТИХ</button>
							<button type="button" id="sl_7" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 7>Сатанист</button>
							<button type="button" id="sl_8" class="btn btn-default btn-sm form-control" name = "radio_sl" value = 8>Миссионер</button>
						</div>

					</div>
				</div>


				<div id="ex_panel" class="panel-custom panel panel-primary" >
					<div class="panel-heading">Снятие с учета</div>
					<div class="panel-body hidden button-groups" data-status='0'>
						<div id="add_ex">
							<button type="button" id="ex_1" class="btn btn-default btn-sm form-control" name = "radio_ex" value = 1>Отказ от гр-ва</button>
							<button type="button" id="ex_2" class="btn btn-default btn-sm form-control" name = "radio_ex" value = 2>Смерть заруб.</button>
							<button type="button" id="ex_3" class="btn btn-default btn-sm form-control" name = "radio_ex" value = 3>Смерть в Узб.</button>
						</div>

					</div>
				</div>

			</div>

			</aside>

			<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
			<section>
				<div id="map-wrap" class="boxes ui-widget-content">
					<div id="map" style="height: 90%; margin: 0 auto"></div>
				</div>
				
				<div id="chart" class="boxes ui-widget-content">
					<div id="chartcontainer" class="highcharts-container" style="min-width: 310px; height: 90%; margin: 0 auto"></div>
				</div>
				<div id="table" class="ui-widget-content table-responsive boxes" style="padding:15px; white-space: nowrap; position: relative;">
					<button id="prev100" name = "table_btn" class="btn btn-default" style="display:none">Предыдущие</button>
					<button id="next100" name = "table_btn" class="btn btn-default" style="display:none">Следующие</button>
					<!-- <div id="total_rows" class="alert alert-info" style="position:absolute; display:none">
						<strong></strong>
					</div>	 -->
					<table id="tableData" class="display table table-condensed table-striped table-hover" cellspacing="0" width="100%">
						<thead>
						  <tr>
						  	 <th>Id</th>
							 <th>Фамилия</th>
							 <th>Имя</th>
							 <th>Отчество</th>
							 <th>Паспорт</th>
							 <th>Область</th>
							 <th>Регион</th>
							 <th>Улица</th>
						  </tr>
						 </thead>							
																
					</table>
					
				</div>

			</section>
			
		</div>
		
		<!-- <footer>Copyright &copy</footer> -->

	</div>

	<?php require('data/includes/footer_b3.php');?>


	<script src="js/jquery-1.11.3.min.js"></script> 
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/mainscript.js"></script>
	<script src="js/datatables.min.js"></script>

	<script src="js/highmaps/js/highcharts.js"></script>
	<script src="js/highmaps/js/modules/highcharts-more.js"></script>
	<script src="js/highmaps/js/map.js"></script>
	<script src="js/highmaps/js/modules/data.js"></script>
	<!-- Export modules -->
	<script src="js/highmaps/js/modules/exporting.src.js"></script>
	<script src="js/offline-exporting.js"></script>
	<script src="js/canvas-tools.js"></script>
	<script src="js/export-csv.js"></script>
	<!-- Export Client-Side module -->
	<script src="js/highcharts-export-clientside.js"></script>
	
	<script src="js/datepicker-ru.js"></script>
	<script src="data/maps/uzb.js"></script>
	<script src="data/maps/world.js"></script>

</body>
</html>