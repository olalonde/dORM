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

abstract class Dorm_Database_Introspector_Adapter_Abstract {
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @return PDO
     */
    protected function getConnection() {return $this->connection;}

    /**
     * @param PDO $connection
     */
    public function setConnection($connection) {$this->connection = $connection;}

    /**
     * @param string $database_name
     * @return Dorm_Database
     */
    public function getDatabase($database_name) {
        $database = new Dorm_Database();
        $database->setName($database_name);

        $dsn = Dorm_Database_Connection::getDsn($this->getConnection());
        $database->setDsn($dsn);

        $this->loadTables($database);

        foreach ($database->getTables() as $table) {
            $this->loadFields($table);
            $this->loadPrimaryKey($table);
        }

        // important: after loading all tables
        foreach ($database->getTables() as $table) {
            $this->loadForeignKeys($table);
        }

        return $database;
    }

    /**
     * @return boolean
     */
    abstract function loadTables($database); // foreach table $database->addTable($table);

    /**
     * @param Dorm_Database_Table $table
     */
    abstract function loadFields($table);

    /**
     * @param Dorm_Database_Table $table
     */
    abstract function loadPrimaryKey($table);

    /**
     * @param Dorm_Database_Table $table
     */
    abstract function loadForeignKeys($table);
}