function Sports_Prediction()
{
	this.validate_User_Details = function()
	{
		if( document.ud_form.UserDetails_nn.value == "" ) {
			alert( "Please provide your Nickname!" );
			document.ud_form.UserDetails_nn.focus() ;
			return false;
		}

		if( document.ud_form.UserDetails_fn.value == "" ) {
			alert( "Please provide your Full Name!" );
			document.ud_form.UserDetails_fn.focus() ;
			return false;
		}

		if( document.ud_form.UserDetails_ea.value == "" ) {
			alert( "Please provide your Email Address!" );
			document.ud_form.UserDetails_ea.focus() ;
			return false;
		}

		if( document.ud_form.UserDetails_pn.value == "" ) {
			alert( "Please provide your Phone Number!" );
			document.ud_form.UserDetails_pn.focus() ;
			return false;
		}

		if( document.ud_form.Zip.value == "" || isNaN( document.ud_form.Zip.value ) || document.ud_form.Zip.value.length != 5 ) {
			alert( "Please provide a zip in the format #####." );
			document.myForm.Zip.focus() ;
			return false;
		}

		if( document.ud_form.Country.value == "-1" )
		{
			alert( "Please provide your country!" );
			return false;
		}
		return( true );
	};

	this.GetMsgs = function() {
		var query = "SELECT * FROM sports_leads_msgs ORDER BY created ASC";
		this.ajax_request('', query, '3');
	};
	
	this.replyMsg = function(nick)
	{
		var msg_box = document.getElementById('new_msg_textbox');
		msg_box.focus();
		msg_box.value = "@"+nick+" ";
	};

	this.addLike = function(id)
	{
		var query = "UPDATE sports_leads_msgs SET likes = CONCAT(likes + 1) WHERE msg_id = "+id;
		console.log(query);
		this.ajax_request('', query, '4');
	};

	this.ShowMsgs = function(json) {
		var msgs_array = JSON.parse(json);
		console.log(msgs_array);
		if(msgs_array != "")
		{
			var msg_div = document.getElementById('msgs');
			document.getElementById('msg_preloader').style.display = 'none';
			var display = "";
			for(var index = 0, len = msgs_array.length; index < len; ++index)
			{
				var msg_id = msgs_array[index]['msg_id'];
				var nick = msgs_array[index]['nickname'];
				display += "<div class='w3-card-4'>" +
			"<header class='w3-container'><h3></h3></header> " +
					"<div class='w3-container'> " +
					"<span style='padding-bottom: 3px'> </span> " +
					"<img height='40px' width='40px' src='assets/images/img_avatar3.jpg' alt='Avatar' class='w3-left w3-circle'><p>"+msgs_array[index]['nickname']+"</p> " +
					"<hr> " +
					"<p>"+msgs_array[index]['msg']+"</p> " +
					"</div> " +
					"</div><br>";
					/* "<footer class='w3-container w3-light-grey'> " +
					"<div class='w3-col s6 m6 l6'><center><button onclick='sports_pred.addLike("+msg_id+")' class='w3-button'>"+msgs_array[index]['likes']+"  <i class='glyphicon glyphicon-heart-empty '></i></button></center></div> " +
					"<div class='w3-col s6 m6 l6'><center><button onclick='sports_pred.replyMsg("+nick+")' class='w3-button'><i class='glyphicon glyphicon-refresh '></i></button></center></div> " +
					"</footer> " + */
			}
			msg_div.innerHTML = display;
		}

	};

	this.show_chat = function(div, div2, cont, state)
	{
		var x = document.getElementById(div);
		var y = document.getElementById(div2);
		//var container = document.getElementById(cont);
		if (x.style.display === 'none')
		{
			document.getElementById(state).innerHTML = "<i class='glyphicon glyphicon-send my-float'></i>";
			x.style.display = 'block';
			y.focus();
			//document.getElementById(state).style.display = 'none';
			//container.scrollTop = element.scrollHeight;
		}
		else
		{
			if(y.value.length > 0)
			{
				this.sendMsg(y.value);
				document.getElementById(state).innerHTML = "<i class='glyphicon glyphicon-comment my-float'></i>";
				x.style.display = 'none';
			}
			else
			{
				x.style.display = 'none';
				document.getElementById(state).innerHTML = "<i class='glyphicon glyphicon-comment my-float'></i>";
			}

		}
	};

	this.MsgBoxOnBlur = function()
	{
		//document.getElementById('new_msg').style.display = 'none';
		//document.getElementById('btn_ico').classList = 'glyphicon glyphicon-comment my-float';
	};

	this.sendMsg = function(msg) {
		var email = this.getFromStorage('email');
		var nickname = this.getFromStorage('nickname');
		var query = "INSERT INTO sports_leads_msgs (msg, email, nickname) VALUES ('"+msg+"', '"+email+"', '"+nickname+"')";
		this.ajax_request('', query, '4');
	};

	this.addToStorage = function (key, value) {
		if (typeof(Storage) !== "undefined"){localStorage.setItem(key, value);}
		else{console.log("No Storage Support");}
	};

	this.getFromStorage = function (key) {
		return localStorage.getItem(key);
	};

	this.TableRowCount = function(id)
	{
		var totalRowCount = 0;
		var rowCount = 0;
		var table = document.getElementById(id);
		var rows = table.getElementsByTagName("tr");
		for (var i = 0; i < rows.length; i++)
		{
			totalRowCount++;
			if (rows[i].getElementsByTagName("td").length > 0) {rowCount++;}
		}
		return totalRowCount;
	};

	this.Process_User_Details = function (){
		var nickname = document.getElementById('UserDetails_nn').value;
		var fullname = document.getElementById('UserDetails_fn').value;
		var phone = document.getElementById('UserDetails_pn').value;
		var email = document.getElementById('UserDetails_ea').value;
		var rma_prediction = document.getElementById('rma_p').value;
		var lfc_prediction = document.getElementById('lfc_p').value;
		var prediction = rma_prediction+'*'+lfc_prediction;
		this.addToStorage('nickname', nickname);
		this.addToStorage('email', email);
		var query = "INSERT INTO sports_leads (nickname, fullname, phone, email, prediction) VALUES ('"+nickname+"', '"+fullname+"', '"+phone+"', '"+email+"', '"+prediction+"');";
		console.log(query);
		document.getElementById('ud_form').style.display = 'none';
		document.getElementById('form_preloader').style.display = 'block';
		this.ajax_request('form_preloader', query, '1');
	};

	this.ajax_request = function (response_div, query, type)
	{
		var XMLHttpRequestObject = false;
		if (window.XMLHttpRequest) {XMLHttpRequestObject = new XMLHttpRequest();}
		else if (window.ActiveXObject) {XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");}
		if(XMLHttpRequestObject)
		{
			XMLHttpRequestObject.open('POST', "assets/server_script.php");
			XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			XMLHttpRequestObject.send("query="+query+"&type="+type);
			XMLHttpRequestObject.onreadystatechange = function() {
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
				{
					if(type == '1'){
						sports_pred.GetPredictions(10);
						//console.log(XMLHttpRequestObject.responseText);
					}
					if(type == '2'){
						//console.log(XMLHttpRequestObject.responseText);
						sports_pred.ShowPredictions(XMLHttpRequestObject.responseText);
					}
					if(type == '3'){
						//console.log(XMLHttpRequestObject.responseText);
						sports_pred.ShowMsgs(XMLHttpRequestObject.responseText);
					}
					if(type == '4'){
						sports_pred.GetMsgs();
					}
					//document.getElementById(response_div).innerHTML = XMLHttpRequestObject.responseText;
				}
			};

		}
		else {   return false;    }
	};

	this.GetPredictions = function(num){
		var query = "SELECT nickname, prediction FROM sports_leads ORDER BY created ASC";
		this.ajax_request('form_preloader', query, '2');
	};

	this.ShowPredictions = function(predictions_array)
	{
		document.getElementById('form_preloader').style.display = 'none';
		document.getElementById('p_list').style.display = 'block';
		var p_table = document.getElementById('predictions');
		p_table.innerHTML = "";
		var predictions = JSON.parse(predictions_array);
		console.log(predictions);
		for(index = 0, len = predictions.length; index < len; ++index)
		{
			var count = this.TableRowCount('predictions');
			var pd = predictions[index]['prediction'].split("");
			var pd_ = "LFC "+pd[2]+" : "+pd[0]+" RMA";
			//console.log(prediction);
			row = p_table.insertRow(count - 1);
			//row.insertCell(0).innerHTML = count + 1;
			row.insertCell(0).innerHTML = "";
			row.insertCell(1).innerHTML = predictions[index]['nickname'];
			row.insertCell(2).innerHTML = pd_;
		}

	};

	this.validateNickName = function(nickname)
	{
		var query = "SELECT nickname FROM sports_leads WHERE nickname = '"+nickname+"' "
		//this.ajax_request('', query, '3');
	}

}
var sports_pred = new Sports_Prediction();