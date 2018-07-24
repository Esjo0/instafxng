function Signal()
{
    this.time_window = 60;//60 mins
    //TODO: Ensure this is changed
    this.BASE_URL = 'http://localhost/instafxngwebsite/public_html/';
    //this.SIGNAL_FILE_URL = 'http://localhost/instafxngwebsite/models/signal_daily.json';
    this.SIGNAL_FILE_URL = '../../../models/signal_daily.json';

    this.formatDate = function (date, format){
        if(format == 'simple'){
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            return year+'-'+month + '-'+day;
        }else{
            var _monthNames = [
                "January", "February", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ];
            var _day = date.getDate();
            var _monthIndex = date.getMonth();
            var _year = date.getFullYear();
            return _day + ' ' + _monthNames[_monthIndex] + ' ' + _year;
        }
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

    this.stamp_to_time_Converter = function (UNIX_timestamp) {
        var a = new Date(UNIX_timestamp * 1000);
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var hour = a.getHours();
        var min = a.getMinutes();
        var sec = a.getSeconds();
        //return date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
        return hour + ':' + min + ':' + sec ;
    };

    this.get_currency_pair_from_str = function(string){
        str = string.split('');
        str = str[0]+str[1]+str[2]+'/'+str[3]+str[4]+str[5];
        return str.toUpperCase();
    };

    ////remove
    this.get_news = function(currency_pair){
        date = this.formatDate(new Date(), 'simple');
        var url = 'https://newsapi.org/v2/everything?q='+currency_pair+'&from='+date+'&sortBy=popularity&apiKey=f954016b06bd412288ac281bc509a719';

    };

    ////fine
    this.show_extra_analysis = function(div_id){
        signal_div = document.getElementById(div_id);
        signal_main = document.getElementById(div_id+'_main');
        signal_extra = document.getElementById(div_id+'_extra');
        signal_trigger = document.getElementById(div_id+'_trigger');
        if (signal_extra.style.display === 'none'){
            //Make div big
            signal_div.classList = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 card grid-item';
            signal_main.classList = 'col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4';
            signal_trigger.innerHTML = '<b><i class="glyphicon glyphicon-arrow-left"></i> HIDE EXTRA ANALYSIS </b>';
            signal_extra.style.display = 'block';
        } else{
            //Make div small
            signal_div.classList = 'col-xs-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 card grid-item';
            signal_main.classList = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12';
            signal_trigger.innerHTML = '<b>SHOW EXTRA ANALYSIS <i class="glyphicon glyphicon-arrow-right"></i></b>';
            signal_extra.style.display = 'none';
        }
    };

    ///fine
    this.ajax_call = function (url, method,call_back_func) {
        console.log(url);
        if(window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
        else { xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
        xmlhttp.onreadystatechange = function(){
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                if(call_back_func){
                    if(xmlhttp.responseText) {
                        console.log(xmlhttp.responseText);
                        signal[call_back_func](xmlhttp.responseText);
                    }
                }
            }
        };
        xmlhttp.open(method, url, true);
        xmlhttp.send();
    };

    this.get_date = function (id) {
        document.getElementById(id).innerHTML = this.formatDate(new Date());
    };

    this.get_OrderType = function (id) {
        var x;
        switch (id) {
            case '1' : x = 'BUY'; break;
            case '2' : x = 'SELL'; break;
            default : x = 'UNKNOWN';
        }
        return x;
    };

    this.getSmallTrend = function (id) {
        var x;
        switch (id) {
            case '1' : x = '<b class="text-success"><i class="glyphicon glyphicon-arrow-up"></i></b>'; break;
            case '2' : x = '<b class="text-danger"><i class="glyphicon glyphicon-arrow-down"></i></b>'; break;
            default : x = 'UNKNOWN'; break;
        }
        return x;
    };

    this.getBigTrend = function (id) {
        var x;
        switch (id) {
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
        if(sign === -1) {
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
            table.setAttribute("align", 'center');
            var row_ = table.insertRow(0);
            row_.setAttribute("align", 'center');
            row_.innerHTML = '<span class="text-center text-danger" style="font-size:15px;margin-left:50px"><i>No signal at the Moment</i></span>';
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

    this.getSignal = function (id) {
        document.getElementById('preloader').style.display = 'block';
        var query = "SELECT order_type, price, take_profit, stop_loss, CONCAT(trigger_date, SPACE(1), trigger_time) AS triger, trend, note, signal_symbol.symbol AS currency_pair FROM signal_daily, signal_symbol WHERE signal_daily.symbol_id = signal_symbol.symbol_id AND signal_id = '"+id+"'";
        var type = "2";
        this.incrementViews(id);
        this.ajax_request(id,query, type);

    };

    this.DisplaySignal = function (json) {
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
                            "<tr><td><b>PRICE</b></td><td>"+ this.zeroCheck(signal_array[0]['price'])+"</td></tr>"+
                            "<tr><td><b>TAKE PROFIT</b></td><td>"+ this.zeroCheck(signal_array[0]['take_profit'])+"</td></tr>"+
                            "<tr><td><b>STOP LOSS</b></td><td>"+ this.zeroCheck(signal_array[0]['stop_loss'])+"</td></tr>"+
                            "<tr><td><b>TRIGGER DATE & TIME</b></td><td>"+signal_array[0]['triger']+"</td></tr>"+
                            "<tr><td><b>KEYNOTE</b></td><td colspan='2'>"+signal_array[0]['note']+"</td></tr>"+
                            "</tbody></table>";
        var feedback = "<tr><td><b>FEEDBACK</b></td>"+ "<td colspan='2'>"+"<textarea placeholder='Comments (If Any)' rows='2' id='comments' name='comments' class='form-control'></textarea>"+"</td>"+ "</tr>";
        table.innerHTML = content;
    };

    //checks the number of decimal places and ensure its 4 digits after the decimal point.
    this.zeroCheck = function(price) {
        var real = price;
        if (Math.floor(price) !== price){ var num = price.toString().split(".")[1].length || 0; }
        if(num == 3){ real = real + "0";}
        if(num == 2){real = real + "00";}
        if(num == 1){real = real + "000";}
        return real;
    };

    this.incrementViews = function(id){
        var query = "UPDATE signal_daily SET views = CONCAT(views p_l_u_s 1) WHERE signal_id = "+"'"+id+"'";
        this.ajax_request('', query, '3');
    };

    ///recheck
    this.refreshList = function() {
        document.getElementById('preloader').style.display = 'block';
        document.getElementById('sig').innerHTML = '';
        this.getSignals('sig');
    };

    ///fine
    this.new_signal_listener = function(){
        var signal_list = document.getElementsByClassName('main');
        var id_list = [];
        for(var row in signal_list){
            if(signal_list[row]['id']){ id_list.push(signal_list[row]['id']);}
        }
        id_list = id_list.join('-');
        var url = this.BASE_URL+"views/signal_management/signal_server.php?method_name=new_signal_listener&method_args="+id_list;
        this.ajax_call(url, 'GET', 'update_signal_page');
        setInterval(function(){signal.new_signal_listener();}, 60000);//TODO: Fix this back to 5000
        setInterval(function(){signal.getQuotes();}, 600000000000000000000000000000);//TODO: Fix this back to 1000
    };

    ///fine
    this.update_signal_page = function(update_msg){
        if(update_msg == 'new-signals-found'){
            document.getElementById('page_reloader').style.display = 'block';
            document.getElementById('page_reloader_side').style.display = 'block';
        }
        //setTimeout(this.new_signal_listener(), 10000)
    };

    this.ajax_pull = function (type) {
        var url = this.BASE_URL+"views/signal_management/signal_server.php?method_name=get_live_quotes";
        var XMLHttpRequestObject = false;
        if (window.XMLHttpRequest) {XMLHttpRequestObject = new XMLHttpRequest();}
        else if (window.ActiveXObject) {XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");}
        if(XMLHttpRequestObject)
        {
            XMLHttpRequestObject.open('GET', 'getQuotesData.php');
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.setRequestHeader('charset','UTF-8');
            XMLHttpRequestObject.send();
            XMLHttpRequestObject.onreadystatechange = function()
            {
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200)
                {
                    var json = XMLHttpRequestObject.responseText;
                    console.log(json);
                    if(type == '1') { signal.showQuotes(json);}
                }
            };

        }
        else {   return false;    }
    };

    this.showQuotes = function(json)
    {
        var quotes_array = JSON.parse(json);
        for(var x in quotes_array){
            document.getElementById(quotes_array[0]['symbol']).innerHTML = quotes_array[0]['price'];
        }

    };

    this.getQuotes = function ()
    {
        var type = "1";
        this.ajax_pull(type);

    };

}
var signal = new Signal();