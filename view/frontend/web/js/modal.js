define([
    'uiComponent',
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Magento_Customer/js/customer-data'
], function (Component, $) {
    'use strict';
    return Component.extend({

        initialize: function () {

            this._super();
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                title: false,
                buttons: [],
                modalClass: 'modal-overlay-container'
            };
            var modal_overlay_element = $('#modal-overlay');
            modal_overlay_element.modal(options);
            jQuery('.action-close').hide();
            if (!localStorage.getItem('dataprivacy')) {
                setTimeout(function(){
                    modal_overlay_element.modal('openModal');
                },2000);
            }

        }

    });
});
