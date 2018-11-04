	window.onload=testTimeCount;

	function testTimeCount(val){
		if(val < 2){
			val++;
			
			testTimeCount(val);			
		}else{
			//alert("Here...");
			beginrefresh();
		}
	}

	function sleep(milliseconds) {
	  var start = new Date().getTime();
	  for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds){
		  break;
		}
	  }
	}

	function beginrefresh(){			
		/* ----------------------------------AJAX : Start ------------------------------------*/
			//AJAX Call for Time Count
			var xmlhttp, response, res;
			xmlhttp = browserVerification();
						
			xmlhttp.onreadystatechange=function(){
				if(xmlhttp.readyState!=4){
					//document.getElementById("setTimeHere").innerHTML = "Loading...";	
				}else if(xmlhttp.readyState==4){		
					//alert(document.getElementById("messageContent").style.display = "block");			
					response = xmlhttp.responseText;
					res = response.split(":*:");
					if(res[0] == '1'){					
						document.getElementById("messageContent").style.display="block";
						$("#message").animate({opacity: "show", bottom: "0"}, "slow");	
						document.getElementById("messageContent").innerHTML = res[1];
					}
				}//if	
			}
			
			var myDate = new Date();
			
			//alert(myDate);
			var yyyy = myDate.getFullYear();
			//var yy = yyyy.toString().substring(2);
			var mon = myDate.getMonth()+1;
			
			
			var mon = mon < 10 ? "0" + mon : mon;
			//alert(mon);
			
			//var mmm = months[m];
			var d = myDate.getDate();
			var d = d < 10 ? "0" + d : d;
			
			var h = myDate.getHours();
			var h = h < 10 ? "0" + h : h;
			
			var m = myDate.getMinutes();
			var m = m < 10 ? "0" + m : m;
			
			var s = myDate.getSeconds();
			var s = s < 10 ? "0" + s : s;

			
			//Form the Date String
			var dateStr = yyyy+"_"+mon+"_"+d+"_"+h+"_"+m+"_"+s;
						
			xmlhttp.open("GET",'ajax.php?action=findupdate',true);
			xmlhttp.send(null);
		/* ----------------------------------AJAX : End ------------------------------------*/
	}//function
	
	setInterval("beginrefresh()",10000);
	
	
	//This function submits the form and open a new tab with report
	function showReport(usr){
		var repRange = document.getElementById("recordRange").value;
		if(repRange==""){
			alert("Select an Option");
			return false;
		}else{
			document.userRecordFrm.target='_blank';
			document.userRecordFrm.action='report.php?dayRange='+repRange+'&userid='+usr;
			document.userRecordFrm.submit();
		}//else
	}
	
	
	//Logout the current active user
	function logout(){
		var timeStr = document.getElementById("dateString").value;
		window.location='logout.php?time='+timeStr;
		//document.userRecordFrm.submit();
	}

	function hideMessage(){
		$("#message").animate({opacity: "hide", bottom: "0"}, "slow");	
	}
