function browserVerification(){
	var xmlhttp;
	if (window.XMLHttpRequest){
	  // code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}else if (window.ActiveXObject){
	  // code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}else{
	  alert("Your browser does not support XMLHTTP!");
	  //xmlhttp = 0;
	}//else
	return xmlhttp;	
}//function

function processFunction(actiontype){	
	var xmlhttp;
	var fileToProcess;
	
	xmlhttp = browserVerification();	
	
	switch(actiontype){
		case 'get_available_stock':
			var hall_id = document.getElementById("hall_id").value;
			fileToProcess = "ajax.php?action=get_available_stock&hall_id="+hall_id;
		break;
		
		case 'get_year':
			fileToProcess = "ajax.php?action=get_year";
		break;
		
		case 'get_product':
			var category_id = document.getElementById("category_id").value;
			fileToProcess = "ajax.php?action=get_product&category_id="+category_id;
		break;
		
		case 'get_seat_setting':
			var hall_id = document.getElementById("hall_id").value;
			var seat_id = document.getElementById("id").value;
			var year = document.getElementById("year").value;
			fileToProcess = "ajax.php?action=get_seat_setting&hall_id="+hall_id+"&seat_id="+seat_id+"&year="+year;
		break;
		
		case 'check_student_order':
			var datef = document.getElementById("issue_date").value;
			var date_end = document.getElementById("end_date").value;
			fileToProcess = "ajax.php?action=check_student_order&issue_date="+datef+"&end_date="+date_end;
		break;
		
		case 'check_student_order_end':
			var datef = document.getElementById("end_date").value;
			fileToProcess = "ajax.php?action=check_student_order&issue_date="+datef;
		break;
		
		
		case 'get_setting':
			var hall_id = document.getElementById("hall_id").value;
			var year = document.getElementById("year").value;
			fileToProcess = "ajax.php?action=get_setting&hall_id="+hall_id+"&year="+year;
		break;
		
		case 'get_stock':
			var hall_id = document.getElementById("hall_id").value;
			var issue_date = document.getElementById("issue_date").value;
			fileToProcess = "ajax.php?action=get_stock&hall_id="+hall_id+"&issue_date="+issue_date;	
		break;
		case 'get_consume':
			var hall_id = document.getElementById("hall_id").value;
			var issue_date = document.getElementById("issue_date").value;
			fileToProcess = "ajax.php?action=get_consume&hall_id="+hall_id+"&issue_date="+issue_date;	
		break;
		case 'get_hall':
			var group_id = document.getElementById("group_id").value;
			fileToProcess = "ajax.php?action=get_hall&group_id="+group_id;	
		break;
		case 'get_room':
			var floor_id = document.getElementById("floor_id").value;
			fileToProcess = "ajax.php?action=get_room&floor_id="+floor_id;	
		break;
		case 'get_seat':
			var room_id = document.getElementById("room_id").value;
			var curr_seat_id
			if(document.getElementById("curr_seat_id")){
				curr_seat_id = document.getElementById("curr_seat_id").value;
			}else{
				curr_seat_id = "";
			}//else
				fileToProcess = "ajax.php?action=get_seat&room_id="+room_id+"&curr_seat_id="+curr_seat_id;
			
		break;
		
		case 'get_floor':
			var block_id = document.getElementById("block_id").value;
			fileToProcess = "ajax.php?action=get_floor&block_id="+block_id;	
		break;
		
		case 'get_block':
			var hall_id = document.getElementById("hall_id").value;
			fileToProcess = "ajax.php?action=get_block&hall_id="+hall_id;	
		break;
		
		case 'check_username_availability':
			var username = document.getElementById("username").value;
			fileToProcess = "ajax.php?action=check_username_availability&username="+username;	
		break;
		
		case 'default':
				return false;
		break;
		//For format to procedure : End
	}//switch
	
	//alert(resArr[0]);
	xmlhttp.onreadystatechange=function(){

		var action = actiontype;
		
		if(action == 'check_username_availability'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("username_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("username_display").style.display = "block";	
				document.getElementById("username_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_seat_setting'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("setting_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("setting_display").style.display = "block";	
				document.getElementById("setting_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_product'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("product_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("product_display").style.display = "block";	
				document.getElementById("product_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_year'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("year_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("year_display").style.display = "block";	
				document.getElementById("year_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'check_student_order'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("order_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("order_display").style.display = "block";	
				document.getElementById("order_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'check_student_order_end'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("order_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("order_display").style.display = "block";	
				document.getElementById("order_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_hall'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("hall_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("hall_display").style.display = "block";	
				document.getElementById("hall_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_seat'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("seat_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("seat_display").style.display = "block";	
				document.getElementById("seat_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_block'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("block_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("block_display").style.display = "block";	
				document.getElementById("block_display").innerHTML = xmlhttp.responseText;
			}//else if
			
		}else if(action == 'get_floor'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("floor_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("floor_display").style.display = "block";	
				document.getElementById("floor_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_consume'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("consumeinsert").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("consumeinsert").style.display = "block";	
				document.getElementById("consumeinsert").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_stock'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("stockinsert").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("stockinsert").style.display = "block";	
				document.getElementById("stockinsert").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_setting'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("setting_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("setting_display").style.display = "block";
				document.getElementById("setting_display").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_available_stock'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("available_stock_view").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("available_stock_view").style.display = "block";
				document.getElementById("available_stock_view").innerHTML = xmlhttp.responseText;
			}//else if
		}else if(action == 'get_room'){
			if(xmlhttp.readyState!=4){
				document.getElementById("loaderContainer").style.display = "block";
				document.getElementById("room_display").style.display = "none";
			}else if(xmlhttp.readyState==4){
				document.getElementById("loaderContainer").style.display = "none";
				document.getElementById("room_display").style.display = "block";	
				document.getElementById("room_display").innerHTML = xmlhttp.responseText;
			}//else if
		}//else if
	}//onready state change
	xmlhttp.open("GET",fileToProcess,true);
	xmlhttp.send(null);
}//Function End



//---------------------------------------------------Function to Verify System---------------------------------

	
//Check Whether a Number or Not
function isNUM(id){
	var container, num;
	container = id;
	
	num = document.getElementById(container).value;
	
	if(isNaN(num)){
		alert("Only Number Supported!");
		document.getElementById(container).value = '';
	}//if
}//function

function hallcharge(container){
	var estab,readm, sd, messad, donation, seatrent, utencro, maint, crnpape, inter, conti, total, container;
	isNUM(container);
	estab = (document.getElementById('estab').value == '') ? 0.00 : parseFloat(document.getElementById('estab').value);
	readm = (document.getElementById('readm').value == '') ? 0.00 : parseFloat(document.getElementById('readm').value);
	sd = (document.getElementById('sd').value == '') ? 0.00 : parseFloat(document.getElementById('sd').value);
	messad = (document.getElementById('messad').value == '') ? 0.00 : parseFloat(document.getElementById('messad').value);
	donation = (document.getElementById('donation').value == '') ? 0.00 : parseFloat(document.getElementById('donation').value);
	seatrent = (document.getElementById('seatrent').value == '') ? 0.00 : parseFloat(document.getElementById('seatrent').value);
	utencro = (document.getElementById('utencro').value == '') ? 0.00 : parseFloat(document.getElementById('utencro').value);
	maint = (document.getElementById('maint').value == '') ? 0.00 : parseFloat(document.getElementById('maint').value);
	crnpape = (document.getElementById('crnpape').value == '') ? 0.00 : parseFloat(document.getElementById('crnpape').value);
	inter = (document.getElementById('inter').value == '') ? 0.00 : parseFloat(document.getElementById('inter').value);
	conti = (document.getElementById('conti').value == '') ? 0.00 : parseFloat(document.getElementById('conti').value);
	
	total = estab+readm+sd+messad+donation+seatrent+utencro+maint+crnpape+inter+conti;
	total = total.toFixed(2);
	document.getElementById('total').value = total;

}//function

//Check/uncheck All Items of a bunch of check box
function checkAllItem(toCheck) {
	
	var master, objects, name, attribute;
	name = toCheck;

	master = document.getElementById("check_all").checked;
	objects = document.getElementsByTagName("input");
	
	for(i = 1; i < objects.length; i++){
		name = objects.item(i).name;
		attribute = objects.item(i).getAttribute("disabled");
		
		if(name == toCheck && attribute != 'disabled'){
			objects.item(i).checked = master;
		}//if
	}//for
}//function
		
		
//Set a Field empty on click
function makeEmpty(id){
	var container;
	container = id;
	document.getElementById(container).value = '';
}//function

//Validate User Name Field
function validateUser(){
	var userRegEx;
	username = document.getElementById('username').value;
	userRegEx = /^[A-Za-z0-9_]+$/;
	
	if(!username.match(userRegEx)){
		return false;
	}else{
		return true;
	}//else
}//function

//Check for Available User Name
function checkUsername(){
	var username;
	username = document.getElementById('username').value;
	
	if(username == ''){
		alert("Please, Insert Username!");
		return false;
	}else{
		if(username != "" && validateUser() != true){
			alert("Username only supports alphanumeric character, including '_'");
		}else{
			processFunction("check_username_availability");
		}//else
	}//else
}//function

//--------------------------------------------------------Validate Submit Button-----------------------------------
// User Group Fields
function validateUserGroup(){
	var name; 
	name = document.getElementById('name').value;
	
	if(name == ""){
		alert("Please, Insert User Group Name.");
		return false;
	}else{
		return true;
	}//else
}//function

// User Fields
function validateUserCreate(token){
	var hall_id, full_name, official_name, username, password, retype_password;
	
	if(document.getElementById('hall_id')){
		hall_id = document.getElementById('hall_id').value;	
	}//if
	
	official_name = document.getElementById('official_name').value;
	username = document.getElementById('username').value;
	password = document.getElementById('password').value;
	retype_password = document.getElementById('retype_password').value;
		
	if(hall_id == 0){
		alert("Please, Select Hall.");
		return false;
	}else if(official_name == ""){
		alert("Please, Insert Official Name.");
		return false;
	}else if(username == ""){
		alert("Please, Insert Username.");
		return false;
	}else if(username != "" && validateUser()!= true){
		alert("Username only supports digit, number & '_'");
		return false;
	}else if(token == 0 && password == ""){
		alert("Please, Insert Password.");
		return false;
	}else if(token == 0 && retype_password == ""){
		alert("Password Confirmation field is empty!");
		return false;
	}else if(password != retype_password){
		alert("Password Confirmation does not match!");
		return false;
	}else{
		return true;
	}//else
}//function

//Password Change Fields Validity
function validateChangePass(){
	var cur_password, new_password, retype_password; 
	
	cur_password = document.getElementById('cur_password').value;
	new_password = document.getElementById('new_password').value;
	retype_password = document.getElementById('retype_password').value;
	
	if(cur_password == ""){
		alert("Please, Insert your current Password!");
		return false;
	}else if(new_password == ""){
		alert("Please, Insert your new Password!");
		return false;
	}else if(retype_password == ""){
		alert("Please, Retype your new password!");
		return false;
	}else if(new_password != retype_password){
		alert("Your new password does not match");
		return false;
	}else{
		return true;
	}//else
}//function

function enable_input(id){
	var product, test;

	product = document.getElementById('product_'+id).checked;
	
	if(product == true){
		document.getElementById('qty_'+id).readOnly = false; 
		if(document.getElementById('total_'+id)){
			document.getElementById('total_'+id).readOnly = false; 
		}//if
	}else if(product == false){
		document.getElementById('qty_'+id).value = ''; 
		document.getElementById('qty_'+id).readOnly = true; 
		if(document.getElementById('total_'+id)){
			document.getElementById('total_'+id).value = ''; 
			document.getElementById('total_'+id).readOnly = true;
			document.getElementById('unit_price_'+id).value = ''; 
		}//if
	}//else if
}//function

function getTotalPrice(nam_id, id, balance, avg_price, prev_total_price, in_total, field_ids, container){
	var qty, total, unit_price, balance, new_ttl_price, net_total, i, sub_total=0, total2, net_total2,i, field_arr, count, container, limit;
	isNUM(container);
	
	float_balance = parseFloat(balance);
	float_avg_price = parseFloat(avg_price);
	demo_qty = document.getElementById('qty_'+id).value;
	demo_total = document.getElementById('total_'+id).value;
	id_total = document.getElementById('total_'+id);

	qty = parseFloat(document.getElementById('qty_'+id).value);
	total = parseFloat(document.getElementById('total_'+id).value);
	prev_total_price = parseFloat(prev_total_price);
	
	
	new_ttl_price = parseFloat(prev_total_price) + total;
	unit_price = total / qty;
	total_qty = parseFloat(float_balance+qty);
	total_price = parseFloat(prev_total_price + total);
	pavg_price = total_price / total_qty;
	
	balance = float_balance.toFixed(2);
	unit_price = unit_price.toFixed(2);
	total_qty = total_qty.toFixed(2);
	pavg_price = pavg_price.toFixed(2);
	new_ttl_price = new_ttl_price.toFixed(2);
	prev_total_price = prev_total_price.toFixed(2);
	
	if((demo_qty == "") || (demo_total == "")){
		document.getElementById('unit_price_'+id).value = "";
		document.getElementById('avg_price_'+id).value = avg_price;
		document.getElementById('total_price_'+id).value = prev_total_price;
		document.getElementById('in_total'+id).value = prev_total_price;
	}else if((demo_qty != "") && (demo_total != "")){
		document.getElementById('unit_price_'+id).value = unit_price;
		document.getElementById('balance_'+id).value = total_qty;
		document.getElementById('avg_price_'+id).value = pavg_price;
		document.getElementById('total_price_'+id).value = new_ttl_price;
	}
	
	in_total= parseInt(in_total);
	field_arr = field_ids.split(",");
	count = field_ids.match(/,/g);  
	limit = count.length;
	net_total = 0;
	
	for(i = 0; i < limit; i++){
		if(document.getElementById("total_"+field_arr[i]).value == ""){	
			net_total += 0;
		}else{
			net_total += parseFloat(document.getElementById("total_"+field_arr[i]).value);
		}
		//alert(net_total);
	}
	final_total = net_total + in_total;
	final_total = final_total.toFixed(2);
	document.getElementById("net_total").value = final_total;
}//function

function getTotalPrice2(prev_qty, prev_balance, prev_total_price, prev_avg_price, balance_total_price){
	var new_balance, new_avg_price, prev_qty, prev_balance, prev_total_price, prev_avg_price,
	prev_unit_price, demo_qty, demo_total, qty, new_total_price;
	
	prev_qty = parseFloat(prev_qty);
	prev_balance = parseFloat(prev_balance);
	prev_total_price = parseFloat(prev_total_price);
	prev_avg_price = parseFloat(prev_avg_price);
	balance_total_price = parseFloat(balance_total_price);
	prev_unit_price = prev_total_price / prev_qty;
	
	demo_qty = document.getElementById('qty').value;
	demo_total = document.getElementById('total').value;
	
	qty = parseFloat(demo_qty);
	total_price = parseFloat(demo_total);
	new_unit_price = parseFloat(total_price/qty);
	
	new_balance = parseFloat(prev_balance - prev_qty + qty);
	new_total_price = parseFloat(balance_total_price - prev_total_price + total_price);
	new_avg_price = (new_total_price/new_balance);
	
	new_balance = new_balance.toFixed(2);
	new_avg_price = new_avg_price.toFixed(2);
	new_unit_price = new_unit_price.toFixed(2);
	
	if((demo_qty == "") || (demo_total == "")){
		document.getElementById('unit_price').value = prev_unit_price;
		document.getElementById('balance').value = prev_balance;
		document.getElementById('avg_price').value = prev_avg_price;
	}else if((demo_qty != "") && (demo_total != "")){
		document.getElementById('unit_price').value = new_unit_price;
		document.getElementById('balance').value = new_balance;
		document.getElementById('avg_price').value = new_avg_price;
	}
}//function

function getConsumePrice(nam_id, id, balance, field_ids, ttl_price, in_total){
	
	var qty, unit_price, t_price, net_total, total_price, net_total_price, unitt, balance;
	
	demo_qty = document.getElementById('qty_'+id).value;
	qty = parseFloat(document.getElementById('qty_'+id).value);
	unit_price = parseFloat(document.getElementById('unit_price_'+id).value);
	t_price = parseFloat(document.getElementById('total_price_'+id).value);
	net_total = parseFloat(document.getElementById('net_total').value);
	int_balance = parseFloat(balance);
	remain_balance = int_balance - qty;
	
	price = qty*unit_price;
	total_price = t_price-price;
	net_total_price = net_total - price;
	
	total_price = total_price.toFixed(2);
	remain_balance = remain_balance.toFixed(2);
	
	unitt = document.getElementById('unit_'+id).innerHTML;
	product = document.getElementById('productt_'+id).innerHTML;
	
	if(demo_qty == ""){
		document.getElementById('balance_'+id).value = balance;
		document.getElementById('total_price_'+id).value = ttl_price;
	}else{
		document.getElementById('balance_'+id).value = remain_balance;
		document.getElementById('total_price_'+id).value = total_price;
		if(qty > int_balance){
			alert("You have remaining "+int_balance+" "+unitt+" of "+product);
			document.getElementById('qty_'+id).value = "";
			document.getElementById('balance_'+id).value = balance;
		}//if
	}
	
	in_total= parseInt(in_total);
	count = field_ids.match(/,/g);  
	field_arr = field_ids.split(",");
	limit = count.length;
	net_total = 0;
	
	for(i = 0; i < limit; i++){
		net_total += parseFloat(document.getElementById("total_price_"+field_arr[i]).value);
	}
	net_total = net_total.toFixed(2);
	document.getElementById("net_total").value = net_total;
	
}//function

function validateBalance(nam_id, id){
	var qty, balance, unitt;
	qty = parseFloat(document.getElementById('qty_'+id).value);
	balance = parseFloat(document.getElementById('balance_'+id).value);
	name = document.getElementById('pname_'+id).innerHTML;
	unitt = document.getElementById('unit_price_'+id).value;
	if(qty > balance){
		alert("you have remaining "+balance+" "+unitt+" "+name);
		document.getElementById('qty_'+id).value = "";
	}//if
}//function

function assignSeat(id){
	var total_seat,id;
	total_seat = parseInt(document.getElementById('total_seat').value) - 1;
	//Put value in hidden field
	document.getElementById('seat_id').value = id;
	
	//Change color of other Seats
	for(i=0;i<=total_seat;i++){
		document.getElementsByName('notbooked')[i].style.opacity = "1.0";
	}//for
	//Change the clicked seat color
	document.getElementById(id).style.opacity = "0.5";
}//function



function add_stock(){
	var issue_date;	
	
	issue_date = document.getElementById('issue_date').value;
	
	if(issue_date == ""){
		alert("Please, Select Issue Date ")
		return false;	
	}else{
		return true;	
	}//else
	
}//function

function validateblock(){
	var hall_id, name;
	
	hall_id = document.getElementById('hall_id').value;
	name = document.getElementById('name').value;
	
	if(hall_id == 0 || hall_id == ""){
		alert("Please, Select Hall ")
		return false;	
	}else if(name == ""){
		alert("Please, Insert Block Name")
		return false;
	}else{
		return true;	
	}//else
}//function

function validatefloor(){	
	var hall_id, block_id, name;
	
	hall_id = document.getElementById('hall_id').value;
	block_id = document.getElementById('block_id').value;
	name = document.getElementById('name').value;
	
	if(hall_id == 0 || hall_id == ""){
		alert("Please, Select Hall ")
		return false;
	}else if(block_id == 0 || block_id == ""){
		alert("Please, Select Block ")
		return false;
	}else if(name == ""){
		alert("Please, Insert Floor Name")
		return false;
	}else{
		return true;	
	}//else
}//function

function validateroom(){	
	var hall_id, block_id, floor_id, patern_id, name;
	
	hall_id = document.getElementById('hall_id').value;
	block_id = document.getElementById('block_id').value;
	floor_id = document.getElementById('floor_id').value;
	patern_id = document.getElementById('patern_id').value;
	name = document.getElementById('name').value;
	
	if(hall_id == 0 || hall_id == ""){
		alert("Please, Select Hall ")
		return false;
	}else if(block_id == 0 || block_id == ""){
		alert("Please, Select Block ")
		return false;
	}else if(floor_id == 0 || floor_id == ""){
		alert("Please, Select Floor ")
		return false;
	}else if(patern_id == 0 || patern_id == ""){
		alert("Please, Select Patern ")
		return false;
	}else if(name == ""){
		alert("Please, Insert Room No")
		return false;
	}else{
		return true;	
	}//else
}//function

function validateseat(){	
	var hall_id, block_id, floor_id, room_id, name;
	
	hall_id = document.getElementById('hall_id').value;
	block_id = document.getElementById('block_id').value;
	floor_id = document.getElementById('floor_id').value;
	room_id = document.getElementById('room_id').value;
	name = document.getElementById('name').value;
	
	if(hall_id == 0 || hall_id == ""){
		alert("Please, Select Hall ")
		return false;
	}else if(block_id == 0 || block_id == ""){
		alert("Please, Select Block ")
		return false;
	}else if(floor_id == 0 || floor_id == ""){
		alert("Please, Select Floor ")
		return false;
	}else if(room_id == 0 || room_id == ""){
		alert("Please, Select Room ")
		return false;
	}else if(name == ""){
		alert("Please, Insert Seat No")
		return false;
	}else{
		return true;	
	}//else
}//function

function validatefacilities(){
	var name;
	
	name = document.getElementById('name').value;
	
	if(name == ""){
		alert("Please, Insert Room Name")
		return false;
	}else{
		return true;	
	}//else
}//function


function validatepatern(){
	var name, number_seat;
	
	name = document.getElementById('name').value;
	number_seat = document.getElementById('number_seat').value;
	
	if(name == ""){
		alert("Please, Insert Room Name")
		return false;
	}else if(number_seat == ""){
		alert("Please, Insert Seat Number")
		return false;
	}else{
		return true;	
	}//else
}//function

function validatehall(){
	var name, location, image1, image2, image3, image4, image5, short_description, description;	
	
	name = document.getElementById('name').value;
	location = document.getElementById('location').value;
	short_description = document.getElementById('short_description').value;
	description = document.getElementById('description').value;
	
	if(name == ""){
		alert("Please, Insert Hall Name")
		return false;
	}else if(location == ""){
		alert("Please, Insert Hall Location")
		return false;
	}else if(short_description == ""){
		alert("Please, Insert Hall Short Description")
		return false;
	}else if(description == ""){
		alert("Please, Insert Hall Full Description")
		return false;
	}else{
		return true;	
	}//else
}//function

//Validate Email Address
function validateEmailAddress(email){
	var emailRegEx = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;

	if(!email.match(emailRegEx)){
		alert("Please put a valid Email Address");
		return false;
	}else{
		return true;
	}//else
}//function

function validateUserdGroup(){
	var registration_no, name, course_name, faculty_name, email, mobile, address, permanent_address, gnd_m, gnd_f;	
	
	registration_no = document.getElementById('registration_no').value;
	name = document.getElementById('name').value;
	course_name = document.getElementById('course_name').value;
	faculty_name = document.getElementById('faculty_name').value;
	email = document.getElementById('email').value;
	mobile = document.getElementById('mobile').value;
	address = document.getElementById('address').value;
	permanent_address = document.getElementById('permanent_address').value;
	gnd_m = document.getElementById('gender_m').value;
	gnd_f = document.getElementById('gender_f').value;
	
	if(registration_no == ""){
		alert("Please, Insert Your Registration Number!")
		return false;
	}else if(name == ""){
		alert("Please, Insert Your Name!")
		return false;
	}else if(course_name == ""){
		alert("Please, Insert Your Course Name")
		return false;
	}else if(faculty_name == ""){
		alert("Please, Insert Your Faculty Name")
		return false;
	}else if(gnd_m == "" && gnd_f == ""){
		alert("Please, Select Your Gender!")
		return false;
	}else if(email == ""){
		alert("Please, Insert Your Email Address")
		return false;
	}else if(mobile == ""){
		alert("Please, Insert Your Mobile Number")
		return false;
	}else if(address == ""){
		alert("Please, Insert Your Present Address")
		return false;
	}else if(permanent_address == ""){
		alert("Please, Insert Your Permanent Address")
		return false;
	}else if(document.getElementById('email').value != ""){
		email = document.getElementById('email').value;
			if(validateEmailAddress(email)){
				//return true;
			}else{
				return false;
			}//else
	}else{
		return true;
	}//else
}//function

function validateunit(){
	var name;
	
	name = document.getElementById('name').value;
	
	if(name == ""){
		alert("Please, Insert Unit Name")
		return false;
	}else{
		return true;	
	}//else
}//function

function validateproduct_category(){
	var name, unit_id;	
	
	name = document.getElementById('name').value;
	unit_id = document.getElementById('unit_id').value;
	
	if(name == ""){
		alert("Please, Insert Product Category Name")
		return false;
	}else if(unit_id == 0 || unit_id == ""){
		alert("Please, Select Unit")
		return false;
	}else{
		return true;	
	}//else
}//function

function validateproduct(){
	var category_id, name;	
	
	name = document.getElementById('name').value;
	category_id = document.getElementById('category_id').value;
	
	if(category_id == 0 || category_id == ""){
		alert("Please, Select Category")
		return false;
	}else if(name == ""){
		alert("Please, Insert Product Name")
		return false;
	}else{
		return true;	
	}//else
}//function

function validateForm(password_cond){
	var registration_no, password, name, confirm_password, department, roll_no, session, present_address, address, password_cond;	
	
	registration_no = document.getElementById('registration_no').value;
	password = document.getElementById('password').value;
	confirm_password = document.getElementById('confirm_password').value;
	name = document.getElementById('name').value;
	department = document.getElementById('department').value;
	roll_no = document.getElementById('roll_no').value;
	session = document.getElementById('session').value;
	present_address = document.getElementById('present_address').value;
	address = document.getElementById('address').value;
	
	if(registration_no == ""){
		alert("Please, Insert Ragistration Number")
		return false;
	}else if((password_cond == '1') && (password == "")){
		alert("Please, Insert Password!");
		return false;
	}else if((password_cond == '1') && (confirm_password == "")){
		alert("Please, Confirm Password!");
		return false;
	}else if(password != confirm_password){
		alert("Your Password Confirmation doesn't match!");
		return false;
	}else if(name == ""){
		alert("Please, Insert Name")
		return false;
	}else if(department == ""){
		alert("Please, Insert Department Name")
		return false;
	}else if(roll_no == ""){
		alert("Please, Insert Roll No")
		return false;
	}else if(session == ""){
		alert("Please, Insert Session name")
		return false;
	}else if(present_address == ""){
		alert("Please, Insert Present Address")
		return false;
	}else if(address == ""){
		alert("Please, Insert Parmanent Address")
		return false;
	}else{
		return true;
	}//else
}//function

function validateMealOrder(){
	var issue_date;	
	issue_date = document.getElementById('issue_date').value;
	if(issue_date == ""){
		alert("Please, Select Issue Date ")
		return false;	
	}else{
		return true;	
	}//else
}//function

function validateMealOrderView(){
	var order_date;	
	order_date = document.getElementById('order_date').value;
	if(order_date == ""){
		alert("Please, Select Issue Date ")
		return false;	
	}else{
		return true;	
	}//else
}//function

function validateStudentReport(){
	var student_id, start_date, end_date;
	
	student_id = document.getElementById('student_id').value;
	start_date = document.getElementById('start_date').value;
	end_date = document.getElementById('end_date').value;
	
	if(student_id == 0 || student_id == ""){
		alert("Please, Select Student Name ")
		return false;
	}else if(start_date == ""){
		alert("Please, Select Start Date ")
		return false;	
	}else if(end_date == ""){
		alert("Please, Select End Date ")
		return false;
	}else if(start_date > end_date){
		alert("Start date could not be greated than End date");
		return false;
	}else{
		return true;	
	}//else
}//function

function validatemsg(){
	var subject, message1;
	
	subject = document.getElementById('subject').value;
	message = document.getElementById('message1').value;
	
	if(subject == ""){
		alert("Please, Enter Your Subject Name. ")
		return false;	
	}else if(message == ""){
		alert("Please, Enter your Message ")
		return false;
	}else{
		return true;	
	}//else
}//function

function check_student_order(){
	var datef;
	
	datef = document.getElementById("issue_date").value;
	date_end = document.getElementById("end_date").value;
	if(datef != "" ||date_end !="" ){
		processFunction("check_student_order");
	}else{
		alert("Please, Select a Date!");	
	}
}

function check_student_order_end(){
	var datef;
	
	datef = document.getElementById("end_date").value;
	if(datef != ""){
		processFunction("check_student_order_end");
	}
	
}

function aval_report(){
	var hall_id;
	
	hall_id = document.getElementById("hall_id").value;
	if(hall_id == 0){
		alert("Please, Select a Hall. ")
		return false;	
	}else{
		return true;	
	}
}

function active_cell(value){
	//alert(value);
	if(value.readOnly==true){
		value.readOnly=false;
	}else{
		value.readOnly = true;
		value.value = "";
	}
}

function checkingDate(){
	var start_date, end_date;
	
	start_date = document.getElementById('start_date').value;
	end_date = document.getElementById('end_date').value;
	
	if((start_date == "") && (end_date == "")){
		alert("Please, Select Date ")
		return false;
	}else if((end_date != "") && (start_date != "")){
		if(start_date > end_date){
			alert("Start date could not be greated than End date");
			return false;
		}
	}else{
		return true;
	}//else

}//function

function checkDate(){
	var start_date, end_date,hall_name;
	
	if(document.getElementById('hall_id')){
		hall_name = document.getElementById('hall_id').value;
	}
	start_date = document.getElementById('start_date').value;
	end_date = document.getElementById('end_date').value;
	
	if(hall_name == "0"){
		alert("Please, Select Hall");
		return false;
	}else if((start_date == "") && (end_date == "")){
		alert("Please, Select Date ")
		return false;
	}else if((end_date != "") && (start_date != "")){
		if(start_date > end_date){
			alert("Start date could not be greated than End date");
			document.getElementById('end_date').value = "";
			return false;
		}
	}else{
		return true;
	}//else

}//function

function checkProduct(){
	var start_date, end_date, hall_name, product, meal_type;

	hall_name = document.getElementById('hall_id').value;
	product = document.getElementById('product_id').value;
	if(document.getElementById('type_id')){
		meal_type = document.getElementById('type_id').value;
	}
	start_date = document.getElementById('start_date').value;
	end_date = document.getElementById('end_date').value;

	if(product == '0'){
		alert("Please, Select Your Product");
		return false;
	}else if((end_date != "") && (start_date != "")){
		if(start_date > end_date){
			alert("Start date could not be greated than End date");
			return false;
		}
	}else{
		return true;
	}//else

}//function

function checkissueDate(){
	var start_date, end_date;
	start_date = document.getElementById('start_date').value;
	end_date = document.getElementById('end_date').value;
	
	if((end_date != "") && (start_date != "")){
		if(start_date > end_date){
			alert("Start date could not be greated than End date");
			return false;
		}
	}else{
		return true;
	}//else	
	
}//function*/


function validateStudent_mess_bill(){	
	var year, month, hall_id;
	year = document.getElementById('year').value;
	month = document.getElementById('month').value;
	hall_id = document.getElementById('hall_id').value;
	
	if(year == 0 || year == ""){
		alert("Please, Select Year ")
		return false;
	}else if(month == 0 || month == ""){
		alert("Please, Select Month ")
		return false;
	}else if(hall_id == 0 || hall_id == ""){
		alert("Please, Select Hall ")
		return false;
	}else{
		return true;	
	}//else
}//function

//Reset Password Function
function validateResetPassword(){
	
	var username, email;
	username = document.getElementById("username").value;
	email = document.getElementById("email").value;
	
	if(username == ''){
		alert("Please, Insert your User Name");
		return false;
	}else if(email == ''){
		alert("Please, Insert your E-mail Address");
		return false;	
	}else{
		return true;	
	}//else 
}//function 

function hourLunch(id){
	var hour_lunch = document.getElementById(id).value;
	isNUM(id);
	
	if(hour_lunch > 12){
		alert("Invalid Hour");
		document.getElementById("hour_lunch").value = "";
		document.getElementById("hour_lunch").focus();
	}else{
		document.getElementById("hour_lunch").value;
	}
}//function

function breakfast(breakfastId){
	
	var breakfast = document.getElementById(breakfastId).value;
	
	isNUM(breakfastId);
	
	if(breakfast > 12){
		alert("Invalid Hour");
		document.getElementById("hour_break").value = "";
		document.getElementById("hour_break").focus();
	}else{
		document.getElementById("hour_break").value;
	}
}//function

function dinner(dinnerId){
	
	var dinner = document.getElementById(dinnerId).value;
	
	isNUM(dinnerId);
	
	if(dinner > 12){
		alert("Invalid Hour");
		document.getElementById("hour_dinner").value = "";
		document.getElementById("hour_dinner").focus();
	}else{
		document.getElementById("hour_dinner").value;
	}
}//function

function brakfasthour(id){
	
	var brakfasthour = document.getElementById(id).value;
	
	isNUM(id);
	
	if(brakfasthour > 24){
		alert("Invalid Hour");
		document.getElementById("bf_hour").value = "";
		document.getElementById("bf_hour").focus();
	}else{
		document.getElementById("bf_hour").value;
	}
}//function

function dinnerhour(id){
	
	var dinnerhour = document.getElementById(id).value;
	
	isNUM(id);
	
	if(dinnerhour > 24){
		alert("Invalid Hour");
		document.getElementById("dn_hour").value = "";
		document.getElementById("dn_hour").focus();
	}else{
		document.getElementById("dn_hour").value;
	}
}//function

function lunchhour(id){
	
	var lunchhour = document.getElementById(id).value;
	
	isNUM(id);
	
	if(lunchhour > 24){
		alert("Invalid Hour");
		document.getElementById("ln_hour").value = "";
		document.getElementById("ln_hour").focus();
	}else{
		document.getElementById("ln_hour").value;
	}
}//function

function minutesLunch(id){
	var minutesLunch = document.getElementById(id).value;
	isNUM(id);
	
	if(minutesLunch > 59){
		alert("Invalid Minutes");
		document.getElementById("minutes_lunch").value = "";
		document.getElementById("minutes_lunch").focus();
	}else{
		document.getElementById("minutes_lunch").value;
	}
}//function

function minutesbreakfast(id){
	
	var minutesbreakfast = document.getElementById(id).value;
	
	isNUM(id);
	
	if(minutesbreakfast > 59){
		alert("Invalid Minutes");
		document.getElementById("minutes_break").value = "";
		document.getElementById("minutes_break").focus();
	}else{
		document.getElementById("minutes_break").value;
	}
}//function

function minutesdinner(id){
	
	var minutesdinner = document.getElementById(id).value;
	
	isNUM(id);
	
	if(minutesdinner > 59){
		alert("Invalid Minutes");
		document.getElementById("minutes_dinner").value = "";
		document.getElementById("minutes_dinner").focus();
	}else{
		document.getElementById("minutes_dinner").value;
	}
}//function

function brakfastseconds(id){
	
	var brakfastseconds = document.getElementById(id).value;
	
	isNUM(id);
	
	if(brakfastseconds > 59){
		alert("Invalid Seconds");
		document.getElementById("seconds_break").value = "";
		document.getElementById("seconds_break").focus();
	}else{
		document.getElementById("seconds_break").value;
	}
}//function

function dinnerseconds(id){
	
	var dinnerseconds = document.getElementById(id).value;
	
	isNUM(id);
	
	if(dinnerseconds > 59){
		alert("Invalid Seconds");
		document.getElementById("seconds_dinner").value = "";
		document.getElementById("seconds_dinner").focus();
	}else{
		document.getElementById("seconds_dinner").value;
	}
}//function

function lunchseconds(id){
	
	var lunchseconds = document.getElementById(id).value;
	
	isNUM(id);
	
	if(lunchseconds > 59){
		alert("Invalid Seconds");
		document.getElementById("seconds_lunch").value = "";
		document.getElementById("seconds_lunch").focus();
	}else{
		document.getElementById("seconds_lunch").value;
	}
}//function



