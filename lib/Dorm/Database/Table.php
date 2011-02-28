<?php
/**
 * This file is part of Dorm and is subject to the GNU Affero General
 * Public License Version 3 (AGPLv3). You should have received a copy
 * of the GNU Affero General Public License along with Dorm. If not,
 * see <http://www.gnu.org/licenses/>.
 *
 * @category Dorm
 * @package Dorm_Database
 * @copyright Copyright (c) 2008-2009 Olivier Lalonde <olalonde@gmail.com>
 */

class Dorm_Database_Table {

    /**
     * @var Dorm_Database Parent database
     */
    private $database;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array of Dorm_Database_Table_Field
     */
    public $fields = array();

    /**
     * @var Dorm_Database_Table_PrimaryKey
     */
    public $primaryKey;

    /**
     * @var array of Dorm_Database_Table_ForeignKey
     */
    public $foreignKeys = array();

    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {return $this->name;}

    /**
     * @param string $name
     */
    private function setName($name) {$this->name = $name;}

    /**
     * @param Dorm_Database $database
     */
    public function setDatabase($database) {$this->database = $database;}

    /**
     * @return Dorm_Database
     */
    public function getDatabase() {return $this->database;}

    /**
     * @param Dorm_Database_Table_Field $field
     */
    public function addField($field) {
        if (!in_array($field, $this->fields, true)) {
            $this->fields[$field->getName()] = $field;
        }
    }

    /**
     * @param string $field_name
     */
    public function getField($field_name) {
        return $this->fields[$field_name];
    }

    /**
     * @return array of Dorm_Database_Table_Field
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @param Dorm_Database_Table_ForeignKey $foreign_key
     */
    public function addForeignKey($foreign_key) {
        if (!in_array($foreign_key, $this->foreignKeys))
            $this->foreignKeys[$foreign_key->getName()] = $foreign_key;
    }

    /**
     * @return string
     */
    public function getForeignKey($foreign_key_name) {
        if (isset($this->foreignKeys[$foreign_key_name]))
            return $this->foreignKeys[$foreign_key_name];
    }

    /**
     * @return array of Dorm_Database_ForeignKey
     */
    public function getForeignKeys() {
        return $this->foreignKeys;
    }

    /**
     * @param Dorm_Database_Table_PrimaryKey $primary_key
     */
    public function setPrimaryKey($primary_key) {
        $this->primaryKey = $primary_key;
    }

    /**
     * @return Dorm_Database_Table_PrimaryKey
     */
    public function getPrimaryKey() {
        return $this->primaryKey;
    }
}