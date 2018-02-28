/****************************************************************************************
 * LiveZilla ChatReportsClass.js
 *
 * Copyright 2014 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/
function ChatReportsClass() {

}

ChatReportsClass.prototype.createReportList = function() {
    var numberOfPages = Math.max(1, Math.ceil(DataEngine.reports.getMatching() / DataEngine.reports.getReportsPerPage()));
    var page = CommunicationEngine.reportPage;
    var headLine2Html = '<span  class="lzm-dialog-hl2-info">' +
        t('<!--total_reports--> total entries, <!--filtered_reports--> matching filter',
            [['<!--total_reports-->', DataEngine.reports.getTotal()], ['<!--filtered_reports-->', DataEngine.reports.getMatching()]]) +
        '</span><span class="lzm-button-box-right">' +
        lzm_inputControls.createButton('report-filter', '', 'openReportFilterMenu(event)', t('Filter'), '<i class="fa fa-filter"></i>', 'lr',{'margin-right': '4px'}, '', -1,'e') + '</span>';
    var footLineHtml = '<span id="report-paging">';
    var leftDisabled = (page == 1) ? ' ui-disabled' : '', rightDisabled = (page == numberOfPages) ? ' ui-disabled' : '';
    if (!isNaN(numberOfPages)) {
        footLineHtml += lzm_inputControls.createButton('report-page-all-backward', 'report-list-page-button' + leftDisabled, 'pageReportList(1);', '',
            '<i class="fa fa-fast-backward"></i>', 'l', {'border-right-width': '1px'}) +
            lzm_inputControls.createButton('report-page-one-backward', 'report-list-page-button' + leftDisabled, 'pageReportList(' + (page - 1) + ');', '', '<i class="fa fa-backward"></i>', 'r',
                {'border-left-width': '1px'}) +
            '<span style="padding: 0px 15px;">' + t('Page <!--this_page--> of <!--total_pages-->',[['<!--this_page-->', page], ['<!--total_pages-->', numberOfPages]]) + '</span>' +
            lzm_inputControls.createButton('report-page-one-forward', 'report-list-page-button' + rightDisabled, 'pageReportList(' + (page + 1) + ');', '', '<i class="fa fa-forward"></i>', 'l',
                {'border-right-width': '1px'}) +
            lzm_inputControls.createButton('report-page-all-forward', 'report-list-page-button' + rightDisabled, 'pageReportList(' + numberOfPages + ');', '', '<i class="fa fa-fast-forward"></i>', 'r',
                {'border-left-width': '1px'});
    }
    footLineHtml += '</span>';

    $('#report-list-headline').html('<h3>' + t('Reports') + '</h3>');
    $('#report-list-headline2').html(headLine2Html);
    $('#report-list-body').html(this.createReportListHtml());
    $('#report-list-footline').html(footLineHtml);
};

ChatReportsClass.prototype.createReportListHtml = function() {
    var reports = DataEngine.reports.getReportList();
    var selectedReport = (typeof $('#report-list-table').data('selected-report') != 'undefined') ? $('#report-list-table').data('selected-report') : '';
    var bodyHtml = '<table id="report-list-table" class="visible-list-table alternating-rows-table lzm-unselectable" style="width: 100%;"' +
        ' data-selected-report="' + selectedReport + '"><thead>' +
        '<tr><th style="width: 20px !important;"></th><th>' + t('Period') + '</th><th style="width: 150px !important;">' + t('Status (Last Update)') + '</th>' +
        '<th style="width: 150px !important;">' + t('Visitors') + '</th><th style="width: 150px !important;">' + t('Chats') + '</th><th style="width: 150px !important;">' + t('Conversion Rate') + '</th></tr>' +
        '</thead><tbody>';
    for (var i=0; i<reports.length; i++)
    {
        bodyHtml += this.createReportListLine(reports[i]);
    }
    bodyHtml += '</tbody></table>';

    return bodyHtml;
};

ChatReportsClass.prototype.createReportListLine = function(report) {
    var reportImage = (report.r == 'day') ? '<i class="fa fa-pie-chart"></i>' : (report.r == 'month') ? '<i class="fa fa-pie-chart"></i>' : '<i class="fa fa-pie-chart"></i>';
    var updateTimeObject = lzm_chatTimeStamp.getLocalTimeObject(report.t * 1000, true);
    var currentTimeObject = lzm_chatTimeStamp.getLocalTimeObject(null, false);
    var updateTimeHuman = lzm_commonTools.getHumanDate(updateTimeObject, 'time', lzm_chatDisplay.userLanguage);
    var statusLastUpdate = tid('ticket_status_2'), canBeReCalculated = false;
    if (report.a == 0) {
        statusLastUpdate = t('Open (<!--update_time-->)', [['<!--update_time-->', updateTimeHuman]]);
        canBeReCalculated = true;
    }
    var periodHumanDate = (report.r == 'day') ?
        lzm_commonTools.getHumanDate([report.y, report.m, report.d, 0, 0, 0], 'longdate', lzm_chatDisplay.userLanguage) : (report.r == 'month') ? lzm_commonTools.getHumanDate([report.y, report.m, report.d, 0, 0, 0], 'dateyear', lzm_chatDisplay.userLanguage) : report.y;

    var oncontextmenuAction = ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) ? ' oncontextmenu="openReportContextMenu(event, \'' + report.i + '\', ' + canBeReCalculated + ');"' : '';
    var onclickAction = ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) ? ' onclick="selectReport(\'' + report.i + '\');"' : ' onclick="openReportContextMenu(event, \'' + report.i + '\', ' + canBeReCalculated + ');"';

    var ondblclickAction = ((!IFManager.IsAppFrame && !IFManager.IsMobileOS) || IFManager.IsDesktopApp()) ?' ondblclick="loadReport(\'' + report.i + '\', \'report\');"' : '';
    var lineClasses = ($('#report-list-table').data('selected-report') == report.i) ? ' class="report-list-line selected-table-line"' : ' class="report-list-line"';
    var reportListLine = '<tr id="report-list-line-' + report.i + '" style="cursor: pointer;"' + oncontextmenuAction +
        onclickAction + ondblclickAction + lineClasses + '>' +
        '<td style="text-align: center;">' + reportImage + '</td>' +
        '<td>' + periodHumanDate + '</td>' +
        '<td>' + statusLastUpdate + '</td>' +
        '<td>' + report.s + '</td>' +
        '<td>' + report.ch + '</td>' +
        '<td>' + report.c + '%</td>' +
        '</tr>';

    return reportListLine;
};

ChatReportsClass.prototype.createReportListContextMenu = function(myObject) {
    var disabledClass, onclickAction, contextMenuHtml = '';
    disabledClass = '';
    onclickAction = 'loadReport(\'' + myObject.i + '\', \'report\');';
    contextMenuHtml += '<div' + disabledClass + ' onclick="' + onclickAction + 'removeReportContextMenu();">' +
        '<span id="load-this-report" class="cm-line cm-click">' +
        t('Report') + '</span></div>';
    disabledClass = (myObject.r != 'day') ? ' class="ui-disabled"' : '';
    onclickAction = 'loadReport(\'' + myObject.i + '\', \'visitors\');';
    contextMenuHtml += '<div' + disabledClass + ' onclick="' + onclickAction + 'removeReportContextMenu();">' +
        '<span id="load-this-visitors" class="cm-line cm-click">' +
        t('Visitors') + '</span></div><hr />';
    disabledClass = (!myObject.canBeReCalculated) ? ' class="ui-disabled"' : '';
    onclickAction = 'recalculateReport(\'' + myObject.i + '\');';
    contextMenuHtml += '<div' + disabledClass + ' onclick="' + onclickAction + 'removeReportContextMenu();">' +
        '<i class="fa fa-refresh"></i>' +
        '<span id="recalculate-this-report" class="cm-line cm-line-icon-left cm-click">' +
        t('Recalculate') + '</span></div>';

    return contextMenuHtml;
};

ChatReportsClass.prototype.createReportFilterMenu = function(myObject) {
    var myVisibility = (CommunicationEngine.reportFilter == 'day') ? 'visible' : 'hidden', contextMenuHtml = '';
    contextMenuHtml += '<div onclick="toggleReportFilter(\'day\', event)">' +
        '<span id="toggle-filter-day" class="cm-line cm-line-icon-left cm-click">' +
        t('<!--checked--> Day', [['<!--checked-->', '<span style="visibility: ' + myVisibility + ';">&#10003;</span>']]) + '</span></div>';
    myVisibility = (CommunicationEngine.reportFilter == 'month') ? 'visible' : 'hidden';
    contextMenuHtml += '<div onclick="toggleReportFilter(\'month\', event)">' +
        '<span id="toggle-filter-month" class="cm-line cm-line-icon-left cm-click">' +
        t('<!--checked--> Month', [['<!--checked-->', '<span style="visibility: ' + myVisibility + ';">&#10003;</span>']]) + '</span></div>';
    myVisibility = (CommunicationEngine.reportFilter == 'year') ? 'visible' : 'hidden';
    contextMenuHtml += '<div onclick="toggleReportFilter(\'year\', event)">' +
        '<span id="toggle-filter-year" class="cm-line cm-line-icon-left cm-click">' +
        t('<!--checked--> Year', [['<!--checked-->', '<span style="visibility: ' + myVisibility + ';">&#10003;</span>']]) + '</span></div>';
    return contextMenuHtml;
};
