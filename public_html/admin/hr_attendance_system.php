<script>
    function todays_date()
    {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd = '0'+dd
        }

        if(mm < 10)
        {
            mm = '0'+mm
        }

        today = dd + '-' + mm + '-' + yyyy;
        return today;
    }
    function current_time()
    {
        var date = new Date();
        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + ":" + seconds;

        //var currentdate = new Date();
        //time = currentdate.getHours() + ":"+ currentdate.getMinutes() + ":"+ currentdate.getSeconds();
        return time;
    }
    function initMap()
    {
        var triangleCoords = [
            {lat: 6.4752, lng: 3.5851},
            {lat: 6.4746, lng: 3.5852},
            {lat: 6.4747, lng: 3.5856},
            {lat: 6.4753, lng: 3.5856}
        ];
        var bermudaTriangle = new google.maps.Polygon({paths: triangleCoords});
        navigator.geolocation.getCurrentPosition(showPosition);
        function showPosition(position)
        {
            //parseFloat(position.coords.longitude).toFixed(4);
            var my_location = new google.maps.LatLng(parseFloat(position.coords.latitude).toFixed(4), parseFloat(position.coords.longitude).toFixed(4));
            if(google.maps.geometry.poly.containsLocation(my_location, bermudaTriangle))
            {
                var XMLHttpRequestObject = false;

                if (window.XMLHttpRequest)
                {
                    XMLHttpRequestObject = new XMLHttpRequest();
                }
                else if (window.ActiveXObject)
                {
                    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
                }
                if(XMLHttpRequestObject)
                {
                    XMLHttpRequestObject.open("POST", "hr_attendance_system_sign_in.php");
                    XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                    XMLHttpRequestObject.onreadystatechange = function()
                    {
                        if (XMLHttpRequestObject.readyState == 4 &&
                            XMLHttpRequestObject.status == 200)
                        {
                            var returnedData = XMLHttpRequestObject.responseText;
                            if(returnedData != '')
                            {
                                window.alert(returnedData);
                            }
                        }
                    }
                    var today = todays_date();
                    var time = current_time();

                    var weekday = new Array(7);
                    weekday[0] =  "Sunday";
                    weekday[1] = "Monday";
                    weekday[2] = "Tuesday";
                    weekday[3] = "Wednesday";
                    weekday[4] = "Thursday";
                    weekday[5] = "Friday";
                    weekday[6] = "Saturday";
                    var d = new Date();
                    var n = weekday[d.getDay()];
                    if(n != 'Saturday' || n != 'Sunday')
                    {
                        XMLHttpRequestObject.send("date=" + today + "&time=" + time + "&location=1");
                    }
                }
            }
            else
            {
                window.alert('You are not in the office.');
            }
        }
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwhMeJ-S7S_1v05NBme7IWA8lsX0zfJNQ&libraries=geometry&callback=initMap" async defer></script>

