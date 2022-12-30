/* 
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Base
 */

define(['jquery',
    'Rsilva_Vitrine/js/model/pinpoint',
    'Rsilva_ProductFilterApi/js/model/product',
    'loader'
], function ($, Pinpoint, Product) {

    const CONFIG = {
        SELECTORS: {
            VITRINE_CONTAINER: '.vitrine-container',
            PRODUCT_TAG: '.product-tag'
        }
    }

    function renderPinpoint(pinpoint, element) {
        pinpoint.product = new Product(pinpoint.product);
        let new_pinpoint = new Pinpoint(element, pinpoint.position.x, pinpoint.position.y, pinpoint.product);
        new_pinpoint.render();
        $(new_pinpoint.pinpoint_element)
            .on('mouseenter', e => togglePinpointSelected(e, new_pinpoint))
            .on('mouseleave', e => togglePinpointSelected(e, new_pinpoint));
    }

    function togglePinpointSelected(event, pinpoint) {
        let product_tag = event.target.closest(CONFIG.SELECTORS.VITRINE_CONTAINER).querySelector(CONFIG.SELECTORS.PRODUCT_TAG);

        switch (event.type) {
            case 'mouseenter':
                calculateTagPosition(product_tag);
                populateProductTag(pinpoint);
                return product_tag.classList.add('active');
            case 'mouseleave':
                return product_tag.classList.remove('active');
        }

        function calculateTagPosition(product_tag) {
            product_tag.style.left = '';
            product_tag.style.top = '';

            product_tag.style.setProperty('left',
                `calc(${pinpoint.position.x + pinpoint.position.measure_unit} 
                + ${pinpoint.pinpoint_element.clientWidth / 4}px)`);
            let edit_bounding = product_tag.getBoundingClientRect();
            if ((edit_bounding.x + edit_bounding.width) > document.body.clientWidth) {
                product_tag.style.left = '';
                product_tag.style.setProperty('left',
                    `calc(${pinpoint.position.x + pinpoint.position.measure_unit} 
                - ${edit_bounding.width + pinpoint.pinpoint_element.clientWidth / 4}px)`);
            }
            product_tag.style.setProperty('top',
                `calc(${pinpoint.position.y + pinpoint.position.measure_unit} 
                - ${pinpoint.pinpoint_element.clientHeight - pinpoint.pinpoint_element.clientWidth / 2}px)`);
        }
    }

    function populateProductTag(pinpoint) {
        let product_tag = $(CONFIG.SELECTORS.VITRINE_CONTAINER).find(CONFIG.SELECTORS.PRODUCT_TAG)[0];
        if (!product_tag) return;
        $('[data-prod_link]').attr("href", pinpoint.product.complete_page_url);
        $('[data-prod_image]').attr("src", pinpoint.product.complete_image_url);
        $('[data-prod_title]').text(pinpoint.product.name);
        $('[data-prod_sku]').text(pinpoint.product.sku);
        if (pinpoint.product.price) $('[data-prod_price]').text(pinpoint.product.formatted_price);
        if (pinpoint.product.special_price) $('[data-prod_special_price]').text(pinpoint.product.formatted_special_price);
        if (!pinpoint.product.price && !pinpoint.product.special_price) $('[data-prod_price]').text($.mage.__('See more details'));
        $('[data-prod_description]').html(pinpoint.product.short_description);
    }

    return function ({ pinpoints }, element) {
        if (!pinpoints) return;
        $(element).parent().loader({ icon: require.toUrl('images/loader-2.gif') });
        $(element).parent().on('processStart', () => {
            pinpoints.forEach(pinpoint => { renderPinpoint(pinpoint, element) });
            let product_tag = $(CONFIG.SELECTORS.VITRINE_CONTAINER).find(CONFIG.SELECTORS.PRODUCT_TAG);
            product_tag
                .on('mouseenter', () => product_tag[0].classList.add('active'))
                .on('mouseleave', () => product_tag[0].classList.remove('active'));

            setTimeout(() => { $(element).parent().trigger('processStop'); }, 300);
        });
        $(element).parent().trigger('processStart');
    };
});