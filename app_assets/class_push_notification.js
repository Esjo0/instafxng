function Push_Notifications()
{
    this.count_notifications = function ()
    {
        var rows = document.getElementById("list_content").getElementsByTagName("div").length;
        if(rows > 0)
        {
            document.getElementById("counter").innerHTML = rows;
        }
        if(rows <= 0)
        {
            document.getElementById("counter").innerHTML = "";
            document.getElementById("list_content").innerHTML = "<center><img class='img-responsive' src='../images/notification_empty.jpg'></center>";
        }
    };

    this.load_last_notification = function ()
    {
        this.count_notifications();
        this.ajax_request('list_content', '', '1');
        this.ajax_request('content', '', '2');
        this.count_notifications();
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
            XMLHttpRequestObject.send("query="+query+"&type="+type);
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
        var type = '0';
        XMLHttpRequestObject.send("type=" + type + "&notification_id=" + notification_id);
        count_notifications();
    };
}
var push_notifications = new Push_Notifications();



