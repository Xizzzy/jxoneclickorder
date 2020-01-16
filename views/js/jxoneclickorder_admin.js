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
var jxoco = {
  getQueryParameters : function(query) {
    var post = new Array();
    for (var i = 0; i < query.length; i++) {
      post[query[i]['name']] = query[i]['value'];
    }
    return post;
  },
  getHashParams      : function() {
    if (window.location.hash) {
      var hash       = window.location.hash.replace('#', ''),
          params     = new Array(),
          params_str = hash.split('/');
      for (var i = 0; i < params_str.length; i++) {
        var params_ar        = params_str[i].split('=');
        params[params_ar[0]] = params_ar[1];
      }
      return params;
    }
    return false;
  },
  setHashParams      : function(type, param) {
    var hash     = this.getHashParams(),
        new_hash = '';
    if (type == 'status') {
      new_hash += 'status=' + param;
      if (hash['id_order'].length) {
        new_hash + '/id_order=' + hash['id_order']
      }
    } else {
      window.location.hash = 'status=' + hash['status'] + '/id_order=' + param;
    }
  },
  checkFieldForm     : function(types_info, type_name) {
    var i              = 0,
        excluded_field = types_info[type_name]['excluded_field'];
    while (i < excluded_field.length) {
      $(excluded_field[i]).hide();
    }
  },
  checkNewOrders     : function(status) {
    var newOrdersSum  = 0,
        ajax_settings = {
          data    : {
            action : 'checkNewOrders',
            status : status
          },
          success : function(res) {
            if (res.status) {
              newOrdersSum = res.sum;
            }
          }
        },
        ajax          = new this.ajax();
    ajax.init(ajax_settings);
    if (newOrdersSum > 0) {
      return {
        sum : newOrdersSum,
      };
    }
    return false;
  },
  createPreloader    : function() {
    return $('<div>', {
      class : 'jxoco_preloader_wrap'
    }).append($('<div>', {
      class : 'jxoco_preloader'
    }).append($('<i>', {
      class : 'icon-refresh'
    })));
  },
  ajax               : function() {
    this.init    = function(options) {
      this.options = $.extend(this.options, options);
      this.request();
      return this;
    };
    this.options = {
      type     : 'POST',
      url      : jxoco_theme_url + '&ajax',
      headers  : {"cache-control" : "no-cache"},
      cache    : false,
      dataType : "json",
      async    : false,
      success  : function() {
      }
    };
    this.request = function() {
      $.ajax(this.options);
    };
  },
  fancy              : function() {
    this.init    = function(options) {
      this.options = $.extend(this.options, options);
      return this;
    };
    this.options = {
      type            : 'inline',
      autoScale       : true,
      minHeight       : 30,
      minWidth        : 900,
      padding         : 0,
      content         : '',
      showCloseButton : true,
      helpers         : {
        overlay : {
          locked : false
        }
      }
    };
    this.show    = function() {
      $.fancybox(this.options);
    };
  }
};
function updateOrderStatus(el) {
  var id_order        = el.attr('data-id-order'),
      status          = el.attr('data-order-status'),
      desc_form       = $('#order_description'),
      desc            = '',
      tab_content     = $('.tab-pane'),
      nav_item        = $('.navbar-nav a[data-id-order=' + id_order + ']', tab_content).parent('li'),
      next_nav_item   = nav_item.next(':not(.disabled)'),
      prev_nav_item   = nav_item.prev(':not(.disabled)'),
      new_tab         = $('.page-head-tabs a[data-order-status=' + status + ']'),
      new_tab_content = $('#' + status);
  if (status == 'removed' && desc_form.length) {
    desc = desc_form.val()
  }
  var ajax_settings = {
        data    : {
          action      : 'updateOrderStatus',
          status      : status,
          id_order    : id_order,
          description : desc
        },
        success : function(res) {
          if (res.status) {
            $.fancybox.close(true);
            $('.fancybox-overlay').remove();
            nav_item.remove();
            $('span.notification-counter', new_tab).text('(' + res.sum + ')');
            $('.show-new-orders span', new_tab_content).text('(' + res.sum + ')').parent().removeClass('hidden');
            showSuccessMessage(res.msg)
            if (next_nav_item.length) {
              $('a', next_nav_item).trigger('click');
            } else if (prev_nav_item.length) {
              $('a', prev_nav_item).trigger('click')
            } else {
              $('.no-orders, .no-orders+.row', tab_content).toggleClass('hidden');
            }
          } else {
            if (res.errors && status == 'removed') {
              el.parents('.panel').find('.errors').html(res.errors);
            } else {
              showErrorMessage(res.msg);
            }
          }
        }
      },
      ajax          = new jxoco.ajax();
  ajax.init(ajax_settings);
}
$(document).ready(function() {
  var $d     = $(this),
      onload = true;
  if ($('#one-click-order-settings').length) {
    var timeout_field = $('[name = JXONECLICKORDER_AJAX_ORDERS_TIMEOUT]').parents('.form-group');
    $d.on('change', 'select[name=type]', function(e) {
      var el  = $(this),
          req = $('input[name=required]').parents('.form-group');
      if (el.val() == 'content') {
        req.addClass('hidden');
      } else if (req.hasClass('hidden')) {
        req.removeClass('hidden');
      }
    });
    $d.on('click', '.add-field, .one-click-order-field .type', function() {
      var id_field = '';
      if ($(this).parent().hasClass('one-click-order-field')) {
        id_field = $(this).parent().attr('data-field-id');
      }
      var ajax_settings = {
            data    : {
              action   : 'getTemplateFieldForm',
              id_field : id_field
            },
            success : function(msg) {
              if (msg.status) {
                var fancy = new jxoco.fancy();
                fancy.init({
                  content : '<div class="bootstrap" id="content">' + msg.content + '</div>'
                }).show();
                if ($('select[name=type]').val() == 'content') {
                  $('input[name=required]').parents('.form-group').addClass('hidden');
                }
              }
            }
          },
          ajax          = new jxoco.ajax();
      ajax.init(ajax_settings);
    });
    $d.on('click', 'button[name=savefield]', function(e) {
      e.preventDefault();
      $(this).parents('form').find('textarea').each(function() {
        $(this).val(tinyMCE.get($(this).attr('name')).getContent())
      });
      var params        = $(this).parents("form").serializeArray(),
          ajax_settings = {
            data    : $.extend({
              action : 'saveTemplateField'
            }, jxoco.getQueryParameters(params)),
            success : function(res) {
              if (res.status) {
                $.fancybox.close();
                if ($('.no-fields').length) {
                  $('.no-fields').remove();
                }
                var id_field = $('input[name=id_field]').val();
                if (id_field == 0) {
                  $(res.content).appendTo($('#order_settings_template .fields'));
                  $('#order_settings_template .fields').sortable();
                } else {
                  $('[data-field-id=' + id_field + ']').replaceWith($(res.content));
                }
                showSuccessMessage(res.msg)
              } else {
                if ($('.module_error').length) {
                  $('.module_error').replaceWith($(res.errors));
                } else {
                  $(res.errors).insertAfter('.panel-heading');
                }
              }
            }
          },
          ajax          = new jxoco.ajax();
      ajax.init(ajax_settings);
    });
    $d.on('click', '.remove-field', function(e) {
      e.preventDefault();
      var el            = $(this),
          id_field      = el.parent().attr('data-field-id'),
          ajax_settings = {
            data    : {
              action   : 'deleteTemplateField',
              id_field : id_field
            },
            success : function(res) {
              if (res.status) {
                el.parent().remove();
                showSuccessMessage(res.msg)
              } else {
                showErrorMessage(res.msg);
              }
            }
          },
          ajax          = new jxoco.ajax();
      ajax.init(ajax_settings)
    });
    if ($('#JXONECLICKORDER_AJAX_ORDERS_off').attr('checked')) {
      timeout_field.hide();
    }
    $('[name = JXONECLICKORDER_AJAX_ORDERS]').on('click', function() {
      if ($('#JXONECLICKORDER_AJAX_ORDERS_off').attr('checked')) {
        timeout_field.hide();
      } else {
        timeout_field.show();
      }
    });
    $('#order_settings_template .fields').sortable({
      cursor : 'move',
    })
      .bind('sortupdate', function() {
        var fields       = $(this).sortable('toArray');
        var ajax_options = {
          data    : {
            action : 'updateTemplateFieldsPosition',
            fields : fields,
          },
          success : function(res) {
            if (res.status) {
              showSuccessMessage(res.success);
            } else {
              showErrorMessage(res.error);
            }
          }
        };
        var ajax         = new jxoco.ajax();
        ajax.init(ajax_options);
      });
    $d.on('click', '#save_module_settings', function(e) {
      e.preventDefault();
      var form_values   = $('#main_settings_form').serializeArray(),
          ajax_settings = {
            data    : $.extend({}, {
              action : 'saveModuleSettings',
            }, jxoco.getQueryParameters(form_values)),
            success : function(msg) {
              if (msg.status) {
                showSuccessMessage(msg.message);
              }
            }
          },
          ajax          = new jxoco.ajax();
      ajax.init(ajax_settings);
    });
    $d.on('click', '#save_success_message', function(e) {
      e.preventDefault();
      $(this).prev('form').find('textarea').each(function() {
        $(this).val(tinyMCE.get($(this).attr('name')).getContent())
      });
      var form_values   = $('#success_message_form').serializeArray(),
          ajax_settings = {
            data    : $.extend({}, {
              action : 'saveModuleSettings',
            }, jxoco.getQueryParameters(form_values)),
            success : function(msg) {
              if (msg.status) {
                showSuccessMessage(msg.message);
              }
            }
          },
          ajax          = new jxoco.ajax();
      ajax.init(ajax_settings);
    });
  }

  if ($('#one-click-orders').length) {
    $('.page-head-tabs a').each(function(index){
      $(this).append($('<span>'));

      switch (index) {
          case 0:
            $(this).attr('data-order-status',  'new');
            break;
          case 1:
            $(this).attr('data-order-status',  'created');
            break;
          case 2:
            $(this).attr('data-order-status',  'removed');
            break;
          case 3:
            $(this).attr('data-order-status',  'search');
            break;
      }
    });
  }

  if (typeof(JXONECLICKORDER_AJAX_ORDERS) == 'undefined') {
    JXONECLICKORDER_AJAX_ORDERS = 0;
  }
  if (Boolean(JXONECLICKORDER_AJAX_ORDERS)) {
    setInterval(
      function() {
        var newOrders = jxoco.checkNewOrders('new');
        if (newOrders) {
          if ($('#one-click-orders.new').length) {
            var btn = $('#one-click-orders.new .show-new-orders');
            btn.removeClass('hidden');
            btn.find('span').text('(' + newOrders.sum + ')');
            $('.page-head-tabs a[data-order-status=new] span').text('(' + newOrders.sum + ')');
          }
        }
      }, JXONECLICKORDER_AJAX_ORDERS_TIMEOUT
    );
  }
  $d.on('click', '.show-new-orders', function() {
    var el            = $(this),
        status        = el.attr('data-status'),
        ajax_settings = {
          data    : {
            action : 'shownNewOrders',
            status : status
          },
          success : function(res) {
            if (res.status) {
              el.addClass('hidden');
              $('.tab-pane.active .navbar-nav').prepend($(res.content));
              $('.page-head-tabs a[data-order-status=' + status + '] span').text('');
            }
          }
        },
        ajax          = new jxoco.ajax();
    ajax.init(ajax_settings);
  });
  $d.on('click', '.navbar-nav a', function(e) {
    e.preventDefault();
    var preloader   = jxoco.createPreloader(),
        contentWrap = $('.preorder_content');
    contentWrap.html('');
    contentWrap.addClass('load').append(preloader);
    var tab           = $(this),
        id_order      = tab.attr('data-id-order'),
        status        = tab.attr('data-order-status'),
        ajax_settings = {
          data    : {
            action   : 'getOrderForm',
            id_order : id_order,
            status   : status
          },
          success : function(msg) {
            if (msg.status) {
              tab.parents('.navbar-nav').find('li').removeClass('active');
              tab.parent().addClass('active');
              contentWrap.html(contentWrap.html() + msg.content);
              if (status != 'new') {
                contentWrap.removeClass('load');
                $('.jxoco_preloader_wrap').remove();
              }
            }
          }
        },
        ajax          = new jxoco.ajax();
    ajax.init(ajax_settings)
  });
  $d.on('click', '.order_header .remove-order-form', function(e) {
    e.preventDefault();
    var el            = $(this),
        id_order      = el.attr('data-id-order'),
        fancy         = new jxoco.fancy(),
        ajax_settings = {
          data    : {
            action   : 'getRemoveOrderForm',
            id_order : id_order
          },
          success : function(msg) {
            if (msg.status) {
              var fancy_settings = {
                content : msg.content
              }
              fancy.init(fancy_settings).show();
            }
          }
        },
        ajax          = new jxoco.ajax();
    ajax.init(ajax_settings)
  });
  $d.on('click', '.remove-order, .restore-order', function(e) {
    e.preventDefault();
    updateOrderStatus($(this));
  });
  $d.on('click', '#add_products, #add_products+.cancel', function(e) {
    e.preventDefault();
    $('#search_product, #add_products, #add_products+a.cancel, #cart_products', $d).toggleClass('hidden');
  });
  $d.on('click', '.other-customer, .other-customer+.cancel', function(e) {
    e.preventDefault();
    $('.create_customer, .select_customer, .selected_customer, .other-customer, .other-customer+.cancel').toggleClass('hidden');
  });
  if ($('.order-list-wrap').length) {
    $('.order-list-wrap').perfectScrollbar({
      wheelSpeed : 2
    }).scrollTop(0);
  }
  $d.on('click', '#new_address, .save-address+.cancel', function(e) {
    e.preventDefault();
    $('#address_delivery, #address_invoice').parent().toggleClass('hidden');
    $('.create_address, .save-address, #new_address, .save-address+.cancel').toggleClass('hidden');
  });
  $d.on('click', '#random_password', function(e) {
    e.preventDefault();
    var ajax_settings = {
          data    : {
            action : 'generateRandomPsw'
          },
          success : function(msg) {
            if (msg.status) {
              $('[name=passwd]').val(msg.pswd);
            }
          }
        },
        ajax          = new jxoco.ajax();
    ajax.init(ajax_settings)
  });
  $d.on('change', '#id_country', function() {
    var el            = $(this),
        id_country    = el.val(),
        state_input   = $('#id_state'),
        ajax_settings = {
          data    : {
            action     : 'searchStates',
            id_country : id_country
          },
          success : function(msg) {
            if (msg.status) {
              state_input.html('');
              state_input.parent().removeClass('hidden');
              msg.states.forEach(function(item, i, arr) {
                $('<option>', {
                  'value' : item['id_state']
                }).text(item['name']).appendTo(state_input);
              });
            } else {
              state_input.html('');
            }
          }
        },
        ajax          = new jxoco.ajax();
    ajax.init(ajax_settings);
  });
  $d.on('click', '.toggle-panel', function(e) {
    e.preventDefault();
    var el     = $(this),
        span   = $('span:not(.hidden)', this),
        parent = el.parents('.panel');
    parent.find('.panel-content').slideToggle('300');
    parent.toggleClass('closed');
    span.addClass('hidden');
    span.siblings('span').removeClass('hidden');
  });
  $d.on('click', '#new_address', function(e) {
    e.preventDefault();
    var el = $(this);
    $('#address_delivery, #address_invoice').parent().addClass('hidden');
    el.addClass('hidden');
    $('.create_address, .save-address').removeClass('hidden');
  });
  $d.on('click', '.reload-tab, .nav-link.reload-tab', function(e) {
    e.preventDefault();
    var el            = $(this),
        status        = el.attr('data-order-status'),
        preloader     = jxoco.createPreloader(),
        ajax_settings = {
          data    : {
            action : 'reloadSubTab',
            status : status
          },
          success : function(res) {
            if (res.status) {
              $('.page-head-tabs a[data-order-status=' + status + '] span').text('');
              $('#one-click-orders').replaceWith(res.content);
              if ($('.order-list-wrap').length) {
                $('.order-list-wrap').perfectScrollbar({
                  wheelSpeed : 2
                }).scrollTop(0);
              }
              if (status != 'new') {
                $('.jxoco_preloader_wrap').remove();
              }
            }
          }
        },
        ajax          = new jxoco.ajax();
    preloader.appendTo($('.tab-pane#' + status));
    ajax.init(ajax_settings);
  });
  $d.on('click', '#create_preorder, .create_preorder', function(e) {
    e.preventDefault();
    var el            = $(this),
        parent        = el.siblings('.order-list-wrap').find('.navbar-nav'),
        reload        = el.attr('data-reload'),
        ajax_settings = {
          data    : {
            action : 'createPreorder',
            reload : reload
          },
          success : function(res) {
            if (res.status) {
              if (reload == 1) {
                $('#one-click-orders').replaceWith(res.content);
              } else {
                $(res.content).insertBefore($('li', parent).first());
              }
              showSuccessMessage(res.msg);
            } else {
              showErrorMessage(res.msg);
            }
          }
        },
        ajax          = new jxoco.ajax();
    ajax.init(ajax_settings);
  });

  $d.on('click', '.page-head-tabs a', function(){
    $('.page-head-tabs a.current').removeClass('current');
    $(this).addClass('current');
  });
});