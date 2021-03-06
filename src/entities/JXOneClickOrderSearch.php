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

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class JXOneClickOrderSearch
 */
class JXOneClickOrderSearch extends ObjectModel
{
    /**
     * @var int
     */
    public $id_order;
    /**
     * @var int
     */
    public $id_lang;
    /**
     * @var string
     */
    public $word;

    /**
     * @var array
     */
    public static $definition = array(
        'table'     => 'jxoneclickorder_search',
        'primary'   => 'id',
        'multilang' => false,
        'fields'    => array(
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_lang'  => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'word'     => array('type' => self::TYPE_STRING)
        )
    );
}
