(function wraper(){

// GLOBAL VARIABLES	
	var id_list=["country_id","province_id","region_id","qshfy_id"];
	var mapDataObj = {};
	var recordNum = 0; //Number of records in one of the server responces
	"use strict";
	var requestObj={
			tabIndex:0,
			fr:{regType:0, region_id:192},
			to:{regType:-1, region_id:null},
			offset_val : 0

		};

///// STARTING POINT /////////////////		
	$(document).ready(function(){
		
		drawUI();

		//Uzbekistan Regions List
		selectRegions('uzb');


		//World Regions List
		selectRegions('wo');
		
	});


////Select Menu Creating Functions


	function selectRegions(type){
		var obj = {
				select:["provincesUZB","spRegionsUZB","qshfy"],
				list:["provinceList","spRegionList","qshfyList"],
				labels:["Область","Регион","Қ.Ш.Ф.Й."]
			};

		if (type==='uzb'){

			destroyList(obj); // destroy existing list
			selectMenu(obj,"data/uzbData.php", 192, 0, 0);	// Get the province list and create select menu
		}
		if (type==='wo'){

			obj.select = ["countries","provincesWorld","wrldRegions"];
			obj.list = ["countryList","provinceListWorld","worldRegionList"];
			obj.labels = ["Страна","Область","Регион"];
			destroyList(obj); // destroy existing list
			selectMenu(obj,"data/worldData.php", null, 0, -1); // Get the contries list and create select menu
		}

		function destroyList(obj){

			obj.list.forEach(function(value){
				$("#"+value).addClass("hidden");
				//$("#"+value).empty();

			});
			obj.select.forEach(function(value){
				$("#"+value).remove();
				console.log("#"+value);


			});
			//console.log('-------------------------\n\n');
		}	
	}

	function selectMenu(obj, phpSource, region_id, index, regType){
		/*
		* obj - contains the names of DOM elements to create Select Menu
		* phpSource - php file that should process the POST request
		* region_id - initial region id DEFAULT: Uzbekistan = 192, World = null
		* index - current Select Menu number inside 'obj' object
		* regionType - current region type index in 'id_list' array
		* */


		var show_flag=0;
		var sendObj = {};
		if (regType >= 0){
			var regionType = id_list[regType];
			sendObj[regionType] = region_id;
		}


		$.post( phpSource, sendObj, function(data) {
			//console.log(obj);


			createSelectInside(obj.select[index], obj.list[index], data);
			$("clear_btn").prop("display","block");

		}, "json");


		function createSelectInside(selectName, parentNode, data){
			/*
			* selectName - name and id of the element to be created
			* parentNode - name and id of the node the created element is attached to
			* data - Select options
			*
			* */
			// if(!$('#'+selectName)){

			// }
			// var current_indx = obj.select.indexOf(selectName);
			// $('<label for=#'+selectName+'style="position:relative;top:-8px;">'+obj.labels[current_indx]+':</label>').appendTo("#"+parentNode);
			$("<select name="+selectName+" id="+selectName+">").appendTo("#"+parentNode);
			$("<option value=0>Выбрать...</option>").appendTo("#"+selectName);
			$.each(data,function(index, value){
				$("<option value="+value.id+">"+value.name+"</option>").appendTo("#"+selectName);

			});

			$("#loadprogress").hide();
			//Display Elements
			$("#requestBtn").removeAttr("disabled");
			$("#"+obj.list[index]).removeClass("hidden");

			$("#"+selectName).selectmenu({width:186})
				.selectmenu( "menuWidget")
				.addClass("overflow");
			$("#"+selectName).selectmenu({
				change: function(event,data){
					var target = $("#"+selectName+" :selected");
					changeRegionId(target, region_id);
					show_flag = changeRegType(target,regType,regType+1);

					//Hide elements -----------------------------------
					obj.list.forEach(function(value,num){
						if(num>index){
							$("#"+value).addClass("hidden");
							//console.log("#"+value+" is hidden");
						}
					});
					obj.select.forEach(function(value,num){
						if(num>index){
							$("#"+value).remove();
							//console.log(index+" "+num);
						}

					});
					///////////////////////////////////////////////////////

					if (show_flag !== 0)
					{
						$("#loadprogress").show();
						$("#requestBtn").attr("disabled", true);
						selectMenu(obj,phpSource,data.item.value,index+1,regType+1);
					}

				}
			});
		}

	}

///////////////////////////////////////////////////
	

	function changeRegType(o, val_false, val_true){
		var show_flag, target;
		if (requestObj.tabIndex === 0){
			target = requestObj.fr;
		}else{

			target = requestObj.to;
		}
		if (o.val()!=="0"){
			show_flag=1;
			target.regType=val_true;
		}else{
			show_flag=0;
			target.regType=val_false;
		}
		return show_flag;
	}


	function changeRegionId(o, val_false){
		var region_id, target;
		if (requestObj.tabIndex === 0){
			target = requestObj.fr;
		}else{

			target = requestObj.to;
		}
		target.region_id = ( o.val()!=="0"  ) ? o.val() : val_false;
		region_id = target.region_id;
		//return region_id;


	}

//// USER INTERFACE PART

	function createSideTabs(){
	    $( "#tabs" ).tabs({
	    	activate: function( event, ui ) {
	    		if (ui.newTab.index()===0) {
	    			//Uzbekistan
	    			
	    			requestObj.tabIndex=0;
	    			if (mapDataObj.uzb){
	    				drawUzbMap(mapDataObj.uzb, mapDataObj.min_uzb, mapDataObj.max_uzb);
	    			}else{
	    				drawUzbMap(null, 1, 0);
	    			}
	    			
	    		}else{
	    			//World
	    			
	    			requestObj.tabIndex=1;
	    			if (mapDataObj.wo){
	    				drawWorldMap(mapDataObj.wo, mapDataObj.min_wo, mapDataObj.max_wo);
	    			}else{
	    				drawWorldMap(null, 1, 0);
	    			}
	    			
	    		}
	    	}
	    });
	}

	function createClearBtn(){
		$( "#clear_btn" ).button()
			.click(function(){
				$(this).prop("display","none");
				//DELETE CURRENT REQUEST OBJECT
				delete requestObj.groupRequest; 
				delete requestObj.searchCriteria;
				

				//CREATE NEW REQUEST OBJECT
				requestObj.groupRequest = {};
				requestObj.searchCriteria = {};

				//CLEAR GROUP STATISTICS
				$('#total_num').prop("checked",true);
				$( "#selectedStats input" ).button( 'refresh' );
				//CLEAR ADDITIONAL STATISTICS
				$( "#criteria div > input[type='radio']" ).each(function(){
					var $radio = $(this);
					if ($radio.data('waschecked') == true)
				    {
				        $radio.prop('checked', false);
				        $radio.data('waschecked', false);
				    }
				   
				    $radio.button('refresh');	
				});

				//CLEAR DATES
				$.datepicker._clearDate($( "#startDate" ));
				$.datepicker._clearDate($( "#endDate" ));
				$( "#startDate" ).datepicker('destroy');
				$( "#endDate" ).datepicker('destroy');
				createDatePickers(); //readd datepicker functionality

				//CLEAR UZBEKISTAN REGIONS
				$('[name=countries]').each(function(){
					$(this).remove();
				});
				console.log(requestObj);
				selectRegions('uzb');

				//CLEAR WORLD REGIONS
				selectRegions('wo');

				//AGE RANGE SLIDER
				$( "#age_range" ).slider('values',0,18);
				$( "#age_range" ).slider('values',1,100);
				$( "#amount" ).val( "Возраст: не указан" );

				// SELECT SEX
				$( "#sx_m" ).removeAttr('checked').button('refresh');
				$( "#sx_f" ).removeAttr('checked').button('refresh');

				//ABSENCE PERIOD
				$( "#num_dwmy" ).val("");


			});
	}

	function createTopDateRadioBtns(){
		$( "#datesBtnSet" ).buttonset();
	}

	function createDatePickers(){



		$( "#startDate" ).datepicker({
			minDate: new Date(1990, 0, 1), 
			maxDate: new Date(),
			gotoCurrent: true,
			changeMonth: true,
	      	changeYear: true,
			onSelect: function(){
				$( "#endDate" ).datepicker("option", "minDate", $( "#startDate" ).datepicker( "getDate" ));
			}
		});
		$("#endDate").datepicker({
			maxDate: new Date(),
			gotoCurrent: true,
			changeMonth: true,
	      	changeYear: true,
			onSelect: function(){
				$( "#startDate" ).datepicker("option", "maxDate", $( "#endDate" ).datepicker(   "getDate" ));
			}
		});
	}

	

	function createSelectableStats(){
		var $elem;
		$( "#selectedStats input" )
			.button()
			.click(function(){
				var index = Number($(this).val()) + 3;
				$(".ui-accordion-header").hide();
				$( "#criteria" ).accordion('option', {active: false});
				$(".ui-accordion-header").show();	
				if (index > 3){
					var $elem = $(".ui-accordion-header").eq(index);
					$elem.hide();
					$elem.next().children("input").each(function(){
						
						$(this).prop("checked",false); // Uncheck every selection inside the disabled Accordion Tab
					});
				}
				
			});

		

		var label = $("label[for='"+$( '#total_num' ).attr('id')+"']");
		label.css({width:'54%', position: 'relative', left:'54px'});
		$( "#selectedStats input" ).button( 'refresh' );
			
	}

	function createGeneralStats(){
		$( "#accordion" ).accordion({
	      collapsible: true,
	      active: false,
	      heightStyle: "content"
	    });

	    
	    createSelectableStats();
	    
	}

	function createEnableAdditionalsBtn(){
		$( "#dp_btn" ).button()
			.click(function(){
				if ($(this).prop( "checked" )){

					$( "#criteria" ).show();

				}else{

					$( "#criteria" ).hide();
				}	
			});
	}

	function createAgeSlider(){
		$( "#amount" )
			.css({
				"padding-top":"20px",
				"font-size":"0.96em"
			})
			.width(160);

		$( "#age_range" ).slider({
			
			range: true,
      		min: 1,
      		max: 100,
      		values: [ 18, 100 ],
      		slide: function( event, ui ) {
      			if (Number(ui.values[ 1 ]) < 100){
      				$( "#amount" ).val( "Возраст: от " + ui.values[ 0 ] + " - до " + ui.values[ 1 ] + "  " );	
      			}else{
      				$( "#amount" ).val( "Возраст: старше " + ui.values[ 0 ]);	
      			}
        		
        		$( "#amount" ).data('wasselected',true);
      		}

		});
		$( "#amount" ).val( "Возраст: не указан" );
		
	}


	function secondClickUncheckBtn(){
		$( "#criteria div > input[type='radio']" ).click(function(){
			var $radio = $(this);
			if ($radio.data('waschecked') == true)
		    {
		        $radio.prop('checked', false);
		        $radio.data('waschecked', false);
		    }
		    else
		    {
		         $radio.prop('checked', true);
		         $radio.data('waschecked', true);
		    }
		    $radio.button('refresh');	
		});

	}

	function createAbsencePeriodSelector(){
		$( "#num_dwmy" )
			.addClass("ui-corner-all")
			.width("60px")
			.css({"position":"relative", "top":"-10px","left":"-20px"});

		$( "#period_type" )
			.selectmenu({width:100})
			.selectmenu( "menuWidget" );	
	}

	function selectedSx(){
		$( "#sx_m" ).button()
	    	.change(function(){
	    		
	    		if($(this).is(':checked')){
        			$( "#sx_f" ).removeAttr('checked').button('refresh');
    			}	
	    	});
	    $( "#sx_f" ).button()
	    	.change(function(){
	    		
	    		if($(this).is(':checked')){
        			$( "#sx_m" ).removeAttr('checked').button('refresh');
    			}	
	    	});
	    $( ".sx_label" ).css({'width':'35%', 'position':'relative', 'left':'35px', 'margin':'20px 0'});	
	}

	function createAdditionalCriteria(){
		
		createEnableAdditionalsBtn();		//Enable Additional Search Options
        		
		$( "#criteria" ).accordion({
	      collapsible: true,
	      active: false,
	      heightStyle: "content"
	    }); // Creates dropdown for additional options

		selectedSx();
		createAbsencePeriodSelector();      //Renders Absence period input select
		createAgeSlider(); 					// Age Range Selector 
	    $( "#criteria div input[type='radio']" ).button(); // All input type=radio becomes jQuery UI button widget
	    secondClickUncheckBtn();
	    

	    

	}

	function createAbsencePeriodReq(){
		/*
		If there is a valid & >0 number entered in period field,
		this function creates a 'period' object and adds to request
		*/
		var fieldVal = Number($( "#num_dwmy" ).val());
		if( fieldVal > 0){
			var $period_type = $("#period_type :selected").val();
			var period_arr = [$period_type, fieldVal];
			//period_arr[$period_type] = fieldVal;
			requestObj.searchCriteria["period"] = period_arr;
			
		}	
	}

	function createAgeRangeReq(){
		/*
		This function checks weather user changed the age slider
		if YES: creates "age_range" object and adds to request
		if NO: Nothing is done in this case
		*/
		if ($( "#amount" ).data('wasselected')){
			var obj = {}, min_age, max_age;
			min_age = $( "#age_range" ).slider( "values", 0 );
			max_age = $( "#age_range" ).slider( "values", 1 );
			requestObj.searchCriteria["min_age"] = min_age;
			requestObj.searchCriteria["max_age"] = (max_age < 100) ? max_age : null;

		} 

	}



	function requestPost(o){

		$.post("data/dataFunctions.php",o,function(data){
			var stat_group = {};
			var series = [];
			recordNum = data.total; //Global variable
			var group_names;
			var key_1, key_2, key_3;
			var categories = [];
			categories =Object.keys(data.cat); 
			//console.log(categories);

			
			//Display Elements
			if (data.id){
				
				//Get the names in the Group stat from server
				$.post("data/dataFunctions.php",{get_list : requestObj.groupRequest["group_by"]},function(names){
					group_names = names;
					var indx = 0;
					var tmpObj;
					//var dataArr = Array.from({length: categories.length}, (v, k) => 0);  
					//console.log(data);
					for (var key in data.id){


						pos = Number(key);
						series[indx] = {};
						series[indx]['name'] = group_names[pos];
						series[indx]['data'] = Array.from({length: categories.length}, (v, k) => 0);
						(function(obj){
							var i = 0;

							for(var k in obj){
								i = Number(k);
								series[indx]['data'][i] = Number(obj[k]);

							}

						})(data.id[key]['chart']);
						
						
						indx++;
						
					}
					
					drawChart(categories, series);
					//console.log(series);

				},"json");
				
				
			}else{

				//data['chart']
				series[0] = {};
				series[0]['name'] = 'Общее количество';
				series[0]['data'] = Array.from({length: categories.length}, (v, k) => 0);
				(function(obj){
					var i = 0;

					for(var k in obj){
						i = Number(k);
						series[0]['data'][i] = Number(obj[k]);

					}

				})(data['chart']);
				
				drawChart(categories, series);
			}
			
			//$("#loadprogress").hide();

			requestObj.offset_val = 0; //Set this object to zero
			drawTable(data.total);
		},"json");
		
	}



	function checkDates(){

		var xstring = $("#datesBtnSet input:checked").attr("id");
		if(xstring === "dol"){
			requestObj.groupRequest.dateType = "date_of_leave";
		}else{
			requestObj.groupRequest.dateType = "date_of_return";
		}
		//User indicated start date
		var startingDate = $( "#startDate" ).datepicker( "getDate" );
		
		//User indicated ending date
		var endingDate = $( "#endDate" ).datepicker( "getDate" );
		
		//Below two is used to check the passed years between the entered Dates
		var startComp = startingDate;
		var endComp = endingDate;
		//Check the starting Date
		if (startingDate === null){

			var minDate = new Date('January 1, 1991 00:00:00');
			requestObj.groupRequest.startDate = minDate.getFullYear()+"-"+(minDate.getMonth()+1)+"-"+minDate.getDate();
			startComp = minDate;
		}else{
		
			requestObj.groupRequest.startDate = startingDate.getFullYear()+"-"+(startingDate.getMonth()+1)+"-"+startingDate.getDate();
		}
		//Check the ending Date
		if (endingDate === null)
		{
			var today = new Date();
			requestObj.groupRequest.endDate = today.getFullYear()+"-"+(today.getMonth()+1)+"-"+today.getDate();
			endComp = today;			
		}else{
			requestObj.groupRequest.endDate = endingDate.getFullYear()+"-"+(endingDate.getMonth()+1)+"-"+endingDate.getDate();	
		}
		//Calculate the date difference in YEARS
		var date_diff = Math.floor((endComp - startComp) / (1000*365*60*60*24));

		requestObj.years = date_diff; // This property is used to track if there are monthes in return JSON object

	}

	

	function createSubmitButton(){
		$( "#requestBtn" ).button()
			.click(function(){
				//Delete previous requests
				delete requestObj.groupRequest; 
				delete requestObj.searchCriteria;
				

				//Create new request objects
				requestObj.groupRequest = {};
				requestObj.searchCriteria = {};

				

				//Group statistics check and send
				$( "#selectedStats > input:checked" ).each(function(){
					requestObj.groupRequest["group_by"]=$(this).val();
				});

				//Additional statistics check and send
				$( "#criteria div > input:checked" ).each(function(){
					var index=$(this).attr("name");
					requestObj.searchCriteria[index]=$(this).attr("value");
				});
				//Age range processing
				createAgeRangeReq();
				//Absence period processing
				createAbsencePeriodReq();

				//Check and validate Selected Dates
				checkDates();
				

				//console.log(requestObj);
				$("#loadprogress").show();
				//console.log($("#loadprogress"));
				requestPost(requestObj);

				processMapDataReq(requestObj);

			});
	}


	function chartExportingButtons(){
		//Remove "Download"
		$(".chart-export").remove();
		//Recreate "Download" Button each time chart created
		$("#chart").prepend("<div class='chart-export' data-chart-selector='#chartcontainer'>");

		$(".chart-export").append('<button type="button" data-type="image/jpeg">');
		$(".chart-export > *").css({position: 'relative', left:'650px', 'margin':'10px', 'padding':'10px'});

		$(".chart-export > *").button({
			  text: false,

		      icons: {
		        primary: "ui-icon-disk"
		      }
		});
		$(".chart-export").each(function() {
		  var jThis = $(this),
			  chartSelector = jThis.data("chartSelector"),
			  chart = $(chartSelector).highcharts();

		  $("*[data-type]", this).each(function() {
			var jThis = $(this),
				type = jThis.data("type");
			if(Highcharts.exporting.supports(type)) {
			  jThis.click(function() {
				chart.exportChartLocal({ type: type });
			  });
			}
			else {
			  jThis.attr("disabled", "disabled");
			}
		  });
		});
	}

	function createTableButtons(){
		//Handle "Prev" button click event
		$("#prev100").button()
			.click(function(){
				var offset_val = requestObj.offset_val;
				$("#prev100").attr("disabled", true);	
				if (offset_val-100 < 0 ){
					offset_val =0;	
				}else{
					offset_val -= 100;
					tableDataReq(offset_val);	
				}	
				//console.log("Current: "+offset_val+" Total:"+recordNum);
			});

		//Handle "Next" button click event
		$("#next100").button().click(function(){
				
				var offset_val = requestObj.offset_val;
				$("#next100").attr("disabled", true);
				if (offset_val + 100 < recordNum ){
					
					offset_val += 100;	
					tableDataReq(offset_val);	
				}	
				//console.log("Current: "+offset_val+" Total:"+recordNum);
			});
		
		//Positioning "Prev" and "Next" buttons
		$("#prev100").css({position: 'relative', left:'445px'});
		$("#next100").css({position: 'relative', left:'445px'});


	}




	function drawAside(){

		createSideTabs();  			// From and To Tabs
		createTopDateRadioBtns();	// Date of leave date of return
		createDatePickers();		// Datepicker
		createGeneralStats();		// General Stats Dropdown
		createClearBtn();			// Create Clear Selected Stats Button
		createAdditionalCriteria();	// Addiotional Criteria Multiple Drop Downs
		createSubmitButton();		// Main Submit Button
		
		
		$( "#loadprogress" ).progressbar( {value: false} );
	}



	function drawUI(){
		
		drawAside();	// Aside bar
		

		$( '#container' ).show(); //Show graphical section
		createTableButtons();
		
		drawUzbMap(null, 1,0);
		initChart();

	}

//// DATA MANIPULATIONS
	function initChart(){
		var today = new Date();
		var thisYear = today.getFullYear();
		var diff = thisYear - 1991;
		var series = []
		series[0] = {};
		series[0]['name'] = 'Статистика';
		

		series[0]['data'] = [];
		var category = [];
		for (var i=0; i<=diff; i++)
		{
			series[0]['data'][i] = 0;
			category[i] = thisYear-diff+i;

		}
		drawChart(category, series);
		

	}

	function tableDataReq(off_val){

		
		requestObj.offset_val = off_val; //From which record to start
		$.post("data/dataTable.php", requestObj, function(tbData){
			
			//console.log(tbData);
			var $table = $('#tableData').DataTable();
			$table.destroy();
					
			$( '#tableData' ).DataTable({
					"aaSorting": [],
					"searching": false,
					"scrollX": true,
					"language": {
						"lengthMenu":"Количество:  _MENU_",
						"info": "Страница _PAGE_ из _PAGES_",
						"paginate": {
							"previous": "<<",
							"next": ">>"
						}
					  },

					data: tbData,
					"columns": [
						{ "data": "id"},
						{ "data": "sname",
							"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
										        $(nTd).html("<a target=_blank href='data/selectPerson.php?pid="+oData.id+"'>"+oData.sname+"</a>");
										     }
						},
						{ "data": "name" },
						{ "data": "mid" },
						{ "data": "pass" },
						{ "data": "prov" },
						{ "data": "reg" },
						{ "data": "str" }		
						]

				});
			//Disable "Prev" and "Next" buttons
			$("#next100").removeAttr("disabled").button('refresh');
			$("#prev100").removeAttr("disabled").button('refresh');
			
			/*
			$( '#tableData tr' ).dblclick(function(){
				var id = $(this).find(':first-child').html();
				//Send the selected user ID to server for processing
				$.post("data/selectPerson.php",{person_id:id},function(data){
					drawDialogBox(data);	
				},"json");
				
				
			});
			*/

		},"json");	

	}

	function drawDialogBox(data){
		console.log(data);
		var dialogBox = $('<div id="dialog" title="Selected user" style="displa:none">');
		$('body').append(dialogBox);
		$( "#dialog" ).dialog({
	    	//autoOpen: false,
	    	close: function(){
	    		$( "#dialog" ).remove();		
	    	}
	    });

	}

	function drawTable(total){

		//Prevent user from clicking before data loaded
		$("#next100").attr("disabled", true);
		$("#prev100").attr("disabled", true);
		
		tableDataReq(0);//Send the server request 
		
		if (recordNum > 100){ // > Enables Next and Prev buttons below the table
			
			$("#table > button").show();	
		}else{
			$("#table > button").hide();	
		}
		
	
	}

	function drawChart(cat, data){
		
		$( '#chartcontainer' ).highcharts({
			exporting: { enabled: false },
			credits: { enabled: false},	
	        title: {
	            text: 'Статистика',
	            x: -20 //center
	        },
	        scrollbar:{
        		enabled:true
    		},
	        // subtitle: {
	        //     text: 'Название графика',
	        //     x: -20
	        // },
	        xAxis: {
	            categories: cat
	        },
	        yAxis: {
	            title: {
	                text: 'Количество'
	            },
	            plotLines: [{
	                value: 0,
	                width: 1,
	                color: '#808080'
	            }]
	        },
	        tooltip: {
	            valueSuffix: ' '
	        },
	        legend: {
	            layout: 'vertical',
	            align: 'right',
	            verticalAlign: 'middle',
	            borderWidth: 0
	        },
	        series: data
	    });//highcharts

		chartExportingButtons();

	}//drawChart
	



	function processMapDataReq(requestObj){
		$.post("data/mapData.php", requestObj, function(data){
			mapDataObj = data;

			//Uzbekistan
			if (mapDataObj.uzb)	{
				var max_uzb = -Number.MAX_VALUE;
				var min_uzb = Number.MAX_VALUE;
				mapDataObj.uzb.forEach(function(obj){
					obj['value'] = Number(obj['value']);
					if (obj['value']>max_uzb) max_uzb = obj['value'];
					if (obj['value']<min_uzb) min_uzb = obj['value'];
					
				});
				mapDataObj.min_uzb = min_uzb;
				mapDataObj.max_uzb = max_uzb;

				if(requestObj.tabIndex===0){ drawUzbMap(mapDataObj.uzb,min_uzb,max_uzb); }
			}else{
				if(requestObj.tabIndex===0){ drawUzbMap(null,1,0); }
			}	
			
			//World
			if (mapDataObj.wo)	{
				var max_w = -Number.MAX_VALUE;
				var min_w = Number.MAX_VALUE;
				mapDataObj.wo.forEach(function(obj){
					obj['value'] = Number(obj['value']);
					if (obj['value']>max_w) max_w = obj['value'];
					if (obj['value']<min_w) min_w = obj['value'];
					//console.log(obj['value']);
				});
				mapDataObj.min_w = min_w;
				mapDataObj.max_w = max_w;
				//console.log('min: '+min_w+' max:'+max_w);
				if(requestObj.tabIndex===1){ drawWorldMap(mapDataObj.wo, min_w, max_w); } 
			}else{
				if(requestObj.tabIndex===1){ drawWorldMap(null, 1, 0); }
			}
			$("#loadprogress").hide();

			//console.log(mapDataObj);

		},"json");

		

		
	};

	function drawUzbMap(mapData, min_val, max_val){
		$( '#map' ).highcharts('Map', {
			exporting: { enabled: false },
			credits: { enabled: false},	
	        title : {
	            text : "Узбекистан"
	        },

	        tooltip: {
				backgroundColor: 'none',
				borderWidth: 0,
				shadow: false,
				useHTML: true,
				padding: 0,
				pointFormat: '<span class="f32"><span class="flag {point.flag}"></span></span>' +
				' {point.name}: <b>{point.value}</b>',
				positioner: function () {
					return { x: 0, y: 250 };
				}
			},

	        mapNavigation: {
	            enabled: false,
	            buttonOptions: {
	                verticalAlign: 'bottom'
	            }
	        },

	        colorAxis: {
	                min: min_val,
	                max: max_val,
	                type: 'linear'
	            },

	        series : [{
	            data : mapData,
	            mapData: Highcharts.maps['uzb'],
	            joinBy: 'province_id',
	            name: 'Количесто',
	            states: {
	                hover: {
	                    color: '#BADA55'
	                }
	            }
	        // dataLabels: {
	        //         enabled: false,
	        //         format: '{point.name}'
	        //     }
	        }]
	    });//Highcharts
	};//drawUzbMap

	function drawWorldMap(mapData, min_val, max_val){
		$('#map').highcharts('Map', {
			exporting: { enabled: false },
			credits: { enabled: false},	
			title: {
				text: 'Карта мира'
			},

			// legend: {
			// 	title: {
			// 		text: 'Плотность населения',
			// 		style: {
			// 			color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
			// 		}
			// 	}
			// },

			mapNavigation: {
				enabled: true,
				buttonOptions: {
					verticalAlign: 'bottom'
				}
			},
			tooltip: {
				backgroundColor: 'none',
				borderWidth: 0,
				shadow: false,
				useHTML: true,
				padding: 0,
				pointFormat: '<span class="f32"><span class="flag {point.flag}"></span></span>' +
				' {point.name}: <b>{point.value}</b>',
				positioner: function () {
					return { x: 0, y: 250 };
				}
			},

			colorAxis: {
				min: min_val,
				max: max_val,
				type: 'linear'
			},

			series : [{
				data : mapData,
				mapData: Highcharts.maps['world'],
				joinBy: ['iso-a3', 'code'],
				name: 'Количесто',
				states: {
					hover: {
						color: '#BADA55'
					}
				}
			}]
		});
	}

})();//end wrapper
