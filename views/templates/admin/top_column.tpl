{**
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
*}
<li id="one_click_order_notifs" class="dropdown">
  <a href="javascript:void(0);" class="notification dropdown-toggle notifs">
    <i class="material-icons">business_center</i>
    <span id="orders_number_wrapper" class="notifs_badge {if count($orders) == 0}hide{/if}">
      <span id="orders_notif_value">{count($orders)|escape:'htmlall':'UTF-8'}</span>
    </span>
  </a>
  <div class="dropdown-menu dropdown-menu-right notifs_dropdown">
    <div class="notifications">
      <h4>{l s='Latest Quick Orders' mod='jxoneclickorder'}</h4>
      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane active {if count($orders) == 0}empty{/if}" id="orders-notifications" role="tabpanel">
          {if !$orders|count}
            <p class="no-notification">
              {l s='No new order for now :(' mod='jxoneclickorder'}<br>
              <strong><a href="{$module_tab_link|escape:'htmlall':'UTF-8'}">{l s='Show Quick Orders page' mod='jxoneclickorder'}</a></strong>?<br>
            </p>
          {else}
            <div class="notification-elements">
              {foreach from=$orders item=order name=orders}
                {if $smarty.foreach.orders.iteration <= 6}
                  <a class="notif" href="{$module_tab_link|escape:'htmlall':'UTF-8'}&id_order={$order.id_order|escape:'htmlall':'UTF-8'}&status=new">
                    #{$order.id_order|escape:'htmlall':'UTF-8'}
                    {if isset($order.customer.name) && $order.customer.name}
                        {l s='From' mod='jxoneclickorder'}: <strong>{$order.customer.name}</strong>
                    {/if}
                    <strong class="pull-right">{convertPrice price=Cart::getTotalCart($order.id_cart|escape:'htmlall':'UTF-8')}</strong>
                  </a>
                {/if}
              {/foreach}
            </div>
          {/if}
        </div>
      </div>
    </div>
  </div>
</li>

<script>
  $(document).ready(function() {
    $('#one_click_order_notifs').insertAfter($('#notification'));

    if ($('.notification-center').length) {
      $('#one_click_order_notifs').hide();
    };
    $(document).on('click', function(e){
        $('#one_click_order_notifs').removeClass('open');
    });
  });
</script>

