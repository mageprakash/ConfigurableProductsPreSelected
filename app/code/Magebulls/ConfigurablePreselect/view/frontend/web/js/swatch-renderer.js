/**
 * Mageants ConfigurablePreselect Magento2 Extension 
 */ 
define([
    'jquery',
    'underscore',
    'jquery/ui',
    'mage/SwatchRenderer',
], function ($, _) {
    'use strict';
    
    /* jQuery load event */
    $(document).ready(function () {
        localStorage.setItem('processed', '');
    });
    
    $.widget('mage.SwatchRenderer', $.mage.SwatchRenderer, {

        /**
         * Get default options values settings with either URL query parameters
         * @private
         */
        _getSelectedAttributes: function () {
            if (typeof this.options.preSelectedOption !== 'undefined') {
                return this.options.preSelectedOption;
            }
        },

        /**
         * Emulate mouse click on all swatches that should be selected
         * @param {Object} [selectedAttributes]
         * @private
         */
        _EmulateSelected: function (selectedAttributes) {
            $.each(selectedAttributes, $.proxy(function (attributeCode, optionId) {
                var elem = this.element.find('.' + this.options.classes.attributeClass +
                    '[attribute-code="' + attributeCode + '"] [option-id="' + optionId + '"]'),
                    parentInput = elem.parent();
                if (elem.hasClass('selected')) {
                    return;
                }

                
                if (parentInput.hasClass(this.options.classes.selectClass)) {
                    parentInput.val(optionId);
                    parentInput.trigger('change');
                } else {
                    elem.trigger('click');
                }
            }, this));
        },


        /**
         * Event for swatch options
         *
         * @param $this
         * @param $widget
         * @private
         */
        _OnClick: function ($this, $widget) {
            /* Fix issue cannot add product to cart */
            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                $wrapper = $this.parents('.' + $widget.options.classes.attributeOptionsWrapper),
                $label = $parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                attributeId = $parent.attr('attribute-id'),
                $input = $parent.find('.' + $widget.options.classes.attributeInput);
            if ($widget.inProductList) {
                $input = $widget.productForm.find(
                    '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                );
            }

            if ($this.hasClass('disabled')) {
                return;
            }

            if ($this.hasClass('selected')) {
                $parent.removeAttr('option-selected').find('.selected').removeClass('selected');
                $input.val('');
                $label.text('');
                $this.attr('aria-checked', false);
            } else {
                $parent.attr('option-selected', $this.attr('option-id')).find('.selected').removeClass('selected');
                $label.text($this.attr('option-label'));
                $input.val($this.attr('option-id'));
                $input.attr('data-attr-name', this._getAttributeCodeById(attributeId));
                $this.addClass('selected');
                if (typeof $widget._toggleCheckedAttributes !== "undefined") {
                    $widget._toggleCheckedAttributes($this, $wrapper);
                }
            }
            
            $widget._Rebuild();


            var description = this.options.jsonConfig.associated_products[this.getProduct()][0].description;
            var short_description = this.options.jsonConfig.associated_products[this.getProduct()][0].short_description;
            var product_name = this.options.jsonConfig.associated_products[this.getProduct()][0].product_name;
            var sku = this.options.jsonConfig.associated_products[this.getProduct()][0].sku;
          
            if ($(this.options.productNamePosition).length && this.options.updateProductName == 1) {
                $(this.options.productNamePosition).html(product_name);
            }

            if ($(this.options.productSkuPosition).length && this.options.updateProductSku == 1) {
                $(this.options.productSkuPosition).html(sku);
            }

            if ($(this.options.productShortDescriptionPosition).length && this.options.updateProductShortDescription == 1) {
                $(this.options.productShortDescriptionPosition).html(short_description);
            }

            if ($(this.options.productDescriptionPosition).length && this.options.updateProductDescription == 1) {
                $(this.options.productDescriptionPosition).html(description);
            }

            if ($widget.element.parents($widget.options.selectorProduct)
                    .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
            ) {
                $widget._UpdatePrice();
            }

            $widget._LoadProductMedia();
            $input.trigger('change');
            localStorage.setItem('processed',true);
        },

    });

    return $.mage.SwatchRenderer;
});