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
{if $tab.orders && count($tab.orders) > 0}
    {foreach from=$tab.orders item=order name="order"}
        <li class="{if isset($tab.id_active_order) && $order.id_order == $tab.id_active_order}active{/if} {$order.status|escape:'htmlall':'UTF-8'}">
            <a href="#" data-id-order="{$order.id_order|escape:'htmlall':'UTF-8'}" data-order-status="{$order.status|escape:'htmlall':'UTF-8'}">
                <span class="order_id">№ {$order.id_order|escape:'htmlall':'UTF-8'}</span>
                <span class="order_date">{$order.date_add|escape:'htmlall':'UTF-8'}</span>
                <span class="total_price">{$order.total_price|escape:'htmlall':'UTF-8'}</span>
            </a>
        </li>
    {/foreach}
{/if}