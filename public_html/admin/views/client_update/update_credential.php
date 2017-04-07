<h5>Verification Documents</h5>
<p>You can update the documents uploaded by the client below. Only update an empty or invalid document.</p>

<!-- Display Documents -->
<div class="row">
    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
        <p>Identity Card</p>
        <?php if(!empty($selected_user_docs['idcard'])) { ?>
            <a href="../userfiles/<?php echo $selected_user_docs['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" width="180px" height="180px"/></a>
        <?php } else { ?>
            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
        <?php } ?>
        <p><a href="" data-toggle="modal" data-target="#myModalCard" class="btn btn-default" style="margin-top: 2px !important">View</a></p>
    </div>
    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
        <span>Passport</span><br />
        <?php if(!empty($selected_user_docs['passport'])) { ?>
            <a href="../userfiles/<?php echo $selected_user_docs['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" width="180px" height="180px"/></a>
        <?php } else { ?>
            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
        <?php } ?>
        <p><a href="" data-toggle="modal" data-target="#myModalPass" class="btn btn-default" style="margin-top: 2px !important">View</a></p>
    </div>
    <div class="col-sm-4 text-center" style="margin-bottom: 4px !important">
        <span>Signature</span><br />
        <?php if(!empty($selected_user_docs['signature'])) { ?>
            <a href="../userfiles/<?php echo $selected_user_docs['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" width="180px" height="180px"/></a>
        <?php } else { ?>
            <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
        <?php } ?>
        <p><a href="" data-toggle="modal" data-target="#myModalSign" class="btn btn-default" style="margin-top: 2px !important">View</a></p>
    </div>
</div>
<hr />
<p>Click the browse button to update any of the verification documents. Please note that you cannot undo any change
you make.</p>
<!-- Form for document upload --->
<form data-toggle="validator" role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
    <input type="hidden" name="user_no" value="<?php if(isset($user_code)) { echo encrypt($user_code); } ?>" />

    <div class="form-group">
        <label class="control-label">ID (National ID Card, Driver's license, International Passport or Recognized ID Card):</label>
        <input type="file" name="pictures_id_card" class="file file-loading" data-allowed-file-extensions='["jpg", "gif", "png"]'>
    </div>

    <div class="form-group">
        <label class="control-label">Passport Photograph:</label>
        <input type="file" name="pictures_passport" class="file file-loading" data-allowed-file-extensions='["jpg", "gif", "png"]'>
    </div>

    <div class="form-group">
        <label class="control-label">Signature:</label>
        <input type="file" name="pictures_signature" class="file file-loading" data-allowed-file-extensions='["jpg", "gif", "png"]'>
    </div>

    <div class="form-group">
        <button type="button" data-target="#document-update" data-toggle="modal" class="btn btn-success">Save Document Update</button>
    </div>

    <!--Modal - confirmation boxes-->
    <div id="document-update" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true"
                            class="close">&times;</button>
                    <h4 class="modal-title">Update Client Crendential</h4></div>
                <div class="modal-body">Are you sure you want to make this update? This action cannot be reversed.</div>
                <div class="modal-footer">
                    <input name="document_update" type="submit" class="btn btn-success" value="Proceed">
                    <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- Modal -->
<div id="myModalPass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <?php if(!empty($selected_user_docs['passport'])) { ?>
                    <a href="../userfiles/<?php echo $selected_user_docs['passport']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['passport']; ?>" class="img-responsive center-block"/></a>
                <?php } else { ?>
                    <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="myModalCard" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php if(!empty($selected_user_docs['idcard'])) { ?>
                    <a href="../userfiles/<?php echo $selected_user_docs['idcard']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['idcard']; ?>" class="img-responsive center-block"/></a>
                <?php } else { ?>
                    <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="myModalSign" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php if(!empty($selected_user_docs['signature'])) { ?>
                    <a href="../userfiles/<?php echo $selected_user_docs['signature']; ?>" title="click to enlarge" target="_blank"><img src="../userfiles/<?php echo $client_credential['signature']; ?>" class="img-responsive center-block"/></a>
                <?php } else { ?>
                    <img src="../images/placeholder.jpg" alt="" class="img-responsive center-block">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" title="Close">Close</button>
            </div>
        </div>
    </div>
</div>