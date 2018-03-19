function Push_Notifications()
{
    this.count_notifications = function ()
    {
        var rows = document.getElementById("list_content").getElementsByTagName("div").length;
        if(rows > 0)
        {
            document.getElementById("counter").innerHTML = rows;
            document.getElementById("clear_btn").style.display = 'block'
        }
        if(rows <= 0)
        {
            document.getElementById("counter").innerHTML = "";
            document.getElementById("clear_btn").style.display = 'none';
            document.getElementById("list_content").innerHTML = "<center><img class='img-responsive' src='../images/notification_empty.jpg'></center>";
        }
    };
    this.load_last_notification = function ()
    {
        this.count_notifications();
        this.ajax_request('list_content', '', '1');
        this.count_notifications();
        this.new_notification_alert();
    };
    this.ajax_request = function(response_div, query, type)
    {
        var XMLHttpRequestObject = false;
        if (window.XMLHttpRequest) {XMLHttpRequestObject = new XMLHttpRequest();}
        else if (window.ActiveXObject){XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");}
        if(XMLHttpRequestObject)
        {
            XMLHttpRequestObject.open('POST', "push_notification_server.php");
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.send(query+"&type="+type);
            XMLHttpRequestObject.onreadystatechange = function()
            {
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
                {
                    document.getElementById(response_div).innerHTML = XMLHttpRequestObject.responseText;
                }
            };
        }
        else {   return false;    }
    };
    this.dismiss_notification = function(notification_id)
    {
        this.ajax_request('list_content', 'id='+notification_id, '0');
        document.getElementById('view_'+notification_id).style.display = 'none';
    };
    this.dismiss_all_notification = function()
    {
        this.ajax_request('list_content', 'id=all', '00');
        //document.getElementById('view_'+notification_id).style.display = 'none';
    };
    this.playSound = function(){ var audio = new Audio('https://instafxng.com/sounds/plucky.mp3');  audio.play();};
    this.new_notification_alert = function()
    {
        var slides = document.getElementById("list_content").getElementsByClassName("alert-success");
        for(var i = 0; i < slides.length; i++)
        {
            this.playSound();
        }
    };
    this.show_alert = function(title, created, author, message, source_url,notification_id)
    {
        document.getElementById('list_expand').innerHTML = "";
        //console.log(title+created+author+message+notification_id);

        var modal = '<div id="modal_show" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">'+
                        '<div class="modal-dialog">'+
                            '<div class="modal-content">'+
                                '<div class="modal-header">'+
                                    '<button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>'+
                                    '<h4 class="modal-title">Notification Details</h4>'+
                               '</div>'+
                                '<div class="modal-body">' +
            '<b>Title</b>: '+title+'<br/>'+'<b>Created: </b> '+created+'<br/>'+'<b>Author: </b> '+author+'<br/>'+ '<b>Message: </b>'+message+ '<br/><a href="'+source_url+'" target="_blank">'+'Click Here'+'</a>'+
            '</div>'+
                                '<div class="modal-footer">'+
                                    '<button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>'+
                                '</div></div></div></div>';
        document.getElementById('list_expand').innerHTML = modal;
        $(document).ready(function()
        {
            $('#modal_show').modal("show");
        });
        this.dismiss_notification(notification_id);
    };
}
var push_notifications = new Push_Notifications();



