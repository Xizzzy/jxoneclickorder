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
<form id="main_settings_form" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data"
      novalidate="">
    <div class="form-wrapper">
        <div class="form-group">
            <label class="control-label col-lg-3">
                {l s='Ajax order' mod='jxoneclickorder'}
            </label>
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="JXONECLICKORDER_AJAX_ORDERS" id="JXONECLICKORDER_AJAX_ORDERS_on" value="1" {if $module_settings['JXONECLICKORDER_AJAX_ORDERS']}checked="checked"{/if}>
                    <label for="JXONECLICKORDER_AJAX_ORDERS_on">{l s='Yes' mod='jxoneclickorder'}</label>
                    <input type="radio" name="JXONECLICKORDER_AJAX_ORDERS" id="JXONECLICKORDER_AJAX_ORDERS_off" value="0" {if !$module_settings['JXONECLICKORDER_AJAX_ORDERS']}checked="checked"{/if}>
                    <label for="JXONECLICKORDER_AJAX_ORDERS_off">{l s='No' mod='jxoneclickorder'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">
                {l s='Ajax order timeout' mod='jxoneclickorder'}
            </label>
            <div class="col-lg-3">
                <input type="text" name="JXONECLICKORDER_AJAX_ORDERS_TIMEOUT" value="{$module_settings['JXONECLICKORDER_AJAX_ORDERS_TIMEOUT']|escape:'htmlall':'UTF-8'}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">
                {l s='Notify the owner' mod='jxoneclickorder'}
            </label>
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="JXONECLICKORDER_NOTIFY_OWNER" id="JXONECLICKORDER_NOTIFY_OWNER_on" value="1" {if $module_settings['JXONECLICKORDER_NOTIFY_OWNER']}checked="checked"{/if}>
                    <label for="JXONECLICKORDER_NOTIFY_OWNER_on">{l s='Yes' mod='jxoneclickorder'}</label>
                    <input type="radio" name="JXONECLICKORDER_NOTIFY_OWNER" id="JXONECLICKORDER_NOTIFY_OWNER_off" value="0" {if !$module_settings['JXONECLICKORDER_NOTIFY_OWNER']}checked="checked"{/if}>
                    <label for="JXONECLICKORDER_NOTIFY_OWNER_off">{l s='No' mod='jxoneclickorder'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
    </div>
</form>
<div class="btn btn-default pull-right btn-success" id="save_module_settings">
    {l s='Save' mod='jxoneclickorder'}
</div>