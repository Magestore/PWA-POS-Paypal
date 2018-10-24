/*
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

define(
    [
        'jquery',
        'ko',
        'mage/translate',
        'Magestore_Webpos/js/helper/alert',
        'Magestore_Webpos/js/helper/price',
        'Magestore_Webpos/js/helper/general',
        'Magestore_Webpos/js/model/event-manager',
        'Magestore_Webpos/js/view/checkout/checkout/payment_selected',
        'Magestore_Webpos/js/view/checkout/checkout/payment_popup',
        'Magestore_Webpos/js/view/checkout/checkout/payment',
        'Magestore_Webpos/js/model/checkout/checkout',
        'Magestore_Webpos/js/model/checkout/cart',
        'Magestore_Webpos/js/model/checkout/cart/items',
        'Magestore_Webpos/js/model/checkout/cart/totals-factory',
        'Magestore_WebposPaypal/js/resource/paypal',
        'Magestore_Webpos/js/action/notification/add-notification'
    ],
    function ($, ko, __, Alert, PriceHelper, Helper, Event, SelectedPayment, PopupPayment, Payment, CheckoutModel, CartModel, Items, TotalsFactory, ApiResource, addNotification) {
        "use strict";

        var PaypalService = {
            /**
             * Logo url
             */
            logoUrl: ko.observable(''),
            /**
             * Paypal authorize url
             */
            authorizeUrl: ko.observable(''),
            /**
             * show ajax loader
             */
            loading: ko.observable(false),
            /**
             * Flag to check the popup has been blocked
             */
            blocked: ko.observable(false),
            /**
             * Flag to check if allow customer pay via email
             */
            enableSendInvoice: ko.observable(false),
            /**
             * Save invoice number sent to customer email
             */
            lastInvoiceData: ko.observable(),
            /**
             * authorize window
             */
            authorizeWindow: ko.observable(),
            /**
             * columns
             */
            renderPaymentColumns: ko.observable(false),
            /**
             * Payment method code
             */
            PAYMENT_METHOD_CODE: 'paypal_integration',
            /**
             * Initialize
             */
            initialize: function () {
                var self = this;
                self.initCallbacks();
                self.initPaymentMethod();
                self.initEvents();
                return self;
            },
            /**
             * Start create payment
             */
            start: function(){
                var self = this;
                var deferred = $.Deferred();
                var params = {
                    transaction:self.getTransactionData()
                };
                ApiResource().setPush(true).setLog(false).startPayment(params, deferred);
                self.loading(true);
                deferred.done(function (response) {
                    if(response){
                        self.openAuthorizeWindow(response);
                    }else{
                        Event.dispatch('close_paypal_integration', '');
                    }
                    self.showMessage(response);
                }).always(function () {
                    self.loading(false);
                });
            },
            /**
             * Show message by process response
             * @param response
             */
            showMessage: function(response){
                if(response && response.message){
                    var priority = (response.success)?'success':'danger';
                    var title = (response.success)?'Message':'Error';
                    Alert({
                        priority: priority,
                        title: __(title),
                        message: __(response.message)
                    });
                }
            },
            /**
             * Open authorize window
             * @param url
             */
            openAuthorizeWindow: function(url){
                var self = this;
                if(self.authorizeWindow()){
                    self.authorizeWindow().close();
                }
                self.authorizeUrl(url);
                var authorizeWindow = window.open(url, 'authorize_window', 'status=1,width=700,height=700');
                if(authorizeWindow){
                    self.blocked(false);
                    self.authorizeWindow(authorizeWindow);
                }else{
                    self.blocked(true);
                }
            },
            /**
             * Close authorize window
             */
            closeAuthorizeWindow: function(){
                var self = this;
                if(self.authorizeWindow()){
                    self.authorizeWindow().close();
                    self.authorizeWindow('');
                }
            },
            /**
             * Close paypal integration popup
             */
            cancel: function(){
                Event.dispatch('close_paypal_integration', '');
            },
            /**
             * Update reference number and place order
             * @param responseText
             */
            success: function(responseText){
                var self = this;
                if(responseText){
                    var response = JSON.parse(responseText);
                    if(response){
                        self.showMessage(response);
                        if(response.success){
                            Event.dispatch('paypal_integration_payment_complete', '');
                            if(response.transactionId){
                                self.updateRefNumber(response.transactionId);
                                if(Helper.isUseOnline('checkout')){
                                    CheckoutModel.placeOrderOnline();
                                }else{
                                    CheckoutModel.createOrder();
                                }
                            }
                        }
                    }
                }
                Event.dispatch('close_paypal_integration', '');
            },
            /**
             * Set init object to call from childs window
             */
            initCallbacks: function(){
                var self = this;
                if(typeof window.paypalService == 'undefined'){
                    window.paypalService = {
                        cancel:self.cancel,
                        success:$.proxy(self.success, self)
                    };
                }
            },
            /**
             * Get paypal method from selected payment list
             * @returns {*}
             */
            getSelectedPaypalMethod: function(){
                var self = this;
                var payments = CheckoutModel.selectedPayments();
                var paypal = ko.utils.arrayFirst(payments, function(method){
                    return method.code == self.PAYMENT_METHOD_CODE;;
                });
                return paypal;
            },
            /**
             * Subscribe the list to add paypal payment method
             */
            initPaymentMethod: function(){
                var self = this;
                PopupPayment().items.subscribe(function(items){
                    var paypal = self.getSelectedPaypalMethod();
                    if(!paypal){
                        // items = self.checkPaymentList(items);
                    }
                });
                Payment().items.subscribe(function(items){
                    // items = self.checkPaymentList(items);
                });
            },
            /**
             * Add paypal payment method
             * @param items
             * @returns {*}
             */
            checkPaymentList: function(items){
                var self = this;
                var inserted = false;
                if(items.length > 0){
                    var paypal = ko.utils.arrayFirst(items, function(method){
                        return method.code == self.PAYMENT_METHOD_CODE;
                    });
                    if(paypal){
                        inserted = true;
                        paypal.type = "0";
                    }
                }
                if(!inserted){
                    var firstMethod = ko.utils.arrayFirst(items, function(method){
                        return method.code != '';
                    });
                    if(firstMethod || self.renderPaymentColumns() != false){
                        if(self.renderPaymentColumns() == false || firstMethod){
                            self.renderPaymentColumns(firstMethod.columns);
                        }
                        items.push({
                            code:self.PAYMENT_METHOD_CODE,
                            columns: self.renderPaymentColumns(),
                            information:"",
                            is_default:"0",
                            is_pay_later:0,
                            is_reference_number:"0",
                            title:__("Web POS - Paypal Integration"),
                            type:"0"
                        });
                    }
                }
                return items;
            },
            /**
             * Init some events, change event when place order
             */
            initEvents: function(){
                var self = this;
                CheckoutModel.selectedPayments.subscribe(function(){
                    $('#checkout_button').unbind('click');
                    $('#checkout_button').click(function(){
                        $.proxy(self.placeOrder(), self);
                    });
                });
                Event.observer('webpos_place_order_before', function (event, data) {
                    self.placeOrderBefore(data);
                });
                Event.observer('webpos_place_order_after', function (event, data) {
                    self.placeOrderAfter(data);
                });
            },
            /**
             * Rewrite place order function
             * @returns {boolean}
             */
            placeOrder: function(){
                var self = this;
                var paypal = self.getSelectedPaypalMethod();
                if(paypal){
                    Event.dispatch('open_paypal_integration', '');
                    if(!self.enableSendInvoice()){
                        self.start();
                    }
                }else{
                    Event.dispatch('start_place_order', '');
                }
                return false;
            },
            /**
             * Save reference number after process paypal payment
             * @param transactionId
             */
            updateRefNumber: function(transactionId){
                var self = this;
                var paypal = self.getSelectedPaypalMethod();
                if(paypal){
                    paypal.reference_number = __("Paypal Transaction ID ")+'#'+transactionId;
                    paypal.is_pay_later = 0;
                }
            },
            /**
             * Get transaction params to create paypal payment
             * @returns {{total: *, currency: string, description: string}}
             */
            getTransactionData: function(){
                var self = this;
                var paypal = self.getSelectedPaypalMethod();
                var amount = (paypal)?paypal.cart_total:0;
                var transaction = {
                    total: PriceHelper.currencyConvert(amount),
                    currency: (window.webposConfig)?window.webposConfig.currentCurrencyCode:'',
                    description: ''
                }
                return transaction;
            },
            /**
             * Get params to create paypal invoice
             * @returns {{billing: *, shipping: *, items: Array, totals: Array, total_paid: *, currency_code: *, note: *}}
             */
            getCreateInvoiceParams: function(){
                var self = this;
                var paypal = self.getSelectedPaypalMethod();
                var totalPaid = parseFloat(CheckoutModel.getTotalPaid()) - parseFloat(paypal.cart_total);
                var items = [];
                var totals = [];
                var Totals = TotalsFactory.get();
                $.each(Totals.getTotals(), function () {
                    totals.push({
                        code: this.code(),
                        amount: PriceHelper.currencyConvert(this.value())
                    });
                });

                $.each(Items.getItems(), function () {
                    items.push({
                        'name': this.product_name(),
                        'qty': this.qty(),
                        'unit_price': this.item_price(),
                        'tax_percent': this.tax_rate()
                    });
                });
                var customerData = CartModel.customerData();
                var billingAddress = CheckoutModel.billingAddress();
                var shippingAddress = CheckoutModel.shippingAddress();
                billingAddress.email = customerData.email;
                billingAddress.id = 0;
                shippingAddress.id = 0;

                var params = {
                    billing: billingAddress,
                    shipping: shippingAddress,
                    items: items,
                    totals: totals,
                    total_paid: PriceHelper.currencyConvert(totalPaid),
                    currency_code: window.webposConfig.currentCurrencyCode,
                    note: CheckoutModel.orderComment()
                };
                return params;
            },
            /**
             * Create paypal invoice and send to customer
             */
            sendInvoice: function(){
                var self = this;
                if(self.loading() == false) {
                    var deferred = $.Deferred();
                    var params = self.getCreateInvoiceParams();
                    ApiResource().setPush(true).setLog(false).sendInvoice(params, deferred);
                    self.loading(true);
                    deferred.done(function (response) {
                        if(typeof response == 'string'){
                            response = JSON.parse(response);
                        }
                        if(response.id){
                            var paypal = self.getSelectedPaypalMethod();
                            if(paypal && response.number){
                                paypal.reference_number = __("Paypal Invoice Number ")+"#"+response.number;
                                paypal.is_pay_later = 1;
                                paypal.cart_total = 0;
                                paypal.paid_amount = 0;
                            }
                            self.lastInvoiceData(response);
                            CheckoutModel.createInvoice(false);
                            Event.dispatch('disable_create_invoice', '');
                            Event.dispatch('close_paypal_integration', '');
                            if(response.qr_code){
                                self.generateQrCodeImage(response.qr_code);
                            }
                            if(Helper.isUseOnline('checkout')){
                                CheckoutModel.placeOrderOnline();
                            }else{
                                CheckoutModel.createOrder();
                            }
                        }
                    }).always(function () {
                        self.loading(false);
                    });
                }
            },
            /**
             * Check if customer has been selected
             * @returns {boolean}
             */
            validateCustomerData: function(){
                return (CartModel.customerId())?true:false;
            },
            /**
             * Add params to save data to order
             * @param data
             */
            placeOrderBefore: function(data){
                var self = this;
                var lastInvoiceData = self.lastInvoiceData();
                if (data && data.increment_id && CartModel.customerId() && lastInvoiceData && lastInvoiceData.id) {
                    data.sync_params.extension_data.push({
                        key:"pos_paypal_invoice_number",
                        value: lastInvoiceData.number
                    });
                    data.sync_params.extension_data.push({
                        key:"pos_paypal_invoice_id",
                        value: lastInvoiceData.id
                    });
                }
            },
            /**
             * Reset invoice data
             * @param data
             */
            placeOrderAfter: function(data){
                var self = this;
                self.lastInvoiceData('');
            },
            /**
             * Show QR code
             * @param code
             */
            generateQrCodeImage: function(code){
                var html = "";
                if(code){
                    var src = "data:image/png;base64,"+code;
                    if($('#webpos_paypal_qrcode').length > 0){
                        $('#webpos_paypal_qrcode').attr('src', src);
                    }else{
                        html = "<img id='webpos_paypal_qrcode' src='"+src+"' alt='"+__("Paypal Invoice QR Code")+"' />";
                        if($('#success_container .icon-iconPOS-success').length > 0){
                            $('#success_container .icon-iconPOS-success').append(html);
                        }
                    }
                }else{
                    $('#webpos_paypal_qrcode').hide();
                }
            }
        };
        return PaypalService.initialize();
    }
);