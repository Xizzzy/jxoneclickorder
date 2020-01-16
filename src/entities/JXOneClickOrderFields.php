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
 * Class JXOneClickOrderFields
 */
class JXOneClickOrderFields extends ObjectModel
{
    /**
     * @var int
     */
    public $id_field;
    /**
     * @var int
     */
    public $sort_order;
    /**
     * @var int
     */
    public $id_shop;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $type;
    /**
     * @var bool
     */
    public $required;
    /**
     * @var string
     */
    public $specific_class;

    /**
     * @var array
     */
    public static $definition = array(
        'table'     => 'jxoneclickorder_fields',
        'primary'   => 'id_field',
        'multilang' => true,
        'fields'    => array(
            'sort_order'     => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_shop'        => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isunsignedInt'),
            'name'           => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'description'    => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'type'           => array('type' => self::TYPE_STRING),
            'required'       => array('type' => self::TYPE_BOOL),
            'specific_class' => array('type' => self::TYPE_STRING)
        )
    );
}
