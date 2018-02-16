/**
 * Magebulls ConfigurablePreselect Magento2 Extension 
 */ 
define([
    'Magento_Ui/js/form/element/single-checkbox',
    'underscore',
    'uiRegistry',
    'mageUtils'
], function (CheckboxField, _, registry, utils) {
    'use strict';

    return CheckboxField.extend({

        /**
         * @inheritdoc
         */
        initialize: function () {
            var source,
                parentData;

            this._super();

            source = registry.get(this.provider);
            parentData = source.get(this.parentScope);

            this.checked(parentData.checked);

            return this;
        },

        /**
         * @inheritdoc
         */
        initConfig: function (config) {
            this._super();

            if (this.dataName) {
                _.extend(this, {
                    inputName: this.dataName
                });
            }
        },

        /**
         * Handle click for radio buttons. Change source checked value.
         *
         * @returns {boolean}
         */
        onClick: function () {
            var source,
                currentData,
                records;

            source = registry.get(this.provider);
            currentData = source.get(this.parentScope);
            records = source.get(utils.getPart(this.parentScope, -2));

            _.each(records, function (record) {
                record.checked = (currentData.record_id === record.record_id) ? 1 : 0;
            });

            return true;
        },
    });
});