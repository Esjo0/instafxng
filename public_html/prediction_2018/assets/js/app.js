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

	this.Process_User_Details = function (){
		var nickname = document.getElementById('UserDetails_nn').value;
		var fullname = document.getElementById('UserDetails_fn').value;
		var phone = document.getElementById('UserDetails_pn').value;
		var email = document.getElementById('UserDetails_ea').value;
		var rma_prediction = document.getElementById('rma_p').value;
		var lfc_prediction = document.getElementById('lfc_p').value;
		var prediction = rma_prediction+'*'+lfc_prediction;
		var query = "INSERT INTO sports_leads (nickname, full_name, phone, email, prediction) VALUES ('"+nickname+"', '"+fullname+"', '"+phone+"', '"+email+"', '"+prediction+"');";
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
						console.log(XMLHttpRequestObject.responseText);
					}
					if(type == '2'){
						//console.log(XMLHttpRequestObject.responseText);
						sports_pred.ShowPredictions(XMLHttpRequestObject.responseText);
					}
					//document.getElementById(response_div).innerHTML = XMLHttpRequestObject.responseText;
				}
			};

		}
		else {   return false;    }
	};

	this.GetPredictions = function(num){
		var query = "SELECT nickname, prediction FROM sports_leads ORDER BY created DESC LIMIT "+num;
		this.ajax_request('form_preloader', query, '2');
	};

	this.ShowPredictions = function(predictions_array)
	{
		p_table = document.getElementById('predictions');
		predictions = JSON.parse(predictions_array);
		count = 10;
		for(prediction in predictions)
		{
			row = p_table.insertRow(0);
			row.insertCell(0).innerHTML = count;
			row.insertCell(1).innerHTML = prediction['nickname'];
			row.insertCell(2).innerHTML = prediction['prediction'];
			--count;
		}

	};

	this.validateNickName = function(nickname)
	{
		var query = "SELECT nickname FROM sports_leads WHERE nickname = '"+nickname+"' "
		this.ajax_request('', query, '3');
	}

}
var sports_pred = new Sports_Prediction();