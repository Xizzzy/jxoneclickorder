/**
 * 2017-2019 Zemez
 *
 * JX One Click Order
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    Zemez
 *  @copyright 2017-2019 Zemez
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

jxoco = {
    getQueryParameters: function (query) {
        var post = {};
        for (var i = 0; i < query.length; i++) {
            post[query[i]['name']] = query[i]['value'];
        }
        return post;
    },
    getUrlParams: function(query) {
        var res = {};

        query.replace('?', '').split('&').forEach(function(x) {
            var param = x.split('=');

            if (param[0].indexOf('%5B') != -1) {
                var sub_param = param[0].split('%5B');
                if (res[sub_param[0]] === undefined) {
                    res[sub_param[0]] = {};
                }
                res[sub_param[0]][sub_param[1].replace('%5D', '')] = param[1];
            } else {
                res[param[0]] = param[1];
            }
        });

        return res;
    },
    ajax: function () {
        this.init = function (options) {
            this.options = $.extend(this.options, options);
            this.request();
            return this;
        };
        this.options = {
            type: 'POST',
            url: jxoneclickorderAjaxController,
            headers: {"cache-control": "no-cache"},
            cache: false,
            dataType: "json",
            async: false,
            success: function () {
            }
        };
        this.request = function () {
            $.ajax(this.options);
        };
    }
};
function checkRequiredFields() {
    $('.preorder-form-box input.required, .preorder-form-box textarea.required').each(function () {
        if ($(this).val() == '') {
            $(this).parent().addClass('form-error');
        }
    });
}
$(document).ready(function () {
    // gdpr consent form start
    $(document).on('change', '.preorder-form-box input[name="psgdpr_consent_checkbox"]', function() {
        if ($(this).is(':checked')) {
            $('#submitPreorder').attr('disabled', false);
        } else {
            $('#submitPreorder').attr('disabled', true);
        }
    });
    // gdpr consent form end
    var $d = $(this);
    $d.on('click', '#add_preorder, #add_preorder_top', function (e) {
        e.preventDefault();
        var ajax_settings = {
                url: jxoneclickorderAjaxController,
                data: {
                    action: 'getForm',
                    preorderForm: 1,
                    global: this.id == 'add_preorder_top'
                },
                success: function (msg) {
                    if (msg.status) {
                        $(document).on('show.bs.modal', '#jxoco-modal', function () {
                          $('.modal.quickview').removeClass('in');
                        });
                        $(document).on('hide.bs.modal', '#jxoco-modal', function () {
                          $('.modal.quickview').addClass('in');
                        });
                        $(document).on('hidden.bs.modal', '#jxoco-modal', function () {
                          $('#jxoco-modal').remove();
                        });
                        $('body').append(msg.form);
                        $('#jxoco-modal').modal('show');
                        // gdpr consent module
                        if ($('.preorder-form-box input[name="psgdpr_consent_checkbox"]').length) {
                            $('#submitPreorder').attr('disabled', true);
                        }
                        $(".datepicker").datetimepicker({
                            prevText: '',
                            nextText: '',
                            dateFormat: 'yy-mm-dd',
                            // Define a custom regional settings in order to use PrestaShop translation tools
                            ampm: false,
                            amNames: ['AM', 'A'],
                            pmNames: ['PM', 'P'],
                            timeFormat: 'hh:mm:ss tt',
                            timeSuffix: ''
                        });
                    }
                }
            },
            ajax = new jxoco.ajax();
        ajax.init(ajax_settings);
    });
    $d.on('change', '#date_from, #date_to', function () {
        var date_to = $('#date_to').val(),
            date_from = $('#date_from').val(),
            forms = $('#date_from, #date_to').parent();
        if (date_to != '' && date_from != '') {
            if (new Date(date_from) > new Date(date_to)) {
                forms.addClass('form-error');
            } else {
                forms.removeClass('form-error');
            }
        }
    });
    $d.on('click', 'input', function () {
        var el = $(this);
        el.parent().removeClass('form-error');
    });
    $d.on('click', '#submitPreorder', function (e) {
        e.preventDefault();
        if (!$('.preorder-form-box .form-error').length) {
            var datetime = {};
            if ($('#date_from').length) {
                datetime = {
                    date_from: $('#date_from').val(),
                    date_to: $('#date_to').val()
                };
            }

            var product = jxoco.getUrlParams($('#add-to-cart-or-refresh').serialize());
            var customer = jxoco.getQueryParameters($('.preorder-form-box').serializeArray());
            customer['datetime'] = datetime;
            var data = {
                    action: 'submitPreorder',
                    preorderSubmit: 1,
                    customer: JSON.stringify(customer),
                    page_name: prestashop.page.page_name,
                    product: JSON.stringify(product),
                    global: $('input[name="global"]').val()
                },
                ajax_settings = {
                    url: jxoneclickorderAjaxController,
                    data: data,
                    success: function (res) {
                        if (res.status) {
                            if (!res.content.length) {
                                window.location.href = prestashop.urls.base_url;
                            } else {
                                $('#jxoco-modal .modal-body').html(res.content);
                                $('#jxoco-modal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            }
                        } else if (res.errors.length) {
                            $('.preorder-form-box .errors').html(res.errors);
                        }
                    }
                },
                ajax = new jxoco.ajax();
            ajax.init(ajax_settings);
        }
    });
});