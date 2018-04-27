<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27/04/2018
 * Time: 1:40 PM
 */
if(issset($_POST['name'])){
echo $_POST['name'] ."<br />";
echo $_POST['email'] ."<br />";
echo $_POST['phone'] ."<br />";
echo $_POST['gender'] ."<br />";
echo "==============================<br />";}
echo "All Data Submitted Successfully!";
?>
<html>
<title>Submit Form without Page Refresh - PHP/jQuery - TecAdmin.net</title>
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    function SubmitFormData() {
        var name = $("#name").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var gender = $("input[type=radio]:checked").val();
        $.post("last_test.php", { name: name, email: email, phone: phone, gender: gender },
            function(data) {
                $('#results').html(data);
                $('#myForm')[0].reset();
            });


    }
</script>
</head>
<body>
  <form id="myForm" method="post">
    Name:    <input name="name" id="name" type="text" /><br />
     Email:   <input name="email" id="email" type="text" /><br />
     Phone No:<input name="phone" id="phone" type="text" /><br />
     Gender:  <input name="gender" type="radio" value="male">Male
	      <input name="gender" type="radio" value="female">Female<br />

    <input type="button" id="submitFormData" onclick="SubmitFormData();" value="Submit" />
   </form>
   <br/>
   Your data will display below..... <br />
   ==============================<br />
   <div id="results">
   <!-- All data will display here  -->
   </div>
</body>
</html>