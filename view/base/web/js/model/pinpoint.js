/* 
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Base
 */

define([
    'ko'
], function (ko) {
    'use strict';

    let id_counter = 1;
    const MEASURE_UNIT = '%';

    /**
     * @param {HTMLElement} target
     * @param {Number} offsetX
     * @param {Number} offsetY
     */
    return class Pinpoint {
        render() {
            let pinpoint_mark = document.createElement('div');
            pinpoint_mark.classList.add('pinpoint-mark');
            pinpoint_mark.id = 'pinpoint_id_' + this.id;
            this.target.after(pinpoint_mark)
            pinpoint_mark.style.setProperty('left', `calc(${this.position.x + MEASURE_UNIT} - ${pinpoint_mark.clientWidth / 2}px)`);
            pinpoint_mark.style.setProperty('top', `calc(${this.position.y + MEASURE_UNIT} - ${pinpoint_mark.clientHeight / 2}px)`);
            this.pinpoint_element = pinpoint_mark;
        }

        constructor(target, offsetX = 0, offsetY = 0, product = {}) {
            if (!target.after) return;
            this.id = id_counter++;
            this.target = target;
            this.product = product;
            this.pinpoint_element = null;
            this.position = { x: offsetX, y: offsetY, measure_unit: MEASURE_UNIT };
            this.saved = ko.observable(false);
        }
    };

});