function Requisition_System()
{
    this.naira_sign = "&#8358;";

    this.getRandomInt = function (min, max)
    {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    this.RandomString = function (strlen)
    {
        const chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        var result = "";
        for (var i=0; i<strlen; i++)
        {
            result += chars[this.getRandomInt(0,35)];
        }
        return result;
    };

    this.AddToList = function()
    {
        var item_code = this.RandomString(5);
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
        cell3.innerHTML = this.naira_sign+" "+unit_cost+' each';
        var del = '"';
        cell4.innerHTML = this.naira_sign+" "+item_total+"<p class='pull-right'><button onclick='acc_system.delete_row("+del+item_code+del+")' style='width: 30px; height: 30px; text-align: center; padding: 6px 0; font-size: 12px; line-height: 1.428571429; border-radius: 15px;' class='btn btn-circle btn-sm btn-danger'><i class='glyphicon glyphicon-remove'></i></button></p>";
        var sum = parseInt(order_total) + parseInt(item_total);
        document.getElementById('requisition_total').innerHTML = sum;
        document.getElementById('requisition_form').reset();
    };

    this.get_total =  function(no_of_items, unit_price, item_total_price)
    {
        var no_of_items_ = document.getElementById(no_of_items).value;
        var unit_price_ = document.getElementById(unit_price).value;
        var item_total = document.getElementById(item_total_price);
        item_total.value = unit_price_ * no_of_items_;
    };

    this.delete_row = function (rowid)
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
    };

    this.select_row = function (rowid, trigger)
    {
        divElement = document.getElementById(rowid);

        if(document.getElementById(trigger).checked)
        {
            inputElements = divElement.getElementsByTagName('input');
            for (i = 0; i < inputElements.length; i++)
            {
                if (inputElements[i].type != 'number')
                    continue;
                inputElements[i].disabled = false;
            }
        }
        else
        {
            inputElements = divElement.getElementsByTagName('input');
            for (i = 0; i < inputElements.length; i++)
            {
                if (inputElements[i].type != 'number')
                    continue;
                inputElements[i].disabled = true;
            }
        }

    };

    this.ajax_request = function (response_div, query, type)
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
            XMLHttpRequestObject.open('POST', "accounting_system_server.php");
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

    this.clear_buttons = function(list){ for (var i = list.length, li; li = list[--i];){ li.parentNode.removeChild(li); } };

    this.send_order_new_function = function (locations, admin_code, req_order_total)
    {
        //var req_order = document.getElementById('requisitionOrder').innerHTML;
        var radios = document.getElementsByName(locations);
        //Office location info & processing
        for (var i = 0, length = radios.length; i < length; i++){ if (radios[i].checked){ var location = radios[i].value; break; }}

        //Remove bottons from order list
        this.clear_buttons(document.getElementById("order_list").getElementsByTagName("button"));

        //Generate an order code
        var req_order_code = this.RandomString(7);

        //req_order_total = this.moneyformat(req_order_total, 1);

        //Create an order ticket
        var query = "INSERT INTO accounting_system_req_order (author_code, req_order_code, req_order_total, location) VALUES ('"+admin_code+"', '"+req_order_code+"', '"+req_order_total+"', '"+location+"')*";

        //Add order list
        var rows = document.getElementById('requisitionOrder').getElementsByTagName("tr");
        for (var x = 0; x < rows.length; x++)
        {
            var Cells = rows[x].getElementsByTagName("td");
            var item_desc = Cells[0].innerText;
            var no_of_items = Cells[1].innerText;
            var unit_cost = Cells[2].innerText;
            unit_cost = unit_cost.match(/\d+/g)[0];
            var item_total = Cells[3].innerText;
            item_total = item_total.match(/\d+/g)[0];
            query += "INSERT INTO accounting_system_req_item (order_code, item_desc, no_of_items, unit_cost, total_cost) VALUES ('"+req_order_code+"', '"+item_desc+"', '"+no_of_items+"', '"+unit_cost+"' , '"+item_total+"')*";
        }
        this.ajax_request('messageDiv', query, '1');

    };

    this.btn_disable = function(action, id)
    {
        if(action === 'enable'){document.getElementById(id).className.replace(/\bdisabled\b/g, "");}
        if(action === 'disable')
        {
            document.getElementById(id).className.split(" ");
            document.getElementById(id).className += "  "+"disabled";
        }

    };

    this.new_order_list = function()
    {
        document.getElementById('requisitionOrder').innerHTML = "";
        this.btn_disable('enable', 's_o_btn');
        document.getElementById('location_list').reset();
        document.getElementById('requisition_total').innerHTML = "0";
        document.getElementById('alert_dismiss').click();
    };

    this.approved_total = function(tableID, orderTotalID)
    {
        var rows = document.getElementById(tableID).getElementsByTagName("tr");
        var all = [0, 0];
        for (var x = 0; x < rows.length; x++)
        {
            var Cells = rows[x].getElementsByTagName("td");
            var item_sum = Cells[7].getElementsByTagName("input");
            console.log(item_sum);
            if(!item_sum[0].disabled)
            {
                console.log(all.length);
                console.log(item_sum[0].value);
                //all[all.length] = item_sum[0].value;
            }
        }
        console.log(all);
        //document.getElementById(orderTotalID).innerHTML = sum;
    };

    this.setAllCheckboxes = function(divId, sourceCheckbox, trigger)
    {
        if(document.getElementById(trigger).checked)
        {
            divElement = document.getElementById(divId);
            inputElements = divElement.getElementsByTagName('input');
            for (i = 0; i < inputElements.length; i++)
            {
                if (inputElements[i].type == 'number'){ inputElements[i].disabled = false; }
                if (inputElements[i].type != 'checkbox'){ continue; }
                inputElements[i].checked = sourceCheckbox.checked;


            }
        }
        else
        {
            divElement = document.getElementById(divId);
            inputElements = divElement.getElementsByTagName('input');
            for (i = 0; i < inputElements.length; i++)
            {
                if (inputElements[i].type == 'number'){ inputElements[i].disabled = true; }
                if (inputElements[i].type != 'checkbox'){ continue; }
                inputElements[i].checked = sourceCheckbox.checked;


            }
        }
    }

}
var acc_system = new Requisition_System();

function print_report(divName)
{
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

function validate_pre_approval(divId, btn, commentBox)
{
    divElement = document.getElementById(divId);
    checkboxes = divElement.getElementsByTagName("input");
    var okay = false;
    for(var i = 0, l = checkboxes.length; i < l; i++)
    {
        if (checkboxes[i].type == 'checkbox' && checkboxes[i].checked)
        {
            okay = true;
            break;
        }
    }
    if(okay)
    {
        if(document.getElementById(commentBox).value == "")
        {
            alert("Please add a remark.");
            return;
        }
        document.getElementById(btn).click();
    }
    else alert("Please select an item.");
};



