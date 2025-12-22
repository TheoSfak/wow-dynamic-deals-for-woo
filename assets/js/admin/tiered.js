/**
 * Woo Dynamic Deals - Tiered Pricing JavaScript
 */

(function($) {
    'use strict';

    const WDDTiered = {
        init: function() {
            this.bindEvents();
            this.initSortable();
        },

        bindEvents: function() {
            // Add tier button
            $(document).on('click', '.wdd-add-tier', function(e) {
                e.preventDefault();
                WDDTiered.addTier();
            });

            // Remove tier button
            $(document).on('click', '.wdd-remove-tier', function(e) {
                e.preventDefault();
                $(this).closest('.wdd-tier-row').fadeOut(200, function() {
                    $(this).remove();
                });
            });

            // Open tiered modal
            $(document).on('click', '.wdd-add-rule[data-type="tiered"]', function(e) {
                e.preventDefault();
                WDDTiered.openModal('add');
            });

            // Edit tiered rule
            $(document).on('click', '.wdd-edit-rule', function(e) {
                const $tab = $('.nav-tab-active');
                const href = $tab.attr('href');
                if (href && href.indexOf('tiered-pricing') !== -1) {
                    e.preventDefault();
                    e.stopPropagation();
                    WDDTiered.openModal('edit', $(this).data('rule-id'));
                }
            });

            // Close modal - X button and backdrop
            $(document).on('click', '#wdd-tiered-modal .wdd-modal-close, #wdd-tiered-modal .wdd-modal-backdrop', function(e) {
                e.preventDefault();
                WDDTiered.closeModal();
            });

            // Tiered form submit
            $('#wdd-tiered-form').on('submit', function(e) {
                e.preventDefault();
                WDDTiered.saveRule();
            });

            // Initialize Select2 for product/user/category search
            this.initSelect2();
        },

        initSelect2: function() {
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }

            $('#wdd-tiered-modal .wdd-product-search').select2({
                ajax: {
                    type: 'POST',
                    url: wddAdmin.ajaxUrl,
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var data = {
                            action: 'wdd_search_products',
                            wdd_nonce: wddAdmin.nonce,
                            search: params.term,
                            page: params.page || 1
                        };
                        console.log('Product search request:', data);
                        return data;
                    },
                    processResults: function(data) {
                        console.log('Product search response:', data);
                        var results = data.data || [];
                        console.log('Processed results:', results);
                        return { results: results };
                    },
                    cache: true
                },
                placeholder: wddAdmin.i18n.searchProducts || 'Search for products...',
                minimumInputLength: 2,
                dropdownParent: $('#wdd-tiered-modal')
            });

            $('#wdd-tiered-modal .wdd-user-search').select2({
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
                dropdownParent: $('#wdd-tiered-modal')
            });

            $('#wdd-tiered-modal .wdd-category-select').select2({
                placeholder: wddAdmin.i18n.selectCategories || 'Select categories...',
                allowClear: true,
                dropdownParent: $('#wdd-tiered-modal')
            });
        },

        initSortable: function() {
            if (typeof $.fn.sortable !== 'undefined') {
                $('#wdd-tiers-container').sortable({
                    handle: '.wdd-tier-handle',
                    placeholder: 'wdd-tier-placeholder',
                    tolerance: 'pointer'
                });
            }
        },

        addTier: function(tierData) {
            const $template = $('#wdd-tier-template');
            const $container = $('#wdd-tiers-container');
            const $tier = $($template.html());

            if (tierData) {
                $tier.find('input[name="tiers[][min_quantity]"]').val(tierData.min_quantity);
                $tier.find('input[name="tiers[][max_quantity]"]').val(tierData.max_quantity || '');
                $tier.find('select[name="tiers[][discount_type]"]').val(tierData.discount_type);
                $tier.find('input[name="tiers[][discount_value]"]').val(tierData.discount_value);
            }

            $container.append($tier);
        },

        openModal: function(mode, ruleId) {
            const $modal = $('#wdd-tiered-modal');
            const $form = $('#wdd-tiered-form');
            const $title = $('#wdd-tiered-modal-title');

            // Reset form
            $form[0].reset();
            $('#wdd-tiered-id').val('');
            $('#wdd-tiers-container').empty();
            $('.wdd-product-search, .wdd-user-search, .wdd-category-select').val(null).trigger('change');

            // Add one default tier
            if (mode === 'add') {
                WDDTiered.addTier({ min_quantity: 1, max_quantity: '', discount_type: 'percentage', discount_value: 10 });
            }

            if (mode === 'edit' && ruleId) {
                $title.text(wddAdmin.i18n.editTieredPricing || 'Edit Tiered Pricing');
                WDDTiered.loadRule(ruleId);
            } else {
                $title.text(wddAdmin.i18n.addTieredPricing || 'Add Tiered Pricing');
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
                    rule_type: 'tiered'
                },
                success: function(response) {
                    console.log('Load rule response:', response);
                    if (response.success && response.data) {
                        console.log('Rule data:', response.data);
                        console.log('Product options:', response.data.product_options);
                        console.log('Category options:', response.data.category_options);
                        WDDTiered.populateForm(response.data);
                    } else {
                        alert(response.data || wddAdmin.i18n.loadError || 'Failed to load rule.');
                        WDDTiered.closeModal();
                    }
                },
                error: function() {
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    WDDTiered.closeModal();
                }
            });
        },

        populateForm: function(rule) {
            $('#wdd-tiered-id').val(rule.id);
            $('#wdd-tiered-title').val(rule.title);
            $('#wdd-tiered-priority').val(rule.priority);
            $('input[name="calculation_mode"][value="' + rule.calculation_mode + '"]').prop('checked', true);
            $('input[name="status"][value="' + rule.status + '"]').prop('checked', true);
            $('input[name="stop_further_rules"]').prop('checked', rule.stop_further_rules == 1);

            // Dates - extract date part only (yyyy-MM-dd), skip if empty or 0000-00-00
            if (rule.date_from && rule.date_from !== '' && rule.date_from !== 'NULL' && rule.date_from !== null && !rule.date_from.startsWith('0000-00-00')) {
                $('#wdd-tiered-date-from').val(rule.date_from.split(' ')[0]);
            }
            if (rule.date_to && rule.date_to !== '' && rule.date_to !== 'NULL' && rule.date_to !== null && !rule.date_to.startsWith('0000-00-00')) {
                $('#wdd-tiered-date-to').val(rule.date_to.split(' ')[0]);
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
                console.log('Populating categories:', rule.category_options);
                const $categorySelect = $('#wdd-tiered-category-ids');
                console.log('Category select found:', $categorySelect.length);
                $categorySelect.empty(); // Clear existing
                
                rule.category_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $categorySelect.append(newOption);
                });
                $categorySelect.trigger('change');
            } else {
                console.log('No category_options in rule data');
            }

            // Products - add options from PHP response
            if (rule.product_options && Array.isArray(rule.product_options)) {
                console.log('Populating products:', rule.product_options);
                const $productSelect = $('#wdd-tiered-product-ids');
                console.log('Product select found:', $productSelect.length);
                $productSelect.empty();
                
                rule.product_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $productSelect.append(newOption);
                });
                $productSelect.trigger('change');
            }

            // Users - add options from PHP response
            if (rule.user_options && Array.isArray(rule.user_options)) {
                const $userSelect = $('#wdd-tiered-user-ids');
                $userSelect.empty();
                
                rule.user_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $userSelect.append(newOption);
                });
                $userSelect.trigger('change');
            }

            // Tiers
            $('#wdd-tiers-container').empty();
            if (rule.tiers && Array.isArray(rule.tiers)) {
                rule.tiers.forEach(function(tier) {
                    WDDTiered.addTier(tier);
                });
            }
        },

        saveRule: function() {
            const $form = $('#wdd-tiered-form');
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Collect form data
            const ruleData = {};
            const rawData = $form.serializeArray();
            
            rawData.forEach(function(item) {
                // Handle multidimensional arrays like tiers[][min_quantity]
                if (item.name.match(/^(\w+)\[\]\[(\w+)\]$/)) {
                    const matches = item.name.match(/^(\w+)\[\]\[(\w+)\]$/);
                    const arrayName = matches[1]; // e.g., 'tiers'
                    const fieldName = matches[2]; // e.g., 'min_quantity'
                    
                    if (!ruleData[arrayName]) {
                        ruleData[arrayName] = [];
                    }
                    
                    // Find the current tier object or create a new one
                    const fieldsPerTier = 4; // min_quantity, max_quantity, discount_type, discount_value
                    let lastTier = ruleData[arrayName][ruleData[arrayName].length - 1];
                    
                    if (!lastTier || Object.keys(lastTier).length >= fieldsPerTier) {
                        // Create new tier object
                        lastTier = {};
                        ruleData[arrayName].push(lastTier);
                    }
                    
                    lastTier[fieldName] = item.value;
                }
                // Remove [] from simple array field names (e.g., product_ids[] becomes product_ids)
                else if (item.name.endsWith('[]')) {
                    let fieldName = item.name.replace(/\[\]$/, '');
                    
                    if (!ruleData[fieldName]) {
                        ruleData[fieldName] = [];
                    }
                    ruleData[fieldName].push(item.value);
                } else if (ruleData[item.name]) {
                    // Duplicate non-array field - convert to array
                    if (!Array.isArray(ruleData[item.name])) {
                        ruleData[item.name] = [ruleData[item.name]];
                    }
                    ruleData[item.name].push(item.value);
                } else {
                    ruleData[item.name] = item.value;
                }
            });
            
            // Build AJAX data
            const ajaxData = {
                action: 'wdd_save_rule',
                wdd_nonce: wddAdmin.nonce,
                rule_type: ruleData.rule_type || 'tiered',
                rule_data: ruleData
            };

            console.log('Sending save request:', ajaxData);

            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: ajaxData,
                beforeSend: function() {
                    $submitBtn.prop('disabled', true).text(wddAdmin.i18n.saving || 'Saving...');
                },
                success: function(response) {
                    console.log('Save response:', response);
                    if (response.success) {
                        WDDTiered.closeModal();
                        location.reload();
                    } else {
                        console.log('Error response data:', response.data);
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
                        $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveTieredPricing || 'Save Tiered Pricing');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error:', xhr, status, error);
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveTieredPricing || 'Save Tiered Pricing');
                }
            });
        },

        closeModal: function() {
            $('#wdd-tiered-modal').fadeOut(200);
        }
    };

    $(document).ready(function() {
        WDDTiered.init();
    });

})(jQuery);
