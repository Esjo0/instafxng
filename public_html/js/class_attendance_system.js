function Attendance_System()
{
    this.sign_in = function(ip)
    {
        if(XMLHttpRequestObject)
        {
            XMLHttpRequestObject.open("POST", "hr_attendance_system.php");
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.onreadystatechange = function()
            {
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
                {
                    document.getElementById('hr_attendance_system').innerHTML = XMLHttpRequestObject.responseText;
                    if(document.getElementById('hr_attendance_system').innerHTML != "")
                    {
                        $(document).ready(function()
                        {
                            $('#confirm-add-admin').modal("show");
                        });
                    }
                }
            };
            XMLHttpRequestObject.send("lan_ip=" + ip);
        }
        return false;
    };

    this.get_lan_ip = function()
    {
        window.RTCPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;   //compatibility for firefox and chrome
        var pc = new RTCPeerConnection({iceServers:[]}), noop = function(){};
        pc.createDataChannel("");    //create a bogus data channel
        pc.createOffer(pc.setLocalDescription.bind(pc), noop);    // create offer and set local description
        pc.onicecandidate = function(ice)
        {  //listen for candidate events
            if(!ice || !ice.candidate || !ice.candidate.candidate)  return;
            var myIP = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/.exec(ice.candidate.candidate)[1];
            sign_in(myIP);
            pc.onicecandidate = noop;
        };
    }
}
var attendance_system = new Attendance_System();



