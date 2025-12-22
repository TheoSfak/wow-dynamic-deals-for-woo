/**
 * Woo Dynamic Deals - Admin JavaScript
 */

(function($) {
    'use strict';

    const WDDAdmin = {
        init: function() {
            console.log('WDD Admin initialized', wddAdmin);
            this.bindEvents();
            this.initSelect2();
        },

        bindEvents: function() {
            // Tab switching
            $('.wdd-tab').on('click', this.switchTab);

            // Add rule button - only for price rules tab
            $(document).on('click', '.wdd-add-rule[data-type="price"]', function(e) {
                e.preventDefault();
                WDDAdmin.openModal('add');
            });

            // Edit rule button - only for price rules tab
            $(document).on('click', '.wdd-edit-rule', function(e) {
                const $tab = $('.nav-tab-active');
                const href = $tab.attr('href');
                if (href && href.indexOf('price-rules') !== -1) {
                    e.preventDefault();
                    WDDAdmin.openModal('edit', $(this).data('rule-id'));
                }
            });

            // Delete rule
            $(document).on('click', '.wdd-delete-rule', function(e) {
                e.preventDefault();
                WDDAdmin.deleteRule($(this));
            });

            // Duplicate rule
            $(document).on('click', '.wdd-duplicate-rule', function(e) {
                e.preventDefault();
                WDDAdmin.duplicateRule($(this));
            });

            // Modal close
            $(document).on('click', '.wdd-modal-close, .wdd-modal-backdrop', function(e) {
                e.preventDefault();
                WDDAdmin.closeModal();
            });

            // Form submit
            $('#wdd-rule-form').on('submit', function(e) {
                e.preventDefault();
                WDDAdmin.saveRule();
            });
        },

        switchTab: function(e) {
            e.preventDefault();
            const $tab = $(this);
            const target = $tab.data('tab');

            $('.wdd-tab').removeClass('active');
            $tab.addClass('active');

            $('.wdd-tab-content').hide();
            $('#wdd-tab-' + target).show();
        },

        initSelect2: function() {
            // Only initialize if Select2 is available
            if (typeof $.fn.select2 === 'undefined') {
                console.warn('Select2 not loaded, using native select');
                return;
            }

            // Product search
            $('.wdd-product-search').select2({
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
                        return {
                            results: data.data || []
                        };
                    },
                    cache: true
                },
                placeholder: wddAdmin.i18n.searchProducts || 'Search for products...',
                minimumInputLength: 2,
                dropdownParent: $('#wdd-rule-modal')
            });

            // User search
            $('.wdd-user-search').select2({
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
                        return {
                            results: data.data || []
                        };
                    },
                    cache: true
                },
                placeholder: wddAdmin.i18n.searchUsers || 'Search for users...',
                minimumInputLength: 2,
                dropdownParent: $('#wdd-rule-modal')
            });

            // Category select
            $('.wdd-category-select').select2({
                placeholder: wddAdmin.i18n.selectCategories || 'Select categories...',
                allowClear: true,
                dropdownParent: $('#wdd-rule-modal')
            });
        },

        openModal: function(mode, ruleId) {
            const $modal = $('#wdd-rule-modal');
            const $form = $('#wdd-rule-form');
            const $title = $('#wdd-modal-title');

            // Reset form
            $form[0].reset();
            $('#wdd-rule-id').val('');
            $('.wdd-product-search, .wdd-user-search, .wdd-category-select').val(null).trigger('change');

            if (mode === 'edit' && ruleId) {
                $title.text(wddAdmin.i18n.editRule || 'Edit Price Rule');
                WDDAdmin.loadRule(ruleId);
            } else {
                $title.text(wddAdmin.i18n.addRule || 'Add Price Rule');
                $('#wdd-rule-type').val(WDDAdmin.getRuleType());
            }

            $modal.fadeIn(200);
        },

        closeModal: function() {
            $('#wdd-rule-modal').fadeOut(200);
        },

        loadRule: function(ruleId) {
            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wdd_get_rule',
                    wdd_nonce: wddAdmin.nonce,
                    rule_id: ruleId,
                    rule_type: WDDAdmin.getRuleType()
                },
                success: function(response) {
                    if (response.success && response.data) {
                        WDDAdmin.populateForm(response.data);
                    } else {
                        alert(response.data || wddAdmin.i18n.loadError || 'Failed to load rule.');
                        WDDAdmin.closeModal();
                    }
                },
                error: function() {
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    WDDAdmin.closeModal();
                }
            });
        },

        populateForm: function(rule) {
            $('#wdd-rule-id').val(rule.id);
            $('#wdd-rule-type').val(rule.rule_type || WDDAdmin.getRuleType());
            $('#wdd-rule-title').val(rule.title);
            $('#wdd-rule-priority').val(rule.priority);
            $('#wdd-adjustment-type').val(rule.adjustment_type);
            $('#wdd-adjustment-value').val(rule.adjustment_value);
            $('input[name="apply_to"][value="' + rule.apply_to + '"]').prop('checked', true);
            $('input[name="status"][value="' + rule.status + '"]').prop('checked', true);
            $('input[name="stop_further_rules"]').prop('checked', rule.stop_further_rules == 1);

            // Dates and times - extract date part only (yyyy-MM-dd), skip if empty or 0000-00-00
            if (rule.date_from && rule.date_from !== '' && rule.date_from !== 'NULL' && rule.date_from !== null && !rule.date_from.startsWith('0000-00-00')) {
                $('#wdd-date-from').val(rule.date_from.split(' ')[0]);
            }
            if (rule.date_to && rule.date_to !== '' && rule.date_to !== 'NULL' && rule.date_to !== null && !rule.date_to.startsWith('0000-00-00')) {
                $('#wdd-date-to').val(rule.date_to.split(' ')[0]);
            }
            if (rule.time_from) $('#wdd-time-from').val(rule.time_from);
            if (rule.time_to) $('#wdd-time-to').val(rule.time_to);

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
                const $categorySelect = $('.wdd-category-select');
                $categorySelect.empty();
                
                rule.category_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $categorySelect.append(newOption);
                });
                $categorySelect.trigger('change');
            }

            // Products - add options from PHP response
            if (rule.product_options && Array.isArray(rule.product_options)) {
                const $productSelect = $('.wdd-product-search');
                $productSelect.empty();
                
                rule.product_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $productSelect.append(newOption);
                });
                $productSelect.trigger('change');
            }

            // Users - add options from PHP response
            if (rule.user_options && Array.isArray(rule.user_options)) {
                const $userSelect = $('.wdd-user-search');
                $userSelect.empty();
                
                rule.user_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $userSelect.append(newOption);
                });
                $userSelect.trigger('change');
            }
        },

        saveRule: function() {
            const $form = $('#wdd-rule-form');
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
                rule_type: ruleData.rule_type || 'price',
                rule_data: ruleData
            };

            console.log('Price save request:', ajaxData);

            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: ajaxData,
                beforeSend: function() {
                    $submitBtn.prop('disabled', true).text(wddAdmin.i18n.saving || 'Saving...');
                },
                success: function(response) {
                    console.log('Price save response:', response);
                    if (response.success) {
                        WDDAdmin.closeModal();
                        location.reload();
                    } else {
                        console.log('Price error data:', response.data);
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
                        $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveRule || 'Save Rule');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Price AJAX error:', xhr, status, error);
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveRule || 'Save Rule');
                }
            });
        },

        deleteRule: function($button) {
            const ruleId = $button.data('rule-id');
            const ruleType = WDDAdmin.getRuleType();

            if (!confirm(wddAdmin.i18n.confirmDelete || 'Are you sure you want to delete this rule?')) {
                return;
            }

            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wdd_delete_rule',
                    wdd_nonce: wddAdmin.nonce,
                    rule_id: ruleId,
                    rule_type: ruleType
                },
                beforeSend: function() {
                    $button.prop('disabled', true).text(wddAdmin.i18n.deleting || 'Deleting...');
                },
                success: function(response) {
                    if (response.success) {
                        $button.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                            WDDAdmin.checkEmptyTable();
                        });
                    } else {
                        alert(response.data || wddAdmin.i18n.deleteError || 'Failed to delete rule.');
                        $button.prop('disabled', false).text(wddAdmin.i18n.delete || 'Delete');
                    }
                },
                error: function() {
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    $button.prop('disabled', false).text(wddAdmin.i18n.delete || 'Delete');
                }
            });
        },

        duplicateRule: function($button) {
            const ruleId = $button.data('rule-id');
            const ruleType = WDDAdmin.getRuleType();

            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wdd_duplicate_rule',
                    wdd_nonce: wddAdmin.nonce,
                    rule_id: ruleId,
                    rule_type: ruleType
                },
                beforeSend: function() {
                    $button.prop('disabled', true).text(wddAdmin.i18n.duplicating || 'Duplicating...');
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data || wddAdmin.i18n.duplicateError || 'Failed to duplicate rule.');
                        $button.prop('disabled', false).text(wddAdmin.i18n.duplicate || 'Duplicate');
                    }
                },
                error: function() {
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    $button.prop('disabled', false).text(wddAdmin.i18n.duplicate || 'Duplicate');
                }
            });
        },

        getRuleType: function() {
            const $activeTab = $('.nav-tab-active');
            const href = $activeTab.attr('href');
            
            if (href) {
                const match = href.match(/tab=([^&]+)/);
                if (match && match[1]) {
                    const tab = match[1];
                    // Map tab name to rule type
                    const typeMap = {
                        'price-rules': 'price',
                        'tiered-pricing': 'tiered',
                        'cart-discounts': 'cart',
                        'gifts': 'gift'
                    };
                    return typeMap[tab] || 'price';
                }
            }
            return 'price';
        },

        checkEmptyTable: function() {
            const $table = $('.wp-list-table tbody');
            if ($table.find('tr').length === 0) {
                $table.html('<tr><td colspan="7" style="text-align:center;">' + 
                    (wddAdmin.i18n.noRules || 'No rules found.') + '</td></tr>');
            }
        }
    };

    $(document).ready(function() {
        WDDAdmin.init();
    });

})(jQuery);
