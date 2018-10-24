/*
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

define(
    [
        'Magestore_Webpos/js/model/resource-model/magento-rest/abstract',
        'Magestore_Webpos/js/action/notification/add-notification',
        'mage/translate',
        'Magestore_Webpos/js/helper/alert'
    ],
    function (onlineAbstract, addNotification, __, Alert) {
        "use strict";

        return onlineAbstract.extend({
            initialize: function () {
                this._super();
                this.apiStartPaymentUrl = "/webpos/paypal/startPayment";
                this.apiSendInvoiceUrl = "/webpos/paypal/sendInvoice";
            },
            getCallBackEvent: function(key){
                switch(key){
                    case "startPayment":
                        return "paypal_start_payment_after";
                    case "sendInvoice":
                        return "paypal_send_invoice_after";
                }
            },
            getApiUrl: function(key){
                switch(key){
                    case "apiStartPaymentUrl":
                        return this.apiStartPaymentUrl;
                    case "apiSendInvoiceUrl":
                        return this.apiSendInvoiceUrl;
                }
            },
            startPayment: function(params,deferred){
                var apiUrl,
                    urlParams,
                    callBackEvent;
                apiUrl = this.getApiUrl("apiStartPaymentUrl");
                callBackEvent = this.getCallBackEvent("startPayment");
                urlParams = {};
                this.callRestApi(apiUrl, "post", urlParams, params, deferred, callBackEvent);
                this.procressResponse(deferred);
            },
            sendInvoice: function(params,deferred){
                var apiUrl,
                    urlParams,
                    callBackEvent;
                apiUrl = this.getApiUrl("apiSendInvoiceUrl");
                callBackEvent = this.getCallBackEvent("sendInvoice");
                urlParams = {};
                this.callRestApi(apiUrl, "post", urlParams, params, deferred, callBackEvent);
                this.procressResponse(deferred);
            },
            procressResponse: function(deferred){
                deferred.fail(function (response) {
                    if (response.responseText) {
                        var error = JSON.parse(response.responseText);
                        if (error.message != undefined) {
                            addNotification(error.message, true, 'danger', 'Error');
                        }else{
                            Alert({
                                priority: 'danger',
                                title: __('Message'),
                                message: __('Something went wrong with the application, please check the exception.log file to see more detail about the error')
                            });
                        }
                    } else {
                        addNotification("Please check your network connection", true, 'danger', 'Error');
                    }
                });
            }
        });
    }
);