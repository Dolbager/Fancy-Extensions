/*global PUNBB: true */

PUNBB.fancy_stop_spam = (function () {
    "use strict";
    var evidence_block = null;

    function get(el) {
        return document.getElementById(el);
    }

    function update_evidence_block(need_show) {
        if (need_show) {
            PUNBB.common.removeClass(evidence_block, 'hidden');
            get('fancy_stop_spam_report_to_sfs_evidence').focus();
        } else {
            PUNBB.common.addClass(evidence_block, 'hidden');
        }
    }

    function reportCheckbox_attach_event() {
        var report_checkbox = get('fancy_stop_spam_report_to_sfs');

        if (report_checkbox) {
            report_checkbox.onclick = function () {
                var need_show_evidence_block = !!report_checkbox.checked;
                update_evidence_block(need_show_evidence_block);
            };

            var need_show_evidence_block = !!report_checkbox.checked;
            update_evidence_block(need_show_evidence_block);
        }
    }

    return {
        init: function () {
            evidence_block =  get('fancy_stop_spam_report_evidence_block');
            if (evidence_block) {
                reportCheckbox_attach_event();
                PUNBB.common.removeClass(evidence_block, 'no_js');
            }
        }
    };
}());

PUNBB.common.addDOMReadyEvent(PUNBB.fancy_stop_spam.init);