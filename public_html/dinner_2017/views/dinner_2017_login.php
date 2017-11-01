<?php
$REQUEST_URI = strtok($_SERVER['REQUEST_URI'], '?');
?>
<p>Update Your Reservation</p>
<ul class="fa-ul">
    <li><i class="fa-li fa fa-check-square-o icon-tune"></i>Enter your email address and click the submit button.</li>
</ul>

<form class="form-horizontal" role="form" method="post" action="<?php echo $REQUEST_URI . '?p=login'; ?>">
    <div class="form-group">
        <label class="control-label col-sm-3" for="email">Email Address:</label>
        <div class="col-sm-9 col-lg-5">
            <input name="email" type="text" class="form-control" id="email">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9"><input name="login" type="submit" class="btn btn-success" value="Submit" /></div>
    </div>
</form>