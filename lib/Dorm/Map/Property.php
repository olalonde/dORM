<?php
/**
 * This file is part of Dorm and is subject to the GNU Affero General
 * Public License Version 3 (AGPLv3). You should have received a copy
 * of the GNU Affero General Public License along with Dorm. If not,
 * see <http://www.gnu.org/licenses/>.
 *
 * @category Dorm
 * @package Dorm_Map
 * @copyright Copyright (c) 2008-2009 Olivier Lalonde <olalonde@gmail.com>
 */

/**
 * Maps class properties with Dorm_Database_Table elements
 * (fields, foreign keys, pivot tables)
 *
 */
class Dorm_Map_Property {

    const TYPE_SCALAR = 1; // property holds a simple variable (string,integer,float,etc.)
    const TYPE_OBJECT = 2; // property holds an object
    const TYPE_OBJECT_ARRAY = 3; // property holds an array of objects

    /**
     * @var integer
     */
    private $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $setter;

    /**
     * @var string
     */
    public $getter;

    /**
     * @var mixed
     */
    public $bind;

    /**
     * @var string
     */
    public $foreignClass;

    /**
     * Field that will be used as the array key in Object Arrays (many-to-many)
     *
     * @var string
     */
    public $key;

    /**
     * Used by Object Arrays to determine which foreign key maps to the parent's primary key
     *
     * @param Dorm_Database_Table_ForeignKey $parentFkeyName
     */
    public $parentFkeyName;

    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {return $this->name;}

    /**
     * @param Dorm_Database_Table_Field $field
     */
    public function bindToField($field) {
        $this->type = self::TYPE_SCALAR;
        $this->bind = $field;
    }

    /**
     * @param Dorm_Database_Table_ForeignKey $foreign_key
     * @param string $foreign_class
     */
    public function bindToForeignKey($foreign_key, $foreign_class) {
        $this->type = self::TYPE_OBJECT;
        $this->bind = $foreign_key;
        $this->foreignClass = $foreign_class;
    }

    /**
     * Bind to foreign table
     *
     * @param Dorm_Database_Table $pivot_table
     * @param string $foreign_class
     */
    public function bindToPivotTable($pivot_table, $foreign_class) {
        $this->type = self::TYPE_OBJECT_ARRAY;
        $this->bind = $pivot_table;
        $this->foreignClass = $foreign_class;
    }

    /**
     * @param Dorm_Database_Table_ForeignKey $foreign_key
     */
    public function setParentFkeyName($foreign_key_name) {
        $this->parentFkeyName = $foreign_key_name;
    }

    /**
     * @return string
     */
    public function getParentFkeyName() {return $this->parentFkeyName;}

    /**
     * Used by Object Arrays
     *
     * @param string $field_name
     */
    public function setKey($field_name) {
        $this->key = $field_name;
    }
    public function getKey() {
        if (empty($this->key)) return null;
        return $this->key;
    }

    /**
     * @return string
     */
    public function getForeignClass() {return $this->foreignClass;}

    /**
     * @return mixed
     */
    public function getBind() {return $this->bind;}

    /**
     * @return string
     */
    public function getSetter() {return $this->setter;}

    /**
     * @return string
     */
    public function setSetter($setter) {
        if (empty($setter)) return;
        $this->setter = $setter;
    }

    /**
     * @return string
     */
    public function getGetter() {return $this->getter;}


    /**
     * @return string
     */
    public function setGetter($getter) {
        if (empty($getter)) return;
        $this->getter = $getter;}

    /**
     * Assign a value to the object's property
     *
     * @param Dorm_Object $object
     * @return mixed
     */
    public function getValue($object) {
        $property_name = $this->getName();
        if (!isset($this->getter)) {
            if (property_exists($object->_, $property_name)) return $object->_->$property_name;
        }
        else {
            $getter = $this->getGetter();
            return $object->_->$getter();
        }
    }

    /**
     * Return the value of the object's property
     *
     * @param Dorm_Object $object
     * @param mixed $value
     */
    public function setValue($object, $value) {
        $property_name = $this->getName();
        if (!isset($this->setter)) {
            $object->_->$property_name = $value;
        }
        else {
            $setter = $this->getSetter();
            $object->_->$setter($value);
        }
    }

    /**
     * @return integer
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isScalar() {return ($this->type === self::TYPE_SCALAR);}

    /**
     * @return boolean
     */
    public function isObject() {return ($this->type === self::TYPE_OBJECT);}

    /**
     * @return boolean
     */
    public function isObjectArray() {return ($this->type === self::TYPE_OBJECT_ARRAY);}
}