<?php
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

class AdminJxOneClickOrderTabController extends ModuleAdminController
{
    /**
     * @var Jxoneclickorder
     */
    public $module;

    public function __construct()
    {
        $this->bootstrap = true;

        parent::__construct();

        $this->meta_title = $this->l('Quick Orders');
        $this->id_shop = $this->context->shop->id;
        $this->module = new Jxoneclickorder();
    }
}
