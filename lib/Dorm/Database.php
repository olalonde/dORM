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

class Dorm_Database {

    private $name;

    /**
     * @var string
     */
    private $dsn;

    private $tables = array();

    public function addTable($table) {
        if (!in_array($table, $this->tables, true)) {
            $table->setDatabase($this);
            $this->tables[$table->getName()] = $table;
        }
    }

    public function removeTable($table) {
        if (isset($this->tables[$table->getName()]))
            unset($this->tables[$table->getName()]);
    }

    public function getName() {return $this->name;}
    public function setName($name) {$this->name = $name;}

    public function getDsn() {return $this->dsn;}
    public function setDsn($dsn) {$this->dsn = $dsn;}

    public function getTables() {
        return $this->tables;
    }

    /**
     * @param string $table_name
     * @return Dorm_Database_Table
     */
    public function getTable($table_name) {
        if (isset($this->tables[$table_name]))
            return $this->tables[$table_name];
    }

    public function getConnection() {
        return Dorm_PDO_Registry::get($this->dsn);
    }
}