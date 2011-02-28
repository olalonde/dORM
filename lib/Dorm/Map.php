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
 * Maps a PHP class to a database table
 *
 */
class Dorm_Map {
    /**
     * @var string
     */
    public $className;

    /**
     * @var Dorm_Database_Table
     */
    public $table;

    /**
     * @var array Associative array
     */
    public $properties = array();

    /**
     * @param string $class_name
     */
    public function setClassName($class_name) {
        $this->className = $class_name;
    }

    /**
     * @return string
     */
    public function getClassName() {return $this->className;}

    /**
     * @param Dorm_Database_Table $table
     */
    public function setTable($table) {$this->table = $table;}

    /**
     * @return Dorm_Database_Table
     */
    public function getTable() {return $this->table;}

    /**
     * @return PDO
     */
    public function getConnection() {
        return $this->getTable()->getDatabase()->getConnection();
    }

    /**
     * @param Dorm_Map_Property $property
     */
    public function addProperty($property) {
        $this->properties[$property->getName()] = $property;
    }

    /**
     * @return array of Dorm_Map_Property
     */
    public function getProperties() {return $this->properties;}
}