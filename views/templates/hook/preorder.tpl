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

{assign var=context value=Context::getContext()}
<div id="jxoco-modal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
<form enctype="multipart/form-data" class="preorder-form-box">
  <div class="errors"></div>
  <fieldset>
    <div class="clearfix">
      {foreach from=$fields item=field}
        <div class="form-group">
          {if $field.type == 'name'}
            {*<label for="name" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
            <input type="text" value="{if $context->customer->firstname}{$context->customer->firstname|escape:'htmlall':'UTF-8'}{/if}" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isName" name="{$field.type|escape:'htmlall':'UTF-8'}" id="name" class="form-control grey validate {if $field.required}required{/if}">
          {elseif $field.type == 'number'}
            {*<label for="number" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
            <input type="text" value="" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isPhoneNumber" name="{$field.type|escape:'htmlall':'UTF-8'}" id="number" class="form-control grey validate {if $field.required}required{/if}">
          {elseif $field.type == 'address'}
            {*<label for="address" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
            <input type="text" value="" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isAddress" name="{$field.type|escape:'htmlall':'UTF-8'}" id="address" class="form-control grey validate {if $field.required}required{/if}">
          {elseif $field.type == 'email'}
            {*<label for="email" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
            <input type="text" value="{if $context->customer->email}{$context->customer->email|escape:'htmlall':'UTF-8'}{/if}" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isEmail" name="{$field.type|escape:'htmlall':'UTF-8'}" id="email" class="form-control grey validate {if $field.required}required{/if}">
          {elseif $field.type == 'message'}
            {*<label for="message" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
            <textarea type="text" value="" placeholder="{$field.name|escape:'htmlall':'UTF-8'}{if $field.required}*{/if}" data-validate="isMessage" name="{$field.type|escape:'htmlall':'UTF-8'}" id="message" class="form-control grey validate {if $field.required}required{/if}"/>
          {elseif $field.type == 'time'}
            {*<label for="date_from" class="control-label {if $field.required}required{/if}">{$field.name|escape:'htmlall':'UTF-8'}</label>*}
            <br>
            {*{l s='From'}*}
            <input type="text" value="" placeholder="{l s='Time from' mod='jxoneclickorder'}{if $field.required}*{/if}" class="datepicker form-control grey {if $field.required}required{/if}" id="date_from" readonly/>
            {*{l s='to'}*}
            <input type="text" value="" placeholder="{l s='Time to' mod='jxoneclickorder'}{if $field.required}*{/if}" class="datepicker form-control grey {if $field.required}required{/if}" id="date_to" readonly/>
          {elseif $field.type == 'content'}
            {*<div class="content-name">{$field.name}</div>*}
            <div class="content-description">{$field.description nofilter}</div>
          {/if}
          {if $field.type != 'content'}
            <div class="text-right"><small class="help-block">{$field.description nofilter}</small></div>
          {/if}
        </div>
      {/foreach}
    </div>
    {* PSGDPR Consent module *}
    {if isset($id_module)}
      {hook h='displayGDPRConsent' mod='psgdpr' id_module=$id_module}
    {/if}
    <div class="submit">
      <button class="button btn btn-success" id="submitPreorder" name="submitPreorder" type="submit">
        <span>{l s='Send' mod='jxoneclickorder'}</span></button>
    </div>
    <input name="global" type="hidden" value="{$global}" />
  </fieldset>
</form>
</div>
  </div></div>
</div>
