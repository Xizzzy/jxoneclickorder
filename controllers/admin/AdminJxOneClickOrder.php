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

class AdminJxOneClickOrderController extends ModuleAdminController
{
    /**
     * @var Jxoneclickorder
     */
    public $module;

    /**
     * AdminJxOneClickOrderController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->module = new Jxoneclickorder();
    }

    /**
     *
     */
    public function ajaxProcessGetTemplateFieldForm()
    {
        $content = $this->module->renderTemplateFieldSettings();
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'content' => $content
            )
        ));
    }

    /**
     * Save template filed settings
     */
    public function ajaxProcessSaveTemplateField()
    {
        if (!$field = $this->module->saveTemplateField()) {
            die(json_encode(
                array(
                    'status' => false,
                    'errors' => $this->module->getErrors()
                )
            ));
        }

        $content = $this->module->renderTemplateField(get_object_vars($field));
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'msg' => $this->l('Field saved'),
                'content' => $content
            )
        ));
    }

    /**
     * Delete template field
     */
    public function ajaxProcessDeleteTemplateField()
    {
        if (!$this->module->deleteTemplateField()) {
            die(json_encode(
                array(
                    'status' => false,
                    'msg' => $this->l('Unable to remove field.')
                )
            ));
        }

        die(json_encode(
            array(
                'status' => true,
                'msg' => $this->l('Field removed')
            )
        ));
    }

    /**
     * Update template field
     */
    public function ajaxProcessUpdateTemplateFieldsPosition()
    {
        $items = Tools::getValue('fields');
        $total = count($items);
        $id_shop = (int)$this->context->shop->id;
        $success = true;

        for ($i = 1; $i <= $total; $i++) {
            $success &= Db::getInstance()->update(
                'jxoneclickorder_fields',
                array('sort_order' => $i),
                '`id_field` = '.preg_replace('/(template_field_)([0-9]+)/', '${2}', pSQL($items[$i - 1])).'
                AND `id_shop` = '.(int)$id_shop
            );
        }
        if (!$success) {
            die(json_encode(
                array(
                    'status' => false,
                    'error' => $this->l('Update Fail')
                )
            ));
        }
        die(json_encode(
            array(
                'status' => true,
                'success' => $this->l('Update Success !')
            )
        ));
    }

    /**
     * Save module settings
     */
    public function ajaxProcessSaveModuleSettings()
    {

        if (!$this->module->updateSettings()) {
            die(json_encode(
                array(
                    'status' => 'false',
                    'message' => $this->l('Unable to save settings')
                )
            ));
        }

        die(json_encode(
            array(
                'status' => 'true',
                'message' => $this->l('Settings updated')
            )
        ));
    }

    /**
     * Get order form
     */
    public function ajaxProcessGetOrderForm()
    {
        $id_order = Tools::getValue('id_order');
        $status = Tools::getValue('status');

        if (!$form = $this->module->getOrderForm($id_order, $status)) {
            die(json_encode(array('status' => 'false')));
        }
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => 'true',
                'content' => $form
            )
        ));
    }

    /**
     * Get order form for remove
     */
    public function ajaxProcessGetRemoveOrderForm()
    {
        $jxoneclickorder = new Jxoneclickorder();
        $id_order = Tools::getValue('id_order');
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'content' => $jxoneclickorder->renderRemoveOrderForm($id_order)
            )
        ));
    }

    /**
     * Update order status
     */
    public function ajaxProcessUpdateOrderStatus()
    {
        $id_order = Tools::getValue('id_order');
        $id_employee = $this->context->employee->id;
        $description = Tools::getValue('description');
        $status = Tools::getValue('status');

        if ($status == 'removed' && empty($description)) {
            die(json_encode(
                array(
                    'status' => false,
                    'errors' => $this->module->displayError($this->l('Description field is empty'))
                )
            ));
        }

        $params = array(
            'id_order' => $id_order,
            'id_employee' => $id_employee,
            'description' => $description
        );

        if (!$this->module->ordersStatusUpdate($params, $status)) {
            die(json_encode(
                array(
                    'status' => 'false',
                    'msg' => $this->l('Unable to update')
                )
            ));
        }

        $ordersSum = $this->module->checkNewOrders();
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'sum' => $ordersSum,
                'msg' => $this->l('Preorder status updated')
            )
        ));
    }

    /**
     * Remove order
     */
    public function ajaxProcessRemoveOrder()
    {
        $id_order = Tools::getValue('id_order');
        $id_employee = $this->context->employee->id;
        $description = Tools::getValue('description');

        if (empty($description)) {
            die(json_encode(
                array(
                    'status' => false,
                    'errors' => $this->module->displayError($this->l('Description field is empty'))
                )
            ));
        }

        $params = array(
            'id_order' => $id_order,
            'id_employee' => $id_employee,
            'description' => $description
        );

        if (!$this->module->ordersStatusUpdate($params, 'removed')) {
            die(json_encode(array('status' => 'false')));
        }

        $this->ajaxProcessCheckNewOrders();
    }

    /**
     * Check for new orders
     */
    public function ajaxProcessCheckNewOrders()
    {
        $ordersSum = $this->module->checkNewOrders();
        if ($ordersSum != 0) {
            if (ob_get_contents()) ob_end_clean();
            header('Content-Type: application/json');
            die(json_encode(
                array(
                    'status' => true,
                    'sum' => $ordersSum
                )
            ));
        }

        die(json_encode(array('status' => 'false')));
    }

    /**
     * Set shown new orders
     */
    public function ajaxProcessShownNewOrders()
    {
        $status = Tools::getValue('status');

        $newOrders = $this->module->repository->getOrders($status, false);
        $ordersSum = count($newOrders);
        $this->module->ordersShownStatusUpdate($newOrders);
        if ($ordersSum != 0) {
            $content = $this->module->renderNewOrders($newOrders);
            if (ob_get_contents()) ob_end_clean();
            header('Content-Type: application/json');
            die(json_encode(
                array(
                    'status' => 'true',
                    'content' => $content
                )
            ));
        }
        die(json_encode(array('status' => 'false')));
    }

    /**
     * Create customer account
     */
    public function ajaxProcessCreateCustomerAccount()
    {
        if (!(bool)Tools::getValue('random') && !$this->module->validateCustomerFields()) {
            die(json_encode(
                array(
                    'status' => false,
                    'errors' => $this->module->getErrors()
                )
            ));
        }

        $id_cart = Tools::getValue('id_cart');
        $cart = new Cart($id_cart);
        $customer = $this->module->createCustomer($cart);
        $cart->id_customer = $customer->id;

        if (!$cart->update()) {
            die(json_encode(array('status' => false)));
        } else {

        }

        die(json_encode(
            array(
                'status'      => true,
                'id_customer' => $customer->id
            )
        ));
    }

    /**
     * Update customer of order
     */
    public function ajaxProcessUpdateOrderCustomer()
    {
        $id_customer = Tools::getValue('id_customer');
        $id_order = Tools::getValue('id_order');

        $customer = $this->module->updateOrderCustomer($id_customer, $id_order);
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status'  => true,
                'success' => $this->l('Customer successfully selected.'),
                'content' => $this->module->renderCustomerInfo($customer)
            )
        ));
    }

    /**
     * Generate random password
     */
    public function ajaxProcessGenerateRandomPsw()
    {
        die(json_encode(
            array(
                'status' => 'true',
                'pswd'   => Tools::passwdGen()
            )
        ));
    }

    /**
     * Load tab
     */
    public function ajaxProcessLoadTab()
    {
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status'  => 'true',
                'content' => $this->module->renderTabContent(Tools::getValue('tab_name'))
            )
        ));
    }

    /**
     * Set customer of order
     */
    public function ajaxProcessSetCustomer()
    {
        $id_customer = Tools::getValue('id_customer');
        $id_cart = Tools::getValue('id_cart');
        $cart = new Cart($id_cart);
        $cart->id_customer = $id_customer;

        $customer = new Customer($id_customer);

        if (!$cart->update()) {
            die(json_encode(array('status' => false)));
        }

        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status'  => true,
                'content' => $this->module->renderCustomerInfo($customer)
            )
        ));
    }

    /**
     * Search for address states
     */
    public function ajaxProcessSearchStates()
    {
        $id_country = (int)Tools::getValue('id_country');
        $states = State::getStatesByIdCountry($id_country);

        if (count($states) == 0) {
            die(json_encode(array('status' => false)));
        }
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'states' => $states
            )
        ));
    }

    /**
     * Create order
     */
    public function ajaxProcessCreateOrder()
    {
        $id_cart = Tools::getValue('id_cart');
        $module_name = Tools::getValue('payment_module_name');
        $id_order_state = Tools::getValue('id_order_state');
        $errors = array();

        $preorder = new JXOneClickOrderOrders(Tools::getValue('id_preorder'));

            if (!Configuration::get('PS_CATALOG_MODE')) {
                $payment_module = Module::getInstanceByName($module_name);
            } else {
                $payment_module = new BoOrder();
            }

            $cart = new Cart((int)$id_cart);
            Context::getContext()->currency = new Currency((int)$cart->id_currency);
            Context::getContext()->customer = new Customer((int)$cart->id_customer);

            if (($bad_delivery = (bool)!Address::isCountryActiveById((int)$cart->id_address_delivery))
                || !Address::isCountryActiveById((int)$cart->id_address_invoice)) {
                if ($bad_delivery) {
                    $errors[] = $this->l('This delivery address country is not active.');
                } else {
                    $errors[] = $this->l('This invoice address country is not active.');
                }
            } else {
                $employee = new Employee((int)Context::getContext()->cookie->id_employee);
                $payment_module->validateOrder((int)$cart->id, (int)$id_order_state, $cart->getOrderTotal(true, Cart::BOTH), $payment_module->displayName, $this->l('Manual order -- Employee:').' '.Tools::substr($employee->firstname, 0, 1).'. '.$employee->lastname, array(), null, false, $cart->secure_key);
                if ($payment_module->currentOrder) {
                    $preorder->id_original_order = $payment_module->currentOrder;
                }

                $preorder->status = 'created';
                $preorder->shown = 0;
                $preorder->id_employee = $this->context->employee->id_profile;

                $preorder->save();

                die(json_encode(
                    array(
                        'status' => true,
                        'msg' => $this->l('Order created!')
                    )
                ));
            }

            if (count($errors) > 0) {
                die(json_encode(
                    array(
                        'status'=> false,
                        'errors' => $this->module->displayError($errors)
                    )
                ));
            }
    }

    /**
     * Search order
     */
    public function ajaxProcessSearchOrders()
    {
        $word = Tools::getValue('word');
        $date_from = Tools::getValue('date_from');
        $date_to = Tools::getValue('date_to');

        $orders = $this->module->repository->search($word, $date_from, $date_to);
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'content' => $this->module->renderNewOrders($orders)
            )
        ));

    }

    /**
     * Reload sub-tab
     */
    public function ajaxProcessReloadSubTab()
    {
        $sub_tab_name = Tools::getValue('status');
        $sub_tab = $this->module->sub_tabs[$sub_tab_name];
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'content' => stripcslashes($this->module->renderTab($sub_tab_name))
            )
        ));
    }

    /**
     * Create address
     */
    public function ajaxProcessCreateAddress()
    {
        $address = new AdminAddressesController();

        if (!$address->processSave()) {
            die(json_encode(
                array(
                    'status' => false,
                    'errors' => $this->module->displayError($address->errors)
                )
            ));
        }
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'id_address' => (int)$address->object->id,
                'alias' => $address->object->alias,
                'message' => $this->l('Address added successfully.')
            )
        ));

    }

    /**
     * Create preorder
     */
    public function ajaxProcessCreatePreorder()
    {
        $reload = Tools::getValue('reload');

        if (!$id_order = $this->module->createPreorder()) {
            if (ob_get_contents()) ob_end_clean();
            header('Content-Type: application/json');
            die(json_encode(
                array(
                    'status' => false,
                    'msg' => $this->l('Unable to create preorder!')
                )
            ));
        }

        if (!(bool)$reload) {
            $preorder = $this->module->repository->getOrders('new', false);

            $content = $this->module->renderNewOrders($preorder);

            $this->module->ordersShownStatusUpdate($preorder);
        } else {
            $content = $this->module->renderTab('new');
        }

        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/json');
        die(json_encode(
            array(
                'status' => true,
                'content' => $content,
                'msg' => $this->l('Preorder successfully created!')
            )
        ));

    }
}
