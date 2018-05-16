<?php
$admin_members = $admin_object->get_all_admin_member();

?>
<h4><strong>MANAGE REPORT SETTINGS</strong></h4>
<div class="row">
    <div class="panel-group" id="accordion">
        <?php foreach ($admin_members as $row) {  extract($row); $reviewers = $obj_rms->get_reviewers($row['admin_code']);?>
            <div class="col-sm-6">
                <!--Panel-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $admin_code;?>">
                                <?php echo $full_name;?>
                            </a>
                        </h5>
                    </div>
                    <div id="collapse_<?php echo $admin_code;?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="table-responsive mtl">
                                <form data-toggle="validator" class="form-horizontal" role="form" method="post" action="">
                                    <input name="admin_code" value="<?php echo $admin_code;?>" type="hidden">
                                    <span class="text-justify">Tick the names of the individuals that would review <?php echo $full_name;?>'s reports.</span>
                                    <div class="col-sm-12">
                                        <?php foreach ($admin_members as $row2) {?>
                                            <!--Reviewer-->
                                            <div class="col-sm-6"><div class="checkbox"><label for=""><input type="checkbox" name="reviewers[]" value="<?php echo $row2['admin_code']?>" <?php if(in_array($row2['admin_code'], $reviewers)) { echo 'checked="checked"'; } ?>/><?php echo $row2['full_name']?></label></div></div>
                                            <!--Reviewer-->
                                        <?php } ?>
                                    </div><br/><br/>
                                    <div class="col-sm-12">
                                        <button name="process" type="submit" class="btn btn-sm btn-success btn-group-justified">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Panel End-->
                <br/>
            </div>
        <?php } ?>

    </div>
</div>
