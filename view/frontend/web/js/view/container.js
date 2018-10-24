/*
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'mage/translate',
        'Magestore_Webpos/js/model/event-manager',
        'Magestore_Webpos/js/helper/alert',
        'Magestore_WebposPaypal/js/model/paypal'
    ],
    function ($, ko, Component, __, Event, Alert, PaypalService) {
        "use strict";
        return Component.extend({
            /**
             * Default data for UI component
             */
            defaults: {
                template: 'Magestore_WebposPaypal/container',
                container_selector: '#webpos_paypal_integration_container',
                overlay_selector: '#webpos_paypal_integration_overlay'
            },
            /**
             * Check flag to show iframe
             */
            visible: ko.observable(false),
            /**
             * Check if using authorize mode
             */
            isAuthorize: ko.observable(true),
            /**
             * Show ajax loader
             */
            loading: PaypalService.loading,
            /**
             * Flag to check blocked popup
             */
            blocked: PaypalService.blocked,
            /**
             * Logo url
             */
            logoUrl: PaypalService.logoUrl,
            /**
             * Flag to check if allow to pay via email
             */
            enableSendInvoice: PaypalService.enableSendInvoice,
            /**
             * Initialize
             */
            initialize: function () {
                this._super();
                var self = this;
                self.initEvents();
            },
            /**
             * Init events
             */
            initEvents: function(){
                var self = this;
                Event.observer('enable_create_invoice', function(event, data){
                    self.enableCreateInvoiceCheckbox(true);
                });
                Event.observer('disable_create_invoice', function(event, data){
                    self.enableCreateInvoiceCheckbox(false);
                });
                Event.observer('open_paypal_integration', function(event, data){
                    self.checkDefaultMode();
                    self.visible(true);
                });
                Event.observer('close_paypal_integration', function(event, data){
                    self.checkDefaultMode();
                    self.visible(false);
                });
                self.visible.subscribe(function(value){
                    if(value){
                        $(self.container_selector).removeClass('hide');
                        $(self.overlay_selector).removeClass('hide');
                    }else{
                        $(self.container_selector).addClass('hide');
                        $(self.overlay_selector).addClass('hide');
                    }
                });
                self.checkDefaultMode();
            },
            /**
             * Set default mode
             */
            checkDefaultMode: function(){
                var self = this;
                if(self.enableSendInvoice()){
                    self.isAuthorize(false);
                }
            },
            /**
             * Close popup
             */
            close: function(){
                var self = this;
                self.visible(false);
                PaypalService.closeAuthorizeWindow();
            },
            /**
             * Open paypal window again
             */
            tryAgain: function(){
                if(PaypalService.authorizeUrl()){
                    PaypalService.openAuthorizeWindow(PaypalService.authorizeUrl());
                }
            },
            /**
             * Start authorize mode
             */
            authorize: function(){
                var self = this;
                self.isAuthorize(true);
                PaypalService.start();
            },
            /**
             * Start send invoice
             */
            sendInvoice: function(){
                var self = this;
                if(PaypalService.validateCustomerData()){
                    self.isAuthorize(false);
                    self.enableCreateInvoiceCheckbox(false);
                    PaypalService.sendInvoice();
                }else{
                    Alert({
                        priority: 'danger',
                        title: __('Message'),
                        message: __('Send invoice is not allowed for guest checkout, please select the customer to continue!')
                    });
                }
            },
            enableCreateInvoiceCheckbox: function(enable){
                var select = $('#can_paid');
                if($('#can_paid').find('.ios-ui-select') != undefined){
                    var bootstrapSlide = $('#can_paid').find('.ios-ui-select');
                    if(enable == true){
                        bootstrapSlide.addClass('checked');
                    }else{
                        bootstrapSlide.removeClass('checked');
                    }
                }
            }
        });
    }
);