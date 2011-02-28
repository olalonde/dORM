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

/**
 * Introspects a database and returns a Dorm_Database object
 *
 * @deprecated Cache system is deprecated, since it is implemented in Dorm
 */
class Dorm_Database_Introspector {

    const CACHE_NONE = 0;
    const CACHE_APC = 1;
    const CACHE_FILESYSTEM = 2;

    /**
     * @var PDO
     */
    private $connection;

    /**
     * @var array of Dorm_Database
     */
    private $registry = array();

    /**
     * @var array of Dorm_Database_Introspector
     */
    private static $instances = array();

    /**
     * @var Dorm_Database_Introspector_Adapter_Abstract
     */
    private $adapter;

    /**
     * @var Dorm_Cache_Abstract
     */
    private $cache;

    /**
     * @var integer
     */
    private $cacheType = self::CACHE_NONE;

    /**
     * @param PDO $connection
     * @return Dorm_Database_Introspector
     */
    public static function getInstance($connection) {
        foreach (self::$instances as $instance) {
            if ($instance->getConnection() === $connection) return $instance;
        }
        $instance = new self($connection);
        self::$instances[] = $instance;
        return $instance;
    }

    /**
     * @param PDO|string $connection
     */
    private function __construct($connection) {
        $this->setConnection($connection);
    }

    /**
     * @param PDO $pdo
     */
    private function setConnection($connection) {
        $this->connection = $connection;
        $driver_name = $connection->getAttribute(PDO::ATTR_DRIVER_NAME);
        $this->setAdapter($driver_name);
    }

    /**
     * @return PDO
     */
    public function getConnection() {return $this->connection;}

    /**
     * @param Dorm_Database_Introspector_Adapter_Abstract|string $adapter
     */
    private function setAdapter($adapter) {
        if (is_string($adapter)) {
            $class_name = __CLASS__;
            $class_name .= '_Adapter_' . $adapter;
            $adapter = new $class_name();
        }
        $adapter->setConnection($this->getConnection());
        $this->adapter = $adapter;
    }

    /**
     * @return Dorm_Database_Introspector_Adapter_Abstract
     */
    private function getAdapter() {
        return $this->adapter;
    }

    /**
     * @return Dorm_Cache_Abstract
     */
    private function getCache() {
        if (isset($this->cache)) return $this->cache;

        $cache_type = $this->cacheType;

        if ($cache_type === self::CACHE_APC) {
            $this->cache = new Dorm_Cache_APC();
        }
        elseif ($cache_type === self::CACHE_FILESYSTEM) {
            $this->cache = new Dorm_Cache_Filesystem();
        }
        else {
            return;
        }

        $this->cache->setPrefix('dorm_database_');

        return $this->cache;
    }

    /**
     * @param string $database_name
     * @return Dorm_Database
     */
    public function getDatabase($database_name = null) {
        if (!isset($database_name)) {
            $database_name = Dorm_Database_Connection::getDatabaseName($this->getConnection());
        }

        // db was already loaded
        if (isset($this->registry[$database_name]))
            return $this->registry[$database_name];

        // db is in cache
        if ($this->getCache()
            && $database = $this->getCache()->fetch($database_name))
            return $database;

        // introspect db
        $database = $this->getAdapter()->getDatabase($database_name);

        // save to registry
        $this->registry[$database->getName()] = $database;

        // save to cache
        if ($this->getCache())
            $this->getCache()->store($database->getName());

        return $database;
    }
}