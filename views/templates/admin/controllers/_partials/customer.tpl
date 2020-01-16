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
<h4>{l s='Selected customer' mod='jxoneclickorder'}</h4>
<dl class="dl-horizontal">
    <dt>{l s='First name:' mod='jxoneclickorder'}</dt>
    <dd class="customer_firstname">{$customer->firstname|escape:'htmlall':'UTF-8'}</dd>
    <dt>{l s='Last name:' mod='jxoneclickorder'}</dt>
    <dd class="customer_lastname">{$customer->lastname|escape:'htmlall':'UTF-8'}</dd>
    <dt>{l s='Email:' mod='jxoneclickorder'}</dt>
    <dd>{$customer->email|escape:'htmlall':'UTF-8'}</dd>
</dl>
<input type="text" class="hidden" name="selected_customer" value="{$customer->id|escape:'htmlall':'UTF-8'}">
