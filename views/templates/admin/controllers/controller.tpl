<div id="one-click-orders" class="row {$tab.status}">
  <div class="tab-content clearfix">
    <div class="tab-pane active">
      {if $tab.value == 'search'}
        <div class="clearfix">
          <div class="col-sm-6">
            <div class="form-group" id="search-customer-form-group">
              <div class="input-group">
                <input type="text" value="" id="orders_search">
                <span class="input-group-addon">
								<i class="icon-search"></i>
							</span>
              </div>
              <div class="help-block">{l s='Search by customer info, employee info, order id, preorder id.' mod='jxoneclickorder'}</div>
            </div>
          </div>
          <div class="col-sm-6">
            <label for="date_from" class="control-label">{l s='Date' mod='jxoneclickorder'}:</label>
            <input type="text" value="" class="datepicker form-control grey" id="date_from"/>
            -
            <input type="text" value="" class="datepicker form-control grey" id="date_to"/>
          </div>
        </div>
      {/if}
      <div class="no-orders {if count($tab.orders) > 0}hidden{/if}">
        <div>
          {l s='No orders in this tab' mod='jxoneclickorder'}
          <br>
          <a href="#" class="reload-tab" data-order-status="{$tab.status|escape:'htmlall':'UTF-8'}">
            {l s='Reload tab' mod='jxoneclickorder'}
          </a>
          {if $tab.status == 'new'}
            /
            <a href="#" class="create_preorder"
               data-reload="1">{l s='Create preorder' mod='jxoneclickorder'}</a>
          {/if}
        </div>
      </div>

      <div class="col-sm-3">
        <div class="sidebar-nav">
          <div class="navbar navbar-default" role="navigation">
            <div class="navbar-collapse collapse sidebar-navbar-collapse">
              {if $tab.status == 'new'}
                <a href="#" id="create_preorder"
                   data-reload="0">{l s='Create preorder' mod='jxoneclickorder'}</a>
              {/if}
              <a href="#" class="show-new-orders hidden"
                 data-status="{$tab.status|escape:'htmlall':'UTF-8'}">{l s='Load new orders' mod='jxoneclickorder'}
                <span></span></a>
              {if $tab.value == 'search'}
                <div class="no-results hidden">{l s='No results' mod='jxoneclickorder'}</div>
              {/if}
              <div class="order-list-wrap">
                <ul class="nav navbar-nav ps-child">
                  {include "./_partials/orders.tpl"}
                </ul>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="preorder_content">
          {if count($tab.orders) > 0}
            {include file="./layouts/{$tab.status}.tpl"}
          {/if}
        </div>
      </div>
    </div>
  </div>


