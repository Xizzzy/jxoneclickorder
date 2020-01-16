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
{if isset($page) && $page == 'cart'}
  <button class="button btn btn-default button-medium add_from_cart" id="add_preorder">
    <span>
        {l s='Buy in one click' mod='jxoneclickorder'}
      <i class="icon-chevron-right right"></i>
    </span>
  </button>
{else}
  {if isset($cart.minimalPurchase) && $cart.minimalPurchase && $cart.minimalPurchase > $product.price}
    <button disabled="disabled" class="button btn btn-default button-medium" id="add_preorder">
      <span>
          {l s='Buy in one click' mod='jxoneclickorder'}
        <i class="icon-chevron-right right"></i>
      </span>
    </button>
    <div class="alert alert-warning">
      {l s='You cannot use this option to buy the product, because it\'s price is lower than the store rules require. The minimum cost should be not less than %1$d(%2$s). Please, use the default shopping cart option.' sprintf=[$cart.minimalPurchase, $currency.iso_code] mod='jxoneclickorder'}
    </div>
  {else}
    <button class="button btn btn-default button-medium" id="add_preorder"{if $configuration.is_catalog || ({$product.quantity} < {$product.minimal_quantity})} disabled="disabled"{/if}>
      <span>
          {l s='Buy in one click' mod='jxoneclickorder'}
        <i class="icon-chevron-right right"></i>
      </span>
    </button>
  {/if}
{/if}