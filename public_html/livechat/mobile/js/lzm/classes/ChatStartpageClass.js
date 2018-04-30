/****************************************************************************************
 * LiveZilla ChatStartpageClass.js
 *
 * Copyright 2014 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/
function ChatStartpageClass() {
}

ChatStartpageClass.prototype.createStartPage = function(lz, ca, cr) {
    var singleStartpageIframe;
    var numberOfStartPages = (lzm_chatDisplay.startPages.show_lz == '1') ? 1 : 0;
    numberOfStartPages += lzm_chatDisplay.startPages.others.length;
    if (!lzm_chatDisplay.startPageTabControlDoesExist) {
        if (numberOfStartPages == 1) {
            singleStartpageIframe = this.createSingleStartPage(lz, ca, cr);
            if (singleStartpageIframe != '')
                $('#startpage-body').html(singleStartpageIframe);
            lzm_chatDisplay.startPageTabControlDoesExist = false;
        } else
        {
            $('#startpage-body').html('<div id="startpage-placeholder"></div>');

            lzm_displayHelper.createTabControl('startpage-placeholder', this.createStartPagesArray(lz, ca, cr), -1, lzm_chatDisplay.windowWidth - 22);
            lzm_chatDisplay.startPageTabControlDoesExist = true;
        }
    } else {
        if (numberOfStartPages == 1) {
            singleStartpageIframe = this.createSingleStartPage(lz, ca, cr);
            if (singleStartpageIframe != '')
                $('#startpage-body').html(singleStartpageIframe);
            lzm_chatDisplay.startPageTabControlDoesExist = false;
        } else {
            lzm_displayHelper.updateTabControl('startpage-placeholder', this.createStartPagesArray(lz, ca, cr));
            lzm_chatDisplay.startPageTabControlDoesExist = true;
        }
    }
    UIRenderer.resizeStartPage();
    $('#startpage-placeholder-tab').click(function() {
        UIRenderer.resizeStartPage();
    });
};

ChatStartpageClass.prototype.createSingleStartPage = function(lz, ca, cr) {
    var startPageHtml = '';
    if (lz || ca.length > 0 || cr.length)
    {
        if (lzm_chatDisplay.startPages.show_lz == '1')
        {
            var pcx0 = 0, pcx1 = -1;
            if (DataEngine.crc3 != null) 
			{
                try
                {
                    pcx0 = DataEngine.getTDays();

                    if(pcx0==null)
                        pcx0 = 0;

                    pcx1 = DataEngine.crc3[5];
                }
                catch(e)
                {}
            }

            var pcx = pcx0 + '_' + pcx1;

            if(DataEngine.getConfigValue('gl_lzch',false) == 1)
                pcx += '&lzch=1';

            var startPageUrl = 'https://start.livezilla.net/startpage/en/?&product_version=' + lzm_commonConfig.lz_version + '&web=1&app=' + (IFManager.IsAppFrame?'1':'0') + '&mobile=' + (IFManager.IsMobileOS?'1':'0') + '&pcx=' + pcx;
            startPageHtml = '<div id="single-startpage-outer-div"><iframe id="single-startpage-iframe" src="' + startPageUrl + '" style="border:0px;"></iframe>';
        }
        else
        {
            var customPageUrl = lzm_chatDisplay.startPages.others[0].url;
            if (lzm_chatDisplay.startPages.others[0].get_param != 0)
            {
                customPageUrl += (customPageUrl.indexOf('?') != -1) ? '&' : '?';
                customPageUrl += 'operator=' + DataEngine.myUserId;
            }
            startPageHtml = '<div id="single-startpage-outer-div"><iframe id="single-startpage-iframe" src="' + customPageUrl + '" style="border:0px;"></iframe></div>';
        }
    }
    return startPageHtml
};

ChatStartpageClass.prototype.createStartPagesArray = function(lz, ca, cr) {
    var startPagesArray = [], i = 0, customPageUrl;
    var pcx0 = 14, pcx1 = -1;
    if (DataEngine.crc3 != null) {
        try {
            pcx0 = Math.max(0, 5184000 - Math.ceil(lzm_chatTimeStamp.getServerTimeString(null, true) - parseInt(DataEngine.crc3[0])));
            pcx1 = DataEngine.crc3[5];
        } catch(e) {}
    }
    var pcx = pcx0 + '_' + pcx1;
    var startPageUrl = 'https://start.livezilla.net/startpage/en/?&product_version=' + lzm_commonConfig.lz_version +
        '&web=1&app=' + app + '&mobile=' + mobile + '&pcx=' + pcx;
    if (!lzm_chatDisplay.startPageTabControlDoesExist) {
        if (lzm_chatDisplay.startPages.show_lz == 1) {
            startPagesArray.push({name: t('Startpage'), content: '<div id="startpage-lz-outer-div" class="startpage-iframe-outer-div">' +
                '<iframe id="startpage-lz" class="startpage-iframe"' +
                ' src="' + startPageUrl + '"></iframe></div>', hash: 'lz'});
        }
        for (i=0; i<lzm_chatDisplay.startPages.others.length; i++) {
            customPageUrl = lzm_chatDisplay.startPages.others[i].url;
            if (lzm_chatDisplay.startPages.others[i].get_param != 0) {
                customPageUrl += (customPageUrl.indexOf('?') != -1) ? '&' : '?';
                customPageUrl += 'operator=' + DataEngine.myUserId;
            }
            startPagesArray.push({name: lzm_chatDisplay.startPages.others[i].title, content: '<div id="startpage-' +
                lzm_chatDisplay.startPages.others[i].hash +'-outer-div" class="startpage-iframe-outer-div">' +
                '<iframe id="startpage-' + lzm_chatDisplay.startPages.others[i].hash + '" class="startpage-iframe"' +
                ' src="' + customPageUrl + '"></iframe></div>',
                hash: lzm_chatDisplay.startPages.others[i].hash});
        }
    } else {
        if (lz && lzm_chatDisplay.startPages.show_lz == 1) {
            startPagesArray.push({name: t('Startpage'), content: '<div id="startpage-lz-outer-div" class="startpage-iframe-outer-div">' +
                '<iframe id="startpage-lz" class="startpage-iframe" src="' + startPageUrl + '"></iframe></div>', hash: 'lz', action: lzm_chatDisplay.startPages.show_lz});
        }
        for (i=0; i<ca.length; i++) {
            customPageUrl = ca[i].url;
            if (ca[i].get_param != 0) {
                customPageUrl += (customPageUrl.indexOf('?') != -1) ? '&' : '?';
                customPageUrl += 'operator=' + DataEngine.myUserId;
            }
            startPagesArray.push({name: ca[i].title, content: '<div id="startpage-' + ca[i].hash +'-outer-div" class="startpage-iframe-outer-div">' +
                '<iframe id="startpage-' + ca[i].hash +'" class="startpage-iframe" src="' + customPageUrl + '"></iframe></div>', hash: ca[i].hash, action: 1});
        }
        for (i=0; i<cr.length; i++) {
            startPagesArray.push({name: cr[i].title, content: '', hash: cr[i].hash, action: 0});
        }
    }
    return startPagesArray;
};
