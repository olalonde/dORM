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

class Dorm_Database_Table_ForeignKey {

    /**
     * @var string
     */
    private $name;

    /**
     * Bound foreign fields have the same array key
     *
     * @var array
     */
    private $localFields = array();

    /**
     * @var array
     */
    private $foreignFields = array();

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
     * @param Dorm_Database_Table_Field $local_field
     * @param Dorm_Database_Table_Field $foreign_field
     */
    public function bind($local_field, $foreign_field) {
        // make sure field is not already bound
        if (in_array($local_field, $this->localFields, true)) return;

        $this->localFields[] = $local_field;
        $this->foreignFields[] = $foreign_field;
    }

    /**
     * @param Dorm_Database_Table_Field $local_field
     * @return boolean
     */
    public function isLocalField($field) {
        return (array_search($field, $this->localFields, true) !== false);
    }

    /**
     * @return array of Dorm_Database_Table_Field
     */
    public function getLocalFields() {
        return $this->localFields;
    }

    /**
     * @return array of Dorm_Database_Table_Field
     */
    public function getForeignFields() {
        return $this->foreignFields;
    }

    /**
     * @param Dorm_Database_Table_Field $field
     * @return Dorm_Database_Table_Field $field
     */
    public function getBind($field) {
        $fields = array_merge($this->localFields, $this->foreignFields);
        $key = array_search($field, $fields, true);
        return ($key === false) ? false : $fields[$key];
    }
}