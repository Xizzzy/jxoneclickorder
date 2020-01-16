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
 * @author    Zemez
 * @copyright 2017-2019 Zemez
 * @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

class JXOneClickOrderRepository
{
    private $db;
    private $lang;
    private $shop;
    private $db_prefix;
    private $engine = _MYSQL_ENGINE_;
    private $table = 'jxoneclickorder';

    public function __construct(Db $db, Shop $shop, Language $lang)
    {
        $this->db = $db;
        $this->shop = $shop;
        $this->lang = $lang;
        $this->db_prefix = $db->getPrefix();
    }

    public function createTables()
    {
        $success = $this->dropTables();

        $sql = array(
            "CREATE TABLE IF NOT EXISTS `{$this->db_prefix}{$this->table}` (
                `id_order` int(11) NOT NULL AUTO_INCREMENT,
                `id_shop` int(11) NOT NULL,
                `status` text NOT NULL,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                `shown` int(11) NOT NULL,
                `id_cart` int(11) NOT NULL,
                `id_employee` int(11) NOT NULL,
                `id_original_order` int(11) NOT NULL,
                `description` text NOT NULL,
                PRIMARY KEY  (`id_order`)
            ) ENGINE={$this->engine} DEFAULT CHARSET=utf8;",
            "CREATE TABLE IF NOT EXISTS `{$this->db_prefix}{$this->table}_customers` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_order` int(11) NOT NULL,
                `name` text NOT NULL,
                `number` text NOT NULL,
                `address` text NOT NULL,
                `message` text NOT NULL,
                `email` text NOT NULL,
                `datetime` text NOT NULL,
                PRIMARY KEY  (`id`)
            ) ENGINE={$this->engine} DEFAULT CHARSET=utf8;",
            "CREATE TABLE IF NOT EXISTS `{$this->db_prefix}{$this->table}_search` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_order` int(11) NOT NULL,
                `id_shop` int(11) NOT NULL,
                `id_lang` int(11) NOT NULL,
                `word` text NOT NULL,
                PRIMARY KEY  (`id`)
            ) ENGINE={$this->engine} DEFAULT CHARSET=utf8;",
            "CREATE TABLE IF NOT EXISTS `{$this->db_prefix}{$this->table}_fields` (
                `id_field` int(11) NOT NULL AUTO_INCREMENT,
                `id_shop` int(11) NOT NULL,
                `sort_order` int(11) NOT NULL,
                `type` text NOT NULL,
                `required` bool NOT NULL,
                `specific_class` text NOT NULL,
                PRIMARY KEY  (`id_field`)
            ) ENGINE={$this->engine} DEFAULT CHARSET=utf8;",
            "CREATE TABLE IF NOT EXISTS `{$this->db_prefix}{$this->table}_fields_lang` (
                `id_field` int(11) NOT NULL,
                `id_lang` int(11) NOT NULL,
                `name` text NOT NULL,
                `description` text NOT NULL
            ) ENGINE={$this->engine} DEFAULT CHARSET=utf8;",
        );

        foreach ($sql as $query) {
            $success &= $this->db->execute($query);
        }
        return (bool)$success;
    }

    public function dropTables()
    {
        $sql = "DROP TABLE IF EXISTS 			
                    `{$this->db_prefix}{$this->table}`, 			
                    `{$this->db_prefix}{$this->table}_customers`, 			
                    `{$this->db_prefix}{$this->table}_search`,
                    `{$this->db_prefix}{$this->table}_fields`, 
                    `{$this->db_prefix}{$this->table}_fields_lang`";

        return (bool)Db::getInstance()->execute($sql);
    }

    /**
     * Get all template fields of shop
     *
     * @param int $id_shop Id shop
     * @param string $field Field name id table
     * @return array|false Array of results
     */
    public function getTemplateFields($field = '*')
    {
        $sql = "SELECT {$field}
                FROM {$this->db_prefix}{$this->table}_fields jxo
                JOIN {$this->db_prefix}{$this->table}_fields_lang jxol
                ON jxo.id_shop = {$this->shop->id}
                AND jxol.id_lang = {$this->lang->id}
                AND jxo.id_field = jxol.id_field
                ORDER BY `sort_order`";

        if (!$this->checkTable('_fields') || !$result = $this->db->executeS($sql)) {
            return array();
        }

        return $result;
    }

    /**
     * Get customer of preorder
     *
     * @param int $id_order Id order
     * @return array Customer info
     */
    public function getCustomer($id_order)
    {
        $sql = "SELECT *
                FROM {$this->db_prefix}{$this->table}_customers 
                WHERE `id_order` = {$id_order}
                ORDER BY `id_order` DESC";

        if (!$result = $this->db->executeS($sql)) {
            return array();
        }

        return $result[0];
    }

    /**
     * Get max sortorder of table
     *
     * @param string $suffix
     *
     * @return array|false|int|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public function getMaxSortOrder($suffix = '')
    {
        $sql = "SELECT MAX(sort_order)
                AS sort_order
                FROM `{$this->db_prefix}{$this->table}{$suffix}`
                WHERE `id_shop` = {$this->shop->id}";

        if (!$result = $this->db->executeS($sql)) {
            return 0;
        }

        return $result;
    }

    /**
     * Get all preorders for shop
     *
     * @param int $id_shop Id shop
     * @param null $status Preorder status
     * @param bool $shown Preorder shown
     * @return array|false Array of preorders
     */
    public function getOrders($status = null, $shown = true)
    {
        $sql = "SELECT *
                FROM `{$this->db_prefix}{$this->table}`
                WHERE `id_shop` = {$this->shop->id}";

        if (!is_null($status)) {
            $sql .= " AND `status` = '{$status}'";
        }

        if (!$shown) {
            $sql .= ' AND `shown` = 0';
        }

        $sql .= ' ORDER BY `id_order` DESC;';

        if (!$this->checkTable() || !$result = $this->db->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public function checkTable($suffix = '')
    {
        $sql = 'SHOW TABLES';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        foreach ($result as $table) {
            if (in_array("{$this->db_prefix}{$this->table}{$suffix}", $table))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Add lang for field
     *
     * @param $id_lang Id lang
     * @param $filed Field
     * @return bool True if lang successfully added
     */
    public function addTemplateLang($id_lang, $filed)
    {
        $table = "{$this->table}_fields_lang";
        $module = new Jxoneclickorder();
        $data = array(
            'id_field' => (int)$filed['id_field'],
            'id_lang' => (int)$id_lang,
            'name' => pSQL($module->field_types[$filed['type']]['name']),
            'description' => pSQL($module->field_types[$filed['type']]['description'])
        );

        if (!$result = $this->db->insert($table, $data)) {
            return false;
        }

        return $result;
    }

    /**
     * Reindex preorders for search
     *
     * @param int $id_order Id order
     */
    public function reindexOrder($id_order)
    {
        $words = array();
        $this->db->delete("{$this->table}_search", '`id_order`=' . (int)$id_order);


        $order = new JXOneClickOrderOrders($id_order);
        $cart = new Cart($order->id_cart);
        if ($cart->id_customer != 0) {
            $customer = new CustomerCore($cart->id_customer);
            $words = array_merge($words, array(
                $customer->firstname,
                $customer->lastname,
                $customer->email
            ));
        }

        if ($order_info = $this->getCustomer($id_order)) {
            $words = array_merge($words, array(
                $order_info['number'],
                $order_info['email'],
                $order_info['name']
            ));
            $words = array_merge($words, Jxoneclickorder::splitString($order_info['message']));
        }

        if ($order->id_employee != 0) {
            $employee = new EmployeeCore($order->id_employee);
            $words = array_merge($words, array(
                $employee->firstname,
                $employee->lastname,
                $employee->email,
            ));
        }


        $words = array_merge($words, array(
            $order->id_order,
            $order->id_original_order,
            $order->id_cart
        ));

        $this->multiInsert($words, $id_order);
    }

    /**
     * Insert words to db table
     *
     * @param array $words Array of words
     * @param int $id_order Id order
     */
    public function multiInsert($words, $id_order)
    {
        foreach ($words as $word) {
            $this->db->insert("{$this->table}_search", array(
                'word' => pSQL($word),
                'id_order' => (int)$id_order
            ));
        }
    }

    /**
     * Search for word
     *
     * @param string $word Word
     * @param string $date_from Date from
     * @param string $date_to Date to
     * @return array|false Array of results
     */
    public function search($word = false, $date_from = false, $date_to = false)
    {
        $sql = "SELECT *
                FROM `{$this->db_prefix}{$this->table}_search` jxs
                INNER JOIN `{$this->db_prefix}{$this->table}` jxo
                ON jxo.`id_order` = jxs.`id_order` ";

        if ($word) {
            $sql .= "AND jxs.`word` LIKE '{$word}%'";
        }

        if (is_string($date_from) && !$date_to) {
            $sql .= 'WHERE jxo.`date_upd` BETWEEN \''. pSQL($date_from).'\'
                     AND \''.date('Y-m-d H:i:00').'\'';
        } elseif (!$date_from && is_string($date_to)) {
            $sql .= 'WHERE jxo.`date_upd` BETWEEN \'0-0-0 0:0:0\'
                     AND \''.pSQL($date_to).'\'';
        } elseif (!$date_from && !$date_to) {
            $sql .= 'WHERE jxo.`date_upd` BETWEEN \'0-0-0 0:0:0\'
                     AND \''.date('Y-m-d H:i:00').'\'';
        } elseif (is_string($date_from) && is_string($date_to)) {
            $sql .= 'WHERE jxo.`date_upd` BETWEEN \''.pSQL($date_from).'\'
                     AND \''.pSQL($date_to).'\'';
        }

        $sql .= ' GROUP BY jxo.`id_order`';

        if (!$result = $this->db->executeS($sql)) {
            return array();
        }

        return $result;
    }

    /**
     * Get all information related to a customer in order to show it on a request
     *
     * @param $orders id's of orders related to a customer
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getCustomerPreordersData($orders)
    {
        $sql = 'SELECT jc.`name` as "Name", jc.`number` as "Phone number", jc.`address` as "Address", jc.`message` as "Message", jc.`email` as "Email", jc.`datetime` as "Date"
                FROM '._DB_PREFIX_.'jxoneclickorder_customers jc
                LEFT JOIN '._DB_PREFIX_.'jxoneclickorder j
                ON(j.`id_order` = jc.`id_order`)
                WHERE j.`id_original_order` IN('.pSQL(implode(',', $orders)).')';

        return Db::getInstance()->executeS($sql);
    }

    /**
     * Remove all information about a customer on a request
     *
     * @param $orders id's of orders related to a customer
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    public static function removeCustomerPreordersData($orders)
    {
        $result = true;
        $entities = Db::getInstance()->executeS('SELECT jc.`id`, jc.`id_order`
          FROM '._DB_PREFIX_.'jxoneclickorder_customers jc
          LEFT JOIN '._DB_PREFIX_.'jxoneclickorder j
          ON(j.`id_order` = jc.`id_order`)
          WHERE j.`id_original_order` IN('.pSQL(implode(',', $orders)).')');

        foreach ($entities as $entity) {
            $result &= Db::getInstance()->delete('jxoneclickorder_customers', '`id` = '.(int)$entity['id']);
            $result &= Db::getInstance()->delete('jxoneclickorder', '`id_order` = '.(int)$entity['id_order']);
        }

        return $result;
    }
}