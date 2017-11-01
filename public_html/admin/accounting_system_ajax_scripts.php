<script type="text/javascript">
    var XMLHttpRequestObject = false;

    if (window.XMLHttpRequest)
    {
        XMLHttpRequestObject = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    function getRandomInt(min, max)
    {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    function RandomString(strlen)
    {
        const chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        var result = "";
        for (var i=0; i<strlen; i++) {
            result += chars[getRandomInt(0,35)];
        }
        return result;
    }
    function AddtoList()
    {
        var item_code = RandomString(5);
        var item_name = document.getElementById('item_name').value; //Item Description
        var item_total = document.getElementById('item_total_price').value; //Item Cost
        var no_of_items = document.getElementById('no_of_items').value; //Item Description
        var unit_cost = document.getElementById('unit_price').value; //Item Cost
        if( !item_name || !item_total || isNaN(item_total))
        {
            var message_error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Oops!</strong> Both fields are compulsry, please try again.</div>';
            var messageDiv = document.getElementById('messageDiv');
            messageDiv.innerHTML = message_error;
            return;
        }
        var order_total = document.getElementById('requisition_total').innerHTML; //Order Total



        var table = document.getElementById('requisitionOrder');
        var row = table.insertRow(0);
        row.id = item_code;
        //row.setAttribute("id", , 0);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        cell1.innerHTML = item_name;
        cell2.innerHTML = no_of_items;
        cell3.innerHTML = unit_cost;
        var del = '"';
        cell4.innerHTML = '₦'+item_total+"<p class='pull-right'><button onclick='delete_row("+del+item_code+del+")' style='width: 30px; height: 30px; text-align: center; padding: 6px 0; font-size: 12px; line-height: 1.428571429; border-radius: 15px;' class='btn btn-circle btn-sm btn-danger'><i class='glyphicon glyphicon-remove'></i></button></p>";
        var sum = parseInt(order_total) + parseInt(item_total);
        document.getElementById('requisition_total').innerHTML = sum;
        document.getElementById('requisition_form').reset();
    }
    function get_total()
    {
        var no_of_items = document.getElementById('no_of_items').value;
        var unit_price = document.getElementById('unit_price').value;
        var item_total = document.getElementById('item_total_price');
        item_total.value = unit_price * no_of_items;
    }
    function delete_row(rowid)
    {
        var Row = document.getElementById(rowid);
        var Cells = Row.getElementsByTagName("td");
        var item_total = Cells[3].innerText;
        item_total = item_total.match(/\d+$/)[0];
        var order_total = document.getElementById('requisition_total').innerHTML;
        var sum = parseInt(order_total) - parseInt(item_total);
        document.getElementById('requisition_total').innerHTML = sum;

        var table = document.getElementById("requisitionOrder");
        var rowIndex = document.getElementById(rowid).rowIndex;
        rowIndex = rowIndex - 1;
        table.deleteRow(rowIndex);
    }
    function search()
    {

        if(XMLHttpRequestObject)
        {
            XMLHttpRequestObject.open("POST", "accounting_system_cashiers_desk_server.php");
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.onreadystatechange = function()
            {
                if (XMLHttpRequestObject.readyState == 4 &&
                    XMLHttpRequestObject.status == 200)
                {
                    var returnedData = XMLHttpRequestObject.responseText;
                    var messageDiv = document.getElementById('messageDiv');
                    messageDiv.innerHTML = returnedData;
                }
            };
            var cash_out_code = document.getElementById('cash_out_code').value;

            XMLHttpRequestObject.send("cash_out_code=" + cash_out_code);

        }

        return false;
    }
    function send_order()
    {

        if(XMLHttpRequestObject)
        {
            XMLHttpRequestObject.open("POST", "accounting_system_req_order_server.php");
            XMLHttpRequestObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            XMLHttpRequestObject.onreadystatechange = function()
            {
                if (XMLHttpRequestObject.readyState == 4 &&
                    XMLHttpRequestObject.status == 200)
                {
                    var returnedData = XMLHttpRequestObject.responseText;
                    var messageDiv = document.getElementById('messageDiv');
                    messageDiv.innerHTML = returnedData;
                }
            };
            //var location = document.getElementById('location').value;
            var radios = document.getElementsByName('office_location');

            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    var location = radios[i].value
                        //alert(radios[i].value);

                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }

            clear_buttons(document.getElementById("order_list").getElementsByTagName("button"));
            var req_order = document.getElementById('order_list').innerHTML;
            var req_order_total = document.getElementById('requisition_total').innerHTML;
            var req_order_code = RandomString(7);
            XMLHttpRequestObject.send("req_order=" + req_order + "&req_order_code=" + req_order_code + "&req_order_total=" + req_order_total + "&location=" + location);
            //location.reload();
        }

        return false;
    }
    function clear_buttons(list)
    {
        for (var i = list.length, li; li = list[--i];)
            li.parentNode.removeChild(li);
    }

</script>