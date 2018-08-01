function Signal()
{
    this.time_window = 60;//60 mins
    //TODO: Ensure this is changed
    //this.BASE_URL = 'http://localhost/instafxngwebsite/public_html/';
    this.BASE_URL = 'https://instafxng.com/';
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
        if(window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();}
        else { xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
        xmlhttp.onreadystatechange = function(){
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                if(call_back_func){
                    if(xmlhttp.responseText) {
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
        this.get_sidebar_signal();
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
    };

    ///fine
    this.update_signal_page = function(update_msg){
        if(update_msg == 'new-signals-found'){
            document.getElementById('page_reloader').style.display = 'block';
        }
    };


    this.showQuotes = function(json)
    {
        var quotes_array = JSON.parse(json);
        for(var x in quotes_array){
            document.getElementById('signal_currency_diff_'+quotes_array[x]['symbol']).innerHTML = quotes_array[x]['price'];
            document.getElementById('signal_pl_'+quotes_array[x]['symbol']).innerHTML = quotes_array[x]['pl'];
        }
        //setInterval(function(){signal.getQuotes();}, 60000);//TODO: Fix this back to 5000
    };

    this.getQuotes = function ()
    {
        var url = "getQuotesData.php";
        this.ajax_call(url,'GET','showQuotes');

    };


    this.show_sidebar_signal = function (signals)
    {
        if(signals != 'new-signals-found'){

            document.getElementById('sig').innerHTML = signals;

        }

    };

    this.get_sidebar_signal = function ()
    {
        var url = this.BASE_URL+"views/signal_management/signal_server.php?method_name=UI_get_signals_for_sidebar";
        this.ajax_call(url,'GET','show_sidebar_signal');

    };

}
var signal = new Signal();