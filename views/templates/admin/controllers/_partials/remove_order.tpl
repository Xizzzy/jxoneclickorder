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
<div class="bootstrap container" id="content">
  <div class="panel row">
    <div class="panel-heading">{l s='Remove order' mod='jxoneclickorder'}</div>
    <div class="panel-content">
      <form method="post">
        <div class="errors"></div>
        <div class="required form-group">
          <label for="order_description">{l s='Description' mod='jxoneclickorder'} <sup>*</sup></label>
          <textarea name="order_description" id="order_description"></textarea>
        </div>
      </form>
    </div>
    <div class="panel-footer clearfix">
      <button class="remove-order btn btn-default pull-right" data-order-status="removed" data-id-order="{$id_order|escape:'htmlall':'UTF-8'}">
        <i class="process-icon-delete"></i><span>{l s='Remove' mod='jxoneclickorder'}</span></button>
    </div>
  </div>
</div>