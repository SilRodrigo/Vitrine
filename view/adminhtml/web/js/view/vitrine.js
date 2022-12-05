/* 
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Base
 */

define([
    'jquery',
    'ko',
    'mage/translate',
    'Magento_Ui/js/form/element/abstract',
    'Rsilva_Vitrine/js/model/pinpoint',
    'Rsilva_ProductFilterApi/js/service/product-api',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/lib/view/utils/async',
], function ($, ko, $t, Abstract, Pinpoint, productApiService, alert) {
    'use strict';

    const MODE = {
        EDIT: 'EDIT',
        NEW: 'NEW',
        DEFAULT: 'DEFAULT'
    }

    return Abstract.extend({

        /* Properties */
        /* ---------- */

        /* Vitrine */
        vitrine_name: ko.observable(''),
        elementToPinpoint: null,
        input_selector: '[name="pinpoints"]',
        reference_image: ko.observable(''),
        current_mode: ko.observable(MODE.DEFAULT),
        isRendered: false,

        /* Pinpoint */
        pinpoint_edit_elem: '#pinpoint_edit',
        pinpoints: new Map(),
        serialized_data: ko.observable('[]'),
        selected_pinpoint: ko.observableArray([]),

        /* Products */
        delayed_search: null,
        currency_list: { "pt-BR": 'BRL', "us-EN": 'USD' },
        product_list: ko.observableArray([]),

        /* ---------- */

        /**
         * @returns {Provider} Chainable.
         */
        initialize: function () {
            this._super();
            const _self = this;
            this.vitrine_name(this.source.data.name);
            this.reference_image(this.source.data.image_url);
            this.source.data = new Proxy(this.source.data, {
                set(obj, prop, value) {
                    if (prop === 'image') {
                        if (value[0]) {
                            _self.reference_image(value[0].url);
                            setTimeout(() => {
                                _self.elementToPinpoint
                                    .closest('.vitrine-wrapper')
                                    .scrollIntoView({ block: "center", behavior: "smooth" });
                            }, 250)

                        }
                        else {
                            _self.reference_image('');
                            _self.clearPinpoints();
                        }
                    }
                    if (prop === 'name') {
                        _self.vitrine_name(value);
                    }
                    obj[prop] = value;
                    return true;
                },
            });
            this.serialized_data.subscribe(function (value) {
                $(_self.input_selector).val(value).change();
            })
            return this;
        },

        setCurrentMode(value) {
            if (MODE[value]) this.current_mode(value);
        },

        updateSerializedData() {
            this.serialized_data(this.getSerializedPinpoints());
        },

        /* Pinpoint handlers */
        /* ---------------- */

        /**
         * @returns {Array<Pinpoint>}
         */
        getSerializedPinpoints() {
            const pinpoints = [];
            this.pinpoints.forEach(pinpoint => {
                const cur_pin = { product: pinpoint.product, position: pinpoint.position };
                pinpoints.push(cur_pin);
            });
            return JSON.stringify(pinpoints);
        },

        getSelectedPinpoint() {
            return this.selected_pinpoint()[0];
        },

        setSelectedPinpoint(pinpoint) {
            pinpoint.pinpoint_element.classList.add('active');
            this.selected_pinpoint([pinpoint]);
        },

        clearSelectedPinpoint() {
            this.getSelectedPinpoint()?.pinpoint_element.classList.remove('active');
            this.selected_pinpoint([]);
            this.updateSerializedData();
        },

        savePinpoint(pinpoint) {
            pinpoint.saved(true);
            this.updateSerializedData();
        },

        clearPinpoints() {
            this.pinpoints.forEach(pinpoint => { pinpoint.pinpoint_element.remove(); })
            this.clearSelectedPinpoint();
            this.pinpoints.clear();
            this.updateSerializedData();
        },

        /**
         * @param {HTMLImageElement} element img which will be pinned.
         */
        initPinpointListener(element) {
            if (element) this.elementToPinpoint = element;
            this.setCurrentMode(MODE.DEFAULT);
            this.elementToPinpoint.onclick = event => {
                this.clearSelectedPinpoint();
                let offsetX = convertToPercent(event.target.clientWidth, event.offsetX),
                    offsetY = convertToPercent(event.target.clientHeight, event.offsetY);
                this.editPinpoint(this.createPinpoint(event.target, offsetX, offsetY), MODE.NEW);
            }

            function convertToPercent(reference, value) {
                if (!reference || !value) return 0;
                value *= 100;
                return value / reference;
            }
        },

        /**
         * @param {HTMLElement} element element where pinpoints are stored to be saved.
         */
        renderPinpoints() {
            $.async(this.input_selector, (element) => {
                if (this.isRendered) return;
                let pinpoints = [];
                if (element.value) pinpoints = JSON.parse(element.value);
                if (pinpoints.length > 0) $('body').trigger('processStart');
                pinpoints.forEach((pinpoint, i) => {
                    productApiService.getProductById(pinpoint.product.entity_id, true).then(response => {
                        if (response?.collection && response.collection[0]) {
                            pinpoint.product = response.collection[0];
                            const cur_pin = this.createPinpoint(this.elementToPinpoint,
                                pinpoint.position.x, pinpoint.position.y, pinpoint.product);
                            this.savePinpoint(cur_pin);
                        }
                        if (i === pinpoints.length - 1) $('body').trigger('processStop');
                    })
                });
                this.isRendered = true;
            });
        },

        /**
         * @param {PointerEvent} event
         * @returns {Pinpoint | null }
         */
        createPinpoint(target, offsetX, offsetY, product) {
            let _self = this;
            let pinpoint = new Pinpoint(target, offsetX, offsetY, product);
            if (!pinpoint.id) return;
            pinpoint.render();
            pinpoint.saved.subscribe(function (value) {
                if (!value) return;
                _self.pinpoints.set(pinpoint.id, pinpoint);
                _self.updateSerializedData();
            });
            pinpoint.pinpoint_element.onclick = function () {
                if ((!pinpoint.product.name || _self.current_mode() === 'NEW')
                    || (_self.current_mode() === MODE.EDIT && pinpoint.id === _self.getSelectedPinpoint().id)) {
                    return _self.cancelPinpointEdit($(_self.pinpoint_edit_elem)[0]);
                }
                _self.editPinpoint(pinpoint, MODE.EDIT);
            };
            return pinpoint;
        },

        editPinpoint(pinpoint, mode) {
            const pinpoint_edit = document.getElementById('pinpoint_edit');
            if (!pinpoint_edit || !pinpoint) return;
            this.elementToPinpoint.onclick = () => { this.cancelPinpointEdit(pinpoint_edit); };
            this.setCurrentMode(mode);
            this.setSelectedPinpoint(pinpoint);
            calculateEditPosition();
            pinpoint_edit.dataset.linked_pin = pinpoint.id;
            pinpoint_edit.classList.add('active');
            let pin_input_edit = pinpoint_edit.querySelector('input');
            if (!pin_input_edit) return;
            pin_input_edit.focus();
            pin_input_edit.value = '';

            function calculateEditPosition() {
                pinpoint_edit.style.setProperty('left',
                    `calc(${pinpoint.position.x + pinpoint.position.measure_unit}
                    + ${pinpoint.pinpoint_element.clientWidth / 2}px)`);
                let edit_bounding = pinpoint_edit.getBoundingClientRect();
                if ((edit_bounding.x + edit_bounding.width) > document.body.clientWidth) {
                    pinpoint_edit.style.setProperty('left',
                        `calc(${pinpoint.position.x + pinpoint.position.measure_unit}
                    - ${edit_bounding.width + pinpoint.pinpoint_element.clientWidth / 2}px)`);
                }
                pinpoint_edit.style.setProperty('top',
                    `calc(${pinpoint.position.y + pinpoint.position.measure_unit}
                    - ${pinpoint.pinpoint_element.clientHeight * 1.3}px)`);
            }
        },

        /**
         * @param {HTMLElement} element this.pinpoint_edit_elem must contain pinpoint-element.
         */
        cancelPinpointEdit(element) {
            element.classList.remove('active');
            if (this.getSelectedPinpoint() && !this.getSelectedPinpoint().saved()) {
                this.getSelectedPinpoint().pinpoint_element.remove();
            }
            this.clearSelectedPinpoint();
            this.clearProductList();
            this.setCurrentMode(MODE.DEFAULT);
            if (!this.getSelectedPinpoint()) this.initPinpointListener();
        },

        deletePinpointModal() {
            const _self = this;

            const title = $t('Are you sure you want to delete this pin?'),
                confirmation = $t('Yes, delete this pinpoint'),
                cancel = $t('No, cancel this action');

            alert({
                title: title,
                buttons: [{
                    text: confirmation,
                    class: 'action primary accept',
                    click: function () {
                        _self.deletePinpoint();
                        this.closeModal(true);
                    }
                }, {
                    text: cancel,
                    class: 'action',
                    click: function () {
                        this.closeModal(true);
                    }
                }]
            });

        },

        deletePinpoint() {
            const pinpoint_edit_elem = $(this.pinpoint_edit_elem)[0];
            let pinpoint = this.getSelectedPinpoint();
            pinpoint.pinpoint_element.remove();
            this.pinpoints.delete(parseInt(pinpoint.id));
            this.cancelPinpointEdit(pinpoint_edit_elem);
        },

        /* ---------------- */

        /* Product search handlers */

        getFormattedPrice: function (price) {
            const locale = navigator.language,
                currency = this.currency_list[locale] ? { style: 'currency', currency: this.currency_list[locale] } : {};
            return new Intl.NumberFormat(locale, currency).format(price);
        },

        delayedSearchEvent(scope, event) {
            clearTimeout(this.delayed_search);
            if (!event.currentTarget || event.currentTarget.value.length < 4) return;
            this.delayed_search = setTimeout(() => {
                this.queryProductByName(event.currentTarget.value);
            }, 1000);
        },

        keyPressSearchEvent(event) {
            if (!event.currentTarget.value || event.currentTarget.value.length > 3) return;
            this.queryProductByName(event.currentTarget.value);
        },

        clearProductList() {
            this.product_list([]);
        },

        queryProductByName(name) {
            if (productApiService.isRequesting) return;
            this.clearProductList();
            productApiService.queryProductByName(name, (response) => {
                if (response?.collection.length > 0) this.product_list(response.collection);
            })
        },

        setProductToPin(product, event) {
            const pinpoint_edit_elem = event.currentTarget.closest(this.pinpoint_edit_elem);
            let pinpoint = this.pinpoints.get(parseInt(pinpoint_edit_elem?.dataset.linked_pin));
            if (!pinpoint) pinpoint = this.getSelectedPinpoint();
            pinpoint.product = product;
            this.savePinpoint(pinpoint);
            this.cancelPinpointEdit(pinpoint_edit_elem);
            this.editPinpoint(pinpoint, MODE.EDIT);
        },

        editProduct() {
            this.editPinpoint(this.getSelectedPinpoint(), MODE.NEW);
        },

    });

});