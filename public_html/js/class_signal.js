function Signal()
{
    this.time_window = 60;//60 mins

    this.formatDate = function (date){
        var monthNames = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();
        return day + ' ' + monthNames[monthIndex] + ' ' + year;
    };

    this.formatTime = function (time){
        time = time.split(":");
        var hours = parseInt(time[0]);
        var minutes = parseInt(time[1]);
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        return hours + ':' + minutes + ' ' + ampm;
    };

    this.getSignals = function (id){
        var d = new Date();
        var date = d.getFullYear()+'-'+(d.getMonth() + 1)+'-'+d.getDate();
        var query = "SELECT signal_id, order_type, price, take_profit, stop_loss, CONCAT(trigger_date, SPACE(1), trigger_time) AS triger, trigger_time, trend, note, signal_symbol.symbol AS currency_pair FROM signal_daily, signal_symbol WHERE signal_daily.symbol_id = signal_symbol.symbol_id AND trigger_date = '"+date+"' ORDER BY triger ASC";
        var type = "1";
        this.ajax_request(id,query, type);
    };

    this.getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    this.ajax_request = function (response_div, query, type) {
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
            XMLHttpRequestObject.open('POST', "getSignalData.php");
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.send("query="+query+"&type="+type);
            XMLHttpRequestObject.onreadystatechange = function()
            {
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
                {
                    //document.getElementById(response_div).innerHTML = XMLHttpRequestObject.responseText;
                    //return XMLHttpRequestObject.responseText;
                    var json = XMLHttpRequestObject.responseText;
                    if(type == '1') { signal.showSignal(json, response_div);}
                    if(type == '2') { signal.DisplaySignal(json);}
                }
            };

        }
        else {   return false;    }
    };

    this.get_date = function (id) {
        document.getElementById(id).innerHTML = this.formatDate(new Date());
    };

    this.get_OrderType = function (id) {
        var x;
        switch (id)
        {
            case '1' : x = 'BUY'; break;
            case '2' : x = 'SELL'; break;
            default : x = 'UNKNOWN';
        }
        return x;
    };

    this.get_Trend = function (id) {
        var x;
        switch (id)
        {
            case '0' : x = '<b class="text-danger"><i class="glyphicon glyphicon-arrow-down"></i></b>'; break;
            case '1' : x = '<b class="text-success"><i class="glyphicon glyphicon-arrow-up"></i></b>'; break;
            default : x = 'UNKNOWN'; break;
        }
        return x;
    };

    this.get_Context = function (time){
        var now = new Date;
        var time1 = time.split(":");
        var x = parseInt(time1[0]);
        var y = parseInt(time1[1]);
        var z = parseInt(time1[2]);
        var a = now.getHours();
        var b = now.getMinutes();
        var c = now.getSeconds();
        var hours = x - a;
        var minutes = y - b;
        var seconds = z - c;
        var diff = (hours * 60) + minutes;
        //console.log(time + '   ->   ' + diff);
        var sign = diff > 0 ? 1 : diff == 0 ? 0 : -1;
        if(sign === -1)
        {
            //console.log(diff = -diff);
            if((- diff) <= this.time_window){ return 'table-warning';}
            else {return 'table-danger';}
        }
        else { return 'table-success';}
    };

    this.get_time = function (id){
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();
        if (minutes < 10){minutes = "0" + minutes}
        if (seconds < 10){seconds = "0" + seconds}
        var t_str = hours + ":" + minutes + ":" + seconds + " ";
        if(hours > 11){t_str += "PM";} else {t_str += "AM";}
        document.getElementById(id).innerHTML = t_str;
    };

    this.showSignal = function (json, id){
        var table = document.getElementById(id);
        table.innerHTML = '';
        var signal_array = JSON.parse(json);
        //localStorage.setItem("signal_array", JSON.stringify(json));
        for(var x in signal_array)
        {
            var row = table.insertRow(0);
            row.classList += this.get_Context(signal_array[x]['trigger_time']);
            row.setAttribute("data-toggle", 'modal');
            row.setAttribute("id", signal_array[x]['signal_id']);
            row.setAttribute("data-target", '#signal_display');
            var id_ = signal_array[x]['signal_id'];
            row.addEventListener("click", signal.getSignal(id_));
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);

            cell1.innerHTML = signal_array[x]['currency_pair'];
            cell3.innerHTML = this.get_OrderType(signal_array[x]['order_type']);
            cell4.innerHTML = this.get_Trend(signal_array[x]['trend']);
            cell5.innerHTML = this.formatTime(signal_array[x]['trigger_time']);
        }
    };

    this.getSignal = function (id)
    {
        document.getElementById('preloader').style.display = 'block';
        var query = "SELECT order_type, price, take_profit, stop_loss, CONCAT(trigger_date, SPACE(1), trigger_time) AS triger, trend, note, signal_symbol.symbol AS currency_pair FROM signal_daily, signal_symbol WHERE signal_daily.symbol_id = signal_symbol.symbol_id AND signal_id = '"+id+"'";
        var type = "2";
        this.ajax_request(id,query, type);

    };
    this.DisplaySignal = function (json)
    {
        document.getElementById('preloader').style.display = 'none';
        var table = document.getElementById('sig_content');
        table.innerHTML = '';
        console.log(json);
        var signal_array = JSON.parse(json);
        var order_type  = this.get_OrderType(signal_array[0]['order_type']);
        var content = "<table class='table table-bordered table-responsive'>"+
                        "<tbody>"+
                            "<tr>"+
                                "<td><b>ORDER</b></td><td>"+order_type+"</td>"+
                                "<td rowspan='5'>"+
                                        "<center><b style='color: green!important; font-size: 150px'><i class='glyphicon glyphicon-arrow-up'></i></b></center>"+
                                    "</td>"+
                            "</tr>"+
                            "<tr><td><b>PRICE</b></td><td>"+signal_array[0]['order_type']+"</td></tr>"+
                            "<tr><td><b>TAKE PROFIT</b></td><td>"+signal_array[0]['take_profit']+"</td></tr>"+
                            "<tr><td><b>STOP LOSS</b></td><td>"+signal_array[0]['stop_loss']+"</td></tr>"+
                            "<tr><td><b>TRIGGER DATE & TIME</b></td><td>"+signal_array[0]['triger']+"</td></tr>"+
                            "<tr><td><b>KEYNOTE</b></td><td colspan='2'>"+signal_array[0]['note']+"</td></tr>"+
                            "<tr><td><b>FEEDBACK</b></td>"+
                                "<td colspan='2'>"+
                                    "<input id='input-1' name='rating' class='rating rating-sm rating-loading rating-sm' data-min='0' data-max='5' data-step='1' required>"+
                                    "<br/>"+
                                    "<textarea placeholder='Comments (If Any)' rows='2' id='comments' name='comments' class='form-control'></textarea>"+
                                "</td>"+
                            "</tr></tbody></table>";
        table.innerHTML = content;
    };

}
var signal = new Signal();