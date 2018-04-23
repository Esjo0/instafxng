function SubmitFormData() {
    var admin = $("#admin").val();
    var trans_id = $("#trans_id").val();
    var comment = $("#comment").val();
    if(comment == ""){
        alert ("The Comment Box is Empty!!!");
    }else{
    $.post("operations_comment.php", { admin: admin, trans_id: trans_id, comment: comment, },
        function(data) {
            $('#results').html(data);
            $('#myForm')[0].reset();
        });
    }
}