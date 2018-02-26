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
    this.playSound = function(){ var audio = new Audio('https://instafxng.com/sounds/plucky.mp3');  audio.play();};
    this.new_notification_alert = function()
    {
        var slides = document.getElementById("list_content").getElementsByClassName("alert-success");
        for(var i = 0; i < slides.length; i++)
        {
            this.playSound();
        }
    };
    this.show_alert = function(title, created, author, message, notification_id)
    {
        //console.log('hello');
        var message2 = message.replace(/<br\s*[\/]?>/gi, "\n");
        alert('Title:'+title+'\n'+'Created: '+created+'\n'+'Author: '+author+'\n'+ message2);
        //this.dismiss_notification(notification_id);
    };
}
var push_notifications = new Push_Notifications();



