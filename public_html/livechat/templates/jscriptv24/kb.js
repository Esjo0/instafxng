/****************************************************************************************
 * LiveZilla kb.js
 *
 * Copyright 2016 LiveZilla GmbH
 * All rights reserved.
 * LiveZilla is a registered trademark.
 *
 ***************************************************************************************/

function KBClass() {
    this.m_Config = null;
}

KBClass.prototype.Search = function() {

    document.getElementById('lz_kb_search_form').submit();

};

