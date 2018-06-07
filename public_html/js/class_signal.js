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
        console.log(query);
        var type = "1";
        this.ajax_request(id,query, type);
    };

    this.getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    this.ajax_request = function (response_div, query, type) {
        var XMLHttpRequestObject = false;
        if (window.XMLHttpRequest) {XMLHttpRequestObject = new XMLHttpRequest();}
        else if (window.ActiveXObject) {XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");}
        if(XMLHttpRequestObject)
        {
            XMLHttpRequestObject.open('POST', "getSignalData.php");
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.setRequestHeader('charset','UTF-8');
            XMLHttpRequestObject.send("query="+encodeURI(query)+"&type="+type);
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

    this.getSmallTrend = function (id) {
        var x;
        switch (id)
        {
            case '1' : x = '<b class="text-success"><i class="glyphicon glyphicon-arrow-up"></i></b>'; break;
            case '2' : x = '<b class="text-danger"><i class="glyphicon glyphicon-arrow-down"></i></b>'; break;
            default : x = 'UNKNOWN'; break;
        }
        return x;
    };

    this.getBigTrend = function (id) {
        var x;
        switch (id)
        {
            case '1' : x = "<b style='color: green!important; font-size: 150px'><i class='glyphicon glyphicon-arrow-up'></i></b>"; break;
            case '2' : x = "<b style='color: red!important; font-size: 150px'><i class='glyphicon glyphicon-arrow-down'></i></b>"; break;
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
        var sign = diff > 0 ? 1 : diff == 0 ? 0 : -1;
        if(sign === -1)
        {
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
        //localStorage.setItem("signal_array", JSON.stringify(json));
        if(json.length < 1)
        {
            //console.log(json.length);
            table.setAttribute("align", 'center');
            var row_ = table.insertRow(0);
            row_.setAttribute("align", 'center');
            row_.innerHTML = '<span class="text-center text-danger" style="font-size:15px;margin-left:50px"><i>No  signals  at  the  Moment</i></span>';
        }
        else {
            var signal_array = JSON.parse(json);
            for(var x in signal_array)
            {
            var row = table.insertRow(0);
            row.setAttribute("class", this.get_Context(signal_array[x]['trigger_time']));
            row.setAttribute("data-toggle", 'modal');
            row.setAttribute("title", 'Click Here For More Details');
            row.setAttribute("id", signal_array[x]['signal_id']);
            row.setAttribute("data-target", '#signal_display');
            var id_ = signal_array[x]['signal_id'];
            row.setAttribute("onclick", 'signal.getSignal('+id_+')');
            //row.addEventListener("click", signal.getSignal(row.id));
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);

            cell1.innerHTML = signal_array[x]['currency_pair'];
            cell3.innerHTML = this.get_OrderType(signal_array[x]['order_type']);
            cell4.innerHTML = this.getSmallTrend(signal_array[x]['order_type']);
            cell5.innerHTML = this.formatTime(signal_array[x]['trigger_time']);
            }
        }
    };

    this.getSignal = function (id)
    {
        document.getElementById('preloader').style.display = 'block';
        var query = "SELECT order_type, price, take_profit, stop_loss, CONCAT(trigger_date, SPACE(1), trigger_time) AS triger, trend, note, signal_symbol.symbol AS currency_pair FROM signal_daily, signal_symbol WHERE signal_daily.symbol_id = signal_symbol.symbol_id AND signal_id = '"+id+"'";
        var type = "2";
        this.incrementViews(id);
        this.ajax_request(id,query, type);

    };

    this.DisplaySignal = function (json)
    {
        document.getElementById('preloader').style.display = 'none';
        var table = document.getElementById('sig_content');
        table.innerHTML = '';
        var signal_array = JSON.parse(json);
        var order_type  = this.get_OrderType(signal_array[0]['order_type']);
        var content = "<table class='table table-bordered table-responsive'>"+
                        "<tbody>"+
                            "<tr>"+
                                "<td><b>ORDER</b></td><td>"+order_type+"</td>"+
                                "<td rowspan='5'><center>"+this.getBigTrend(signal_array[0]['order_type'])+"</center></td>"+
                            "</tr>"+
                            "<tr><td><b>CURRENCY PAIR</b></td><td>"+signal_array[0]['currency_pair']+"</td></tr>"+
                            "<tr><td><b>PRICE</b></td><td>"+signal_array[0]['price']+"</td></tr>"+
                            "<tr><td><b>TAKE PROFIT</b></td><td>"+signal_array[0]['take_profit']+"</td></tr>"+
                            "<tr><td><b>STOP LOSS</b></td><td>"+signal_array[0]['stop_loss']+"</td></tr>"+
                            "<tr><td><b>TRIGGER DATE & TIME</b></td><td>"+signal_array[0]['triger']+"</td></tr>"+
                            "<tr><td><b>KEYNOTE</b></td><td colspan='2'>"+signal_array[0]['note']+"</td></tr>"+
                            "</tbody></table>";
        var feedback = "<tr><td><b>FEEDBACK</b></td>"+ "<td colspan='2'>"+"<textarea placeholder='Comments (If Any)' rows='2' id='comments' name='comments' class='form-control'></textarea>"+"</td>"+ "</tr>";
        table.innerHTML = content;
    };

    this.incrementViews = function(id)
    {
        var query = "UPDATE signal_daily SET views = CONCAT(views p_l_u_s 1) WHERE signal_id = "+"'"+id+"'";
        this.ajax_request('', query, '3');
    };

    this.refreshList = function()
    {
        document.getElementById('preloader').style.display = 'block';
        document.getElementById('sig').innerHTML = '';
        this.getSignals('sig');
    }
}
var signal = new Signal();