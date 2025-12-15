/**
 * Woo Dynamic Deals - Cart Discount JavaScript
 */

(function($) {
    'use strict';

    const WDDCart = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            // Open cart modal
            $(document).on('click', '.wdd-add-rule[data-type="cart"]', function(e) {
                e.preventDefault();
                WDDCart.openModal('add');
            });

            // Edit cart rule
            $(document).on('click', '.wdd-edit-rule', function(e) {
                const $tab = $('.nav-tab-active');
                const href = $tab.attr('href');
                if (href && href.indexOf('cart-discounts') !== -1) {
                    e.preventDefault();
                    e.stopPropagation();
                    WDDCart.openModal('edit', $(this).data('rule-id'));
                }
            });

            // Close modal - X button and backdrop
            $(document).on('click', '#wdd-cart-modal .wdd-modal-close, #wdd-cart-modal .wdd-modal-backdrop', function(e) {
                e.preventDefault();
                WDDCart.closeModal();
            });

            // Cart form submit
            $('#wdd-cart-form').on('submit', function(e) {
                e.preventDefault();
                WDDCart.saveRule();
            });

            // Initialize Select2
            this.initSelect2();
        },

        initSelect2: function() {
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }

            $('#wdd-cart-modal .wdd-product-search').select2({
                ajax: {
                    type: 'POST',
                    url: wddAdmin.ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            action: 'wdd_search_products',
                            wdd_nonce: wddAdmin.nonce,
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return { results: data.data || [] };
                    },
                    cache: true
                },
                placeholder: wddAdmin.i18n.searchProducts || 'Search for products...',
                minimumInputLength: 2,
                dropdownParent: $('#wdd-cart-modal')
            });

            $('#wdd-cart-modal .wdd-user-search').select2({
                ajax: {
                    type: 'POST',
                    url: wddAdmin.ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            action: 'wdd_search_users',
                            wdd_nonce: wddAdmin.nonce,
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return { results: data.data || [] };
                    },
                    cache: true
                },
                placeholder: wddAdmin.i18n.searchUsers || 'Search for users...',
                minimumInputLength: 2,
                dropdownParent: $('#wdd-cart-modal')
            });

            $('#wdd-cart-modal .wdd-category-select').select2({
                placeholder: wddAdmin.i18n.selectCategories || 'Select categories...',
                allowClear: true,
                dropdownParent: $('#wdd-cart-modal')
            });
        },

        openModal: function(mode, ruleId) {
            const $modal = $('#wdd-cart-modal');
            const $form = $('#wdd-cart-form');
            const $title = $('#wdd-cart-modal-title');

            // Reset form
            $form[0].reset();
            $('#wdd-cart-id').val('');
            $('.wdd-product-search, .wdd-user-search').val(null).trigger('change');

            if (mode === 'edit' && ruleId) {
                $title.text(wddAdmin.i18n.editCartDiscount || 'Edit Cart Discount');
                WDDCart.loadRule(ruleId);
            } else {
                $title.text(wddAdmin.i18n.addCartDiscount || 'Add Cart Discount');
            }

            $modal.fadeIn(200);
        },

        loadRule: function(ruleId) {
            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wdd_get_rule',
                    wdd_nonce: wddAdmin.nonce,
                    rule_id: ruleId,
                    rule_type: 'cart'
                },
                success: function(response) {
                    if (response.success && response.data) {
                        WDDCart.populateForm(response.data);
                    } else {
                        alert(response.data || wddAdmin.i18n.loadError || 'Failed to load rule.');
                        WDDCart.closeModal();
                    }
                },
                error: function() {
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    WDDCart.closeModal();
                }
            });
        },

        populateForm: function(rule) {
            $('#wdd-cart-id').val(rule.id);
            $('#wdd-cart-title').val(rule.title);
            $('#wdd-cart-priority').val(rule.priority);
            $('#wdd-cart-discount-type').val(rule.discount_type);
            $('#wdd-cart-discount-value').val(rule.discount_value);
            $('input[name="apply_free_shipping"]').prop('checked', rule.apply_free_shipping == 1);
            $('input[name="status"][value="' + rule.status + '"]').prop('checked', true);
            $('input[name="stop_further_rules"]').prop('checked', rule.stop_further_rules == 1);

            // Cart conditions
            if (rule.min_cart_total) $('#wdd-cart-min-total').val(rule.min_cart_total);
            if (rule.max_cart_total) $('#wdd-cart-max-total').val(rule.max_cart_total);
            if (rule.min_cart_quantity) $('#wdd-cart-min-quantity').val(rule.min_cart_quantity);
            if (rule.max_cart_quantity) $('#wdd-cart-max-quantity').val(rule.max_cart_quantity);

            // Dates - extract date part only (yyyy-MM-dd), skip if empty or 0000-00-00
            if (rule.date_from && rule.date_from !== '' && rule.date_from !== 'NULL' && rule.date_from !== null && !rule.date_from.startsWith('0000-00-00')) {
                $('#wdd-cart-date-from').val(rule.date_from.split(' ')[0]);
            }
            if (rule.date_to && rule.date_to !== '' && rule.date_to !== 'NULL' && rule.date_to !== null && !rule.date_to.startsWith('0000-00-00')) {
                $('#wdd-cart-date-to').val(rule.date_to.split(' ')[0]);
            }

            // Days of week
            if (rule.days_of_week && Array.isArray(rule.days_of_week)) {
                rule.days_of_week.forEach(function(day) {
                    $('input[name="days_of_week[]"][value="' + day + '"]').prop('checked', true);
                });
            }

            // User roles
            if (rule.user_roles && Array.isArray(rule.user_roles)) {
                rule.user_roles.forEach(function(role) {
                    $('input[name="user_roles[]"][value="' + role + '"]').prop('checked', true);
                });
            }

            // Categories - add options from PHP response
            if (rule.category_options && Array.isArray(rule.category_options)) {
                const $categorySelect = $('#wdd-cart-category-ids');
                $categorySelect.empty();
                
                rule.category_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $categorySelect.append(newOption);
                });
                $categorySelect.trigger('change');
            }

            // Products - add options from PHP response
            if (rule.product_options && Array.isArray(rule.product_options)) {
                const $productSelect = $('#wdd-cart-product-ids');
                $productSelect.empty();
                
                rule.product_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $productSelect.append(newOption);
                });
                $productSelect.trigger('change');
            }

            // Users - add options from PHP response
            if (rule.user_options && Array.isArray(rule.user_options)) {
                const $userSelect = $('#wdd-cart-user-ids');
                $userSelect.empty();
                
                rule.user_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $userSelect.append(newOption);
                });
                $userSelect.trigger('change');
            }
        },

        saveRule: function() {
            const $form = $('#wdd-cart-form');
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Collect form data
            const ruleData = {};
            $form.serializeArray().forEach(function(item) {
                // Remove [] from field names (e.g., product_ids[] becomes product_ids)
                let fieldName = item.name.replace(/\[\]$/, '');
                
                // If field name ends with [], it's an array field
                if (item.name.endsWith('[]')) {
                    if (!ruleData[fieldName]) {
                        ruleData[fieldName] = [];
                    }
                    ruleData[fieldName].push(item.value);
                } else if (ruleData[fieldName]) {
                    // Duplicate non-array field - convert to array
                    if (!Array.isArray(ruleData[fieldName])) {
                        ruleData[fieldName] = [ruleData[fieldName]];
                    }
                    ruleData[fieldName].push(item.value);
                } else {
                    ruleData[fieldName] = item.value;
                }
            });
            
            const ajaxData = {
                action: 'wdd_save_rule',
                wdd_nonce: wddAdmin.nonce,
                rule_type: ruleData.rule_type || 'cart',
                rule_data: ruleData
            };

            console.log('Cart save request:', ajaxData);

            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: ajaxData,
                beforeSend: function() {
                    $submitBtn.prop('disabled', true).text(wddAdmin.i18n.saving || 'Saving...');
                },
                success: function(response) {
                    console.log('Cart save response:', response);
                    if (response.success) {
                        WDDCart.closeModal();
                        location.reload();
                    } else {
                        console.log('Cart error data:', response.data);
                        var errorMsg = 'Failed to save rule.';
                        if (response.data) {
                            if (typeof response.data === 'string') {
                                errorMsg = response.data;
                            } else if (response.data.message) {
                                errorMsg = response.data.message;
                            } else {
                                errorMsg = JSON.stringify(response.data);
                            }
                        }
                        alert(errorMsg);
                        $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveCartDiscount || 'Save Cart Discount');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Cart AJAX error:', xhr, status, error);
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveCartDiscount || 'Save Cart Discount');
                }
            });
        },

        closeModal: function() {
            $('#wdd-cart-modal').fadeOut(200);
        }
    };

    $(document).ready(function() {
        WDDCart.init();
    });

})(jQuery);
