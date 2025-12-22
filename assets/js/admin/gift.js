/**
 * Woo Dynamic Deals - Gift Rules JavaScript
 */

(function($) {
    'use strict';

    const WDDGift = {
        categoryOptionsHtml: '', // Cache for category options
        
        init: function() {
            this.bindEvents();
            
            // Cache category options immediately on init, before any Select2 transformation
            // This ensures we have the raw HTML before modal opens
            setTimeout(function() {
                WDDGift.cacheCategoryOptions();
            }, 100); // Small delay to ensure DOM is fully loaded
        },
        
        cacheCategoryOptions: function() {
            // Cache category options BEFORE any Select2 initialization
            const $categorySelect = $('#wdd-gift-trigger-categories');
            
            if ($categorySelect.length) {
                // Get the original select element's options
                let optionsHtml = '';
                const options = $categorySelect.find('option');
                
                options.each(function(index) {
                    optionsHtml += this.outerHTML;
                });
                
                this.categoryOptionsHtml = optionsHtml;
            }
        },

        bindEvents: function() {
            // Trigger type change
            $(document).on('change', '#wdd-gift-trigger-type', function() {
                WDDGift.toggleTriggerFields($(this).val());
            });

            // Open gift modal
            $(document).on('click', '.wdd-add-rule[data-type="gift"]', function(e) {
                e.preventDefault();
                WDDGift.openModal('add');
            });

            // Edit gift rule
            $(document).on('click', '.wdd-edit-rule', function(e) {
                const $tab = $('.nav-tab-active');
                const href = $tab.attr('href');
                if (href && href.indexOf('gifts') !== -1) {
                    e.preventDefault();
                    e.stopPropagation();
                    WDDGift.openModal('edit', $(this).data('rule-id'));
                }
            });

            // Close modal - X button and backdrop
            $(document).on('click', '#wdd-gift-modal .wdd-modal-close, #wdd-gift-modal .wdd-modal-backdrop', function(e) {
                e.preventDefault();
                WDDGift.closeModal();
            });

            // Gift form submit - using delegated event binding
            $(document).on('submit', '#wdd-gift-form', function(e) {
                e.preventDefault();
                
                // Remove 'required' attribute from hidden fields BEFORE any validation
                $(this).find('input, select, textarea').each(function() {
                    const $field = $(this);
                    // Check if field itself is hidden or is inside a hidden trigger field container
                    if ($field.is(':hidden') || $field.closest('.wdd-trigger-field:hidden').length > 0) {
                        $field.removeAttr('required');
                    }
                });
                
                WDDGift.saveRule();
            });

            // Add trigger button
            $(document).on('click', '#wdd-add-trigger', function(e) {
                e.preventDefault();
                WDDGift.addTrigger();
            });

            // Remove trigger button
            $(document).on('click', '.wdd-remove-trigger', function(e) {
                e.preventDefault();
                WDDGift.removeTrigger($(this));
            });

            // Initialize Select2
            this.initSelect2();
        },

        addTrigger: function() {
            const triggerCount = $('.wdd-trigger-group').length;
            
            // Create new trigger group
            const triggerHtml = `
                <div class="wdd-trigger-group" data-trigger-index="${triggerCount}" style="margin-top: 15px; padding: 15px; border: 2px solid #e0e0e0; border-radius: 8px; background: #f9f9f9; position: relative;">
                    <button type="button" class="wdd-remove-trigger" style="position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>
                    
                    <div class="wdd-form-group">
                        <label>Trigger Type</label>
                        <select name="trigger_types[]" class="wdd-select wdd-trigger-type-select" required>
                            <option value="">Select Trigger Type</option>
                            <option value="product">Product</option>
                            <option value="category">Category</option>
                            <option value="cart_total">Cart Total</option>
                            <option value="cart_quantity">Cart Quantity</option>
                        </select>
                    </div>

                    <div class="wdd-trigger-field wdd-trigger-products" style="display: none;">
                        <div class="wdd-form-group">
                            <label>Trigger Products</label>
                            <select name="trigger_products_${triggerCount}[]" class="wdd-select wdd-product-search-additional" multiple="multiple" style="width: 100%;"></select>
                        </div>
                    </div>

                    <div class="wdd-trigger-field wdd-trigger-categories" style="display: none;">
                        <div class="wdd-form-group">
                            <label>Trigger Categories</label>
                            <select name="trigger_categories_${triggerCount}[]" class="wdd-select wdd-category-additional" multiple="multiple" style="width: 100%;">
                                ${WDDGift.categoryOptionsHtml}
                            </select>
                        </div>
                    </div>

                    <div class="wdd-trigger-field wdd-trigger-cart-total" style="display: none;">
                        <div class="wdd-form-group">
                            <label>Minimum Cart Total (€)</label>
                            <input type="number" name="trigger_amounts[]" class="wdd-input" step="0.01" min="0" placeholder="e.g., 100.00">
                        </div>
                    </div>

                    <div class="wdd-trigger-field wdd-trigger-cart-quantity" style="display: none;">
                        <div class="wdd-form-group">
                            <label>Minimum Cart Quantity</label>
                            <input type="number" name="trigger_quantities[]" class="wdd-input" min="1" placeholder="e.g., 5">
                        </div>
                    </div>
                </div>
            `;

            $('#wdd-triggers-container').append(triggerHtml);

            // Initialize Select2 for the new product search field
            const $newProductSearch = $(`.wdd-trigger-group[data-trigger-index="${triggerCount}"] .wdd-product-search-additional`);
            const $newCategorySelect = $(`.wdd-trigger-group[data-trigger-index="${triggerCount}"] .wdd-category-additional`);
            
            if (typeof $.fn.select2 !== 'undefined') {
                // Product search
                $newProductSearch.select2({
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
                    placeholder: 'Search for products...',
                    minimumInputLength: 2,
                    dropdownParent: $('#wdd-gift-modal')
                });
                
                // Category select
                $newCategorySelect.select2({
                    placeholder: 'Select categories...',
                    allowClear: true,
                    dropdownParent: $('#wdd-gift-modal')
                });
            }

            // Bind change event for the new trigger type select
            $(`.wdd-trigger-group[data-trigger-index="${triggerCount}"] .wdd-trigger-type-select`).on('change', function() {
                WDDGift.toggleAdditionalTriggerFields($(this));
            });

            // Show trigger logic radio buttons if more than one trigger
            if ($('.wdd-trigger-group').length > 0) {
                $('#wdd-trigger-logic-group').show();
            }
        },

        removeTrigger: function($btn) {
            $btn.closest('.wdd-trigger-group').remove();

            // Hide trigger logic if only main trigger remains
            if ($('.wdd-trigger-group').length === 0) {
                $('#wdd-trigger-logic-group').hide();
            }
        },

        toggleAdditionalTriggerFields: function($select) {
            const $group = $select.closest('.wdd-trigger-group');
            const triggerType = $select.val();

            $group.find('.wdd-trigger-field').hide();

            switch(triggerType) {
                case 'product':
                    $group.find('.wdd-trigger-products').show();
                    break;
                case 'category':
                    $group.find('.wdd-trigger-categories').show();
                    break;
                case 'cart_total':
                    $group.find('.wdd-trigger-cart-total').show();
                    break;
                case 'cart_quantity':
                    $group.find('.wdd-trigger-cart-quantity').show();
                    break;
            }
        },

        initSelect2: function() {
            if (typeof $.fn.select2 === 'undefined') {
                return;
            }

            // Product search for trigger products
            $('#wdd-gift-modal #wdd-gift-trigger-products').select2({
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
                dropdownParent: $('#wdd-gift-modal')
            });

            // Product search for gift products
            $('#wdd-gift-modal #wdd-gift-products').select2({
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
            dropdownParent: $('#wdd-gift-modal')
        });

        // Category select
        $('#wdd-gift-modal #wdd-gift-trigger-categories').select2({
            placeholder: wddAdmin.i18n.selectCategories || 'Select categories...',
            allowClear: true,
            dropdownParent: $('#wdd-gift-modal')
        });            // User search
            $('#wdd-gift-modal .wdd-user-search').select2({
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
                dropdownParent: $('#wdd-gift-modal')
            });
        },        toggleTriggerFields: function(triggerType) {
            // Hide all trigger fields within the main trigger section
            $('#wdd-main-trigger-section .wdd-trigger-field').hide();
            
            // Remove required attribute from all hidden fields
            $('#wdd-main-trigger-section .wdd-trigger-field').find('input, select, textarea').removeAttr('required');
            
            if (triggerType) {
                // Show the selected trigger field
                const $selectedField = $('#wdd-main-trigger-section .wdd-trigger-field[data-trigger="' + triggerType + '"]');
                $selectedField.show();
                
                // Make the visible field's inputs required (except for optional fields)
                $selectedField.find('select[multiple]').attr('required', 'required');
            }
        },

        openModal: function(mode, ruleId) {
            const $modal = $('#wdd-gift-modal');
            const $form = $('#wdd-gift-form');
            const $title = $('#wdd-gift-modal-title');

            // Cache category options before any Select2 initialization
            WDDGift.cacheCategoryOptions();

            // Reset form
            $form[0].reset();
            $('#wdd-gift-id').val('');
            
            // Destroy existing Select2 instances before resetting
            if (typeof $.fn.select2 !== 'undefined') {
                $('.wdd-product-search, .wdd-user-search, .wdd-category-select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            }
            
            $('.wdd-product-search, .wdd-user-search, .wdd-category-select').val(null);
            $('.wdd-trigger-field').hide();
            
            // Clear additional triggers
            $('#wdd-triggers-container').empty();
            $('#wdd-trigger-logic-group').hide();

            if (mode === 'edit' && ruleId) {
                $title.text(wddAdmin.i18n.editGiftRule || 'Edit Gift Rule');
                WDDGift.loadRule(ruleId);
            } else {
                $title.text(wddAdmin.i18n.addGiftRule || 'Add Gift Rule');
            }

            // Re-initialize Select2 for main trigger fields
            WDDGift.initSelect2();

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
                    rule_type: 'gift'
                },
                success: function(response) {
                    if (response.success && response.data) {
                        WDDGift.populateForm(response.data);
                    } else {
                        alert(response.data || wddAdmin.i18n.loadError || 'Failed to load rule.');
                        WDDGift.closeModal();
                    }
                },
                error: function() {
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    WDDGift.closeModal();
                }
            });
        },

        populateForm: function(rule) {
            $('#wdd-gift-id').val(rule.id);
            $('#wdd-gift-title').val(rule.title);
            $('#wdd-gift-priority').val(rule.priority);
            $('#wdd-gift-trigger-type').val(rule.trigger_type).trigger('change');
            $('#wdd-gift-max-gifts').val(rule.max_gifts_per_order || '');
            $('input[name="status"][value="' + rule.status + '"]').prop('checked', true);
            $('input[name="stop_further_rules"]').prop('checked', rule.stop_further_rules == 1);

            // Trigger values
            if (rule.trigger_amount) $('#wdd-gift-trigger-cart-total').val(rule.trigger_amount);
            if (rule.trigger_quantity) $('#wdd-gift-trigger-cart-quantity').val(rule.trigger_quantity);

            // Categories
            if (rule.trigger_categories && Array.isArray(rule.trigger_categories)) {
                const $categorySelect = $('#wdd-gift-trigger-categories');
                rule.trigger_categories.forEach(function(catId) {
                    $categorySelect.find('option[value="' + catId + '"]').prop('selected', true);
                });
                $categorySelect.trigger('change');
            }

            // Dates - extract date part only (yyyy-MM-dd), skip if empty or 0000-00-00
            if (rule.date_from && rule.date_from !== '' && rule.date_from !== 'NULL' && rule.date_from !== null && !rule.date_from.startsWith('0000-00-00')) {
                $('#wdd-gift-date-from').val(rule.date_from.split(' ')[0]);
            }
            if (rule.date_to && rule.date_to !== '' && rule.date_to !== 'NULL' && rule.date_to !== null && !rule.date_to.startsWith('0000-00-00')) {
                $('#wdd-gift-date-to').val(rule.date_to.split(' ')[0]);
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

            // Trigger Categories - add options from PHP response
            if (rule.trigger_category_options && Array.isArray(rule.trigger_category_options)) {
                const $categorySelect = $('#wdd-gift-trigger-categories');
                $categorySelect.empty();
                
                rule.trigger_category_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $categorySelect.append(newOption);
                });
                $categorySelect.trigger('change');
            }

            // Trigger Products - add options from PHP response
            if (rule.trigger_product_options && Array.isArray(rule.trigger_product_options)) {
                const $productSelect = $('#wdd-gift-trigger-products');
                $productSelect.empty();
                
                rule.trigger_product_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $productSelect.append(newOption);
                });
                $productSelect.trigger('change');
            }

            // Gift Products - add options from PHP response
            if (rule.gift_product_options && Array.isArray(rule.gift_product_options)) {
                const $giftSelect = $('#wdd-gift-products');
                $giftSelect.empty();
                
                rule.gift_product_options.forEach(function(option) {
                    var newOption = new Option(option.text, option.id, true, true);
                    $giftSelect.append(newOption);
                });
                $giftSelect.trigger('change');
            }
            
            // Restore additional triggers if they exist
            if (rule.additional_triggers) {
                let additionalTriggers;
                try {
                    additionalTriggers = typeof rule.additional_triggers === 'string' 
                        ? JSON.parse(rule.additional_triggers) 
                        : rule.additional_triggers;
                } catch(e) {
                    console.error('Failed to parse additional_triggers:', e);
                    additionalTriggers = null;
                }
                
                if (additionalTriggers && Array.isArray(additionalTriggers) && additionalTriggers.length > 0) {
                    // Set trigger logic
                    if (rule.trigger_logic) {
                        $('input[name="trigger_logic"][value="' + rule.trigger_logic + '"]').prop('checked', true);
                    }
                    
                    // Add each additional trigger
                    additionalTriggers.forEach(function(trigger) {
                        WDDGift.addTrigger();
                        
                        const $lastTrigger = $('.wdd-trigger-group').last();
                        const triggerType = trigger.type;
                        
                        // Set trigger type
                        $lastTrigger.find('.wdd-trigger-type-select').val(triggerType).trigger('change');
                        
                        // Populate trigger-specific fields
                        setTimeout(function() {
                            switch(triggerType) {
                                case 'product':
                                    if (trigger.product_options && Array.isArray(trigger.product_options)) {
                                        const $productSelect = $lastTrigger.find('.wdd-product-search');
                                        $productSelect.empty();
                                        trigger.product_options.forEach(function(option) {
                                            var newOption = new Option(option.text, option.id, true, true);
                                            $productSelect.append(newOption);
                                        });
                                        $productSelect.trigger('change');
                                    }
                                    break;
                                    
                                case 'category':
                                    if (trigger.categories && Array.isArray(trigger.categories)) {
                                        const $categorySelect = $lastTrigger.find('select[name^="trigger_categories_"]');
                                        trigger.categories.forEach(function(catId) {
                                            $categorySelect.find('option[value="' + catId + '"]').prop('selected', true);
                                        });
                                        $categorySelect.trigger('change');
                                    }
                                    break;
                                    
                                case 'cart_total':
                                    if (trigger.amount) {
                                        $lastTrigger.find('input[name="trigger_amounts[]"]').val(trigger.amount);
                                    }
                                    break;
                                    
                                case 'cart_quantity':
                                    if (trigger.quantity) {
                                        $lastTrigger.find('input[name="trigger_quantities[]"]').val(trigger.quantity);
                                    }
                                    break;
                            }
                        }, 100);
                    });
                }
            }
        },

        saveRule: function() {
            const $form = $('#wdd-gift-form');
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Collect form data
            const ruleData = {};
            const serializedData = $form.serializeArray();
            
            serializedData.forEach(function(item) {
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

            // Collect additional triggers if any exist
            const additionalTriggers = [];
            $('.wdd-trigger-group').each(function() {
                const $group = $(this);
                const triggerType = $group.find('.wdd-trigger-type-select').val();
                const index = $group.data('trigger-index');
                
                if (triggerType) {
                    const trigger = { type: triggerType };
                    
                    switch(triggerType) {
                        case 'product':
                            trigger.products = $group.find(`[name="trigger_products_${index}[]"]`).val() || [];
                            break;
                        case 'category':
                            trigger.categories = $group.find(`[name="trigger_categories_${index}[]"]`).val() || [];
                            break;
                        case 'cart_total':
                            trigger.amount = $group.find('[name="trigger_amounts[]"]').val();
                            break;
                        case 'cart_quantity':
                            trigger.quantity = $group.find('[name="trigger_quantities[]"]').val();
                            break;
                    }
                    
                    additionalTriggers.push(trigger);
                }
            });

            // Add additional triggers to rule data if any exist
            if (additionalTriggers.length > 0) {
                ruleData.additional_triggers = JSON.stringify(additionalTriggers);
                ruleData.trigger_logic = $('input[name="trigger_logic"]:checked').val() || 'and';
            }
            
            const ajaxData = {
                action: 'wdd_save_rule',
                wdd_nonce: wddAdmin.nonce,
                rule_type: ruleData.rule_type || 'gift',
                rule_data: ruleData
            };

            $.ajax({
                url: wddAdmin.ajaxUrl,
                type: 'POST',
                data: ajaxData,
                beforeSend: function() {
                    $submitBtn.prop('disabled', true).text(wddAdmin.i18n.saving || 'Saving...');
                },
                success: function(response) {
                    if (response.success) {
                        WDDGift.closeModal();
                        location.reload();
                    } else {
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
                        $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveGiftRule || 'Save Gift Rule');
                    }
                },
                error: function(xhr, status, error) {
                    alert(wddAdmin.i18n.ajaxError || 'An error occurred. Please try again.');
                    $submitBtn.prop('disabled', false).text(wddAdmin.i18n.saveGiftRule || 'Save Gift Rule');
                }
            });
        },

        closeModal: function() {
            $('#wdd-gift-modal').fadeOut(200);
        }
    };

    $(document).ready(function() {
        WDDGift.init();
    });

})(jQuery);
