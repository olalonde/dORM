<?php
/**
 * This file is part of Dorm and is subject to the GNU Affero General
 * Public License Version 3 (AGPLv3). You should have received a copy
 * of the GNU General Public License along with Dorm. If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * @category Dorm
 * @package Dorm
 * @copyright Copyright (c) 2008-2009 Olivier Lalonde <olalonde@gmail.com>
 */

/**
 * Dorm is an ORM (object relational mapper) for PHP.
 *
 * "object" is ALWAYS used for Dorm_Object instances
 * "domain_object" is ALWAYS used for domain objects
 */
class Dorm {
    /**
     * @var Dorm_IdentityMap
     */
    private $identityMap;

    /**
     * One map by class.
     *
     * @var array of Dorm_Map (key = class name)
     */
    private $maps = array();

    /**
     * One data mapper by class.
     *
     * @var array of Dorm_Datamapper_Abstract
     */
    private $dataMappers = array();

    /**
     * Path to configuration file.
     *
     * @var string
     */
    private $configPath;

    /**
     * @param string $config_path Path to XML config file.
     */
    public function __construct($config_path = null) {
        if (!$config_path)
            throw new Exception('At the moment, dORM requires a configuration file. See http://www.getdorm.com/documentation/guide.');
        $this->configPath = realpath($config_path);

        if (!$this->loadFromCache()) {
            $this->loadFromConfig();
        }
    }

    /**
     * Parse config file and assign map array to $this->maps
     */
    private function loadFromConfig() {
        // load Maps from XML file
        $config = new Dorm_Config_XML($this->configPath);
        $this->maps = $config->getMaps();
    }

    /**
     * We need to do this in order to always have consistent IDs
     *
     * @param mixed $id
     * @param string $class
     * @todo this should move in Dorm_Object
     */
    private function normalizeId($class, $id) {
        if (!is_array($id)) $id = array($id);

        $normalized_id = array();

        $map = $this->getMap($class);

        $pkey_fields = $map->getTable()->getPrimaryKey()->getFields();

        $i=0;
        foreach ($pkey_fields as $field) {
            /* @var $field Dorm_Database_Table_Field */
            $normalized_id[$field->getName()] = $id[$i++];
        }
        return $normalized_id;
    }

    ////////////////////////////////////////////////////////////////////////////
    // INTERFACE
    ////////////////////////////////////////////////////////////////////////////

    /**
     * @param string $class
     * @param mixed $id
     * @return Dorm_Placeholder_Object
     */
    public function get($class, $id = null) {
        if (isset($id)) $id = $this->normalizeId($class, $id);

    }

    /**
     * @param string $class
     */
    public function getCollection($class) {}

    /**
     * Insert/update domain object to the database.
     *
     * @param object $domain_object
     */
    public function save($domain_object) {
        $object = $this->getDormObject($domain_object);

        if ($object->isNew()) {
            $this->getDataMapper($object)->insert($object);
        }
        else {
            $this->getDataMapper($object)->update($object);
        }
    }

    /**
     * @param string|Dorm_Object $class
     * @return Dorm_DataMapper_Abstract
     * @todo Allow users to override the default data mapper (i.e. ClassName_DataMapper could automatically override) if it exists.
     */
    public function getDataMapper($class) {
        // $class is a Dorm_Object, we need to find the domain object it encapsulates
        if (!is_string($class)) $class = $class->class;

        // DataMapper is cached
        if (isset($this->dataMappers[$class])) return $this->dataMappers[$class];

        // Class doesn't exist
        if (!class_exists($class)) throw new Exception('This class does not exist.');

        // First time we use this datamapper, set it up
        $map = $this->getMap($class);
        $data_mapper_class = 'Dorm_DataMapper';
        $data_mapper = new $data_mapper_class($this); /* @var $data_mapper Dorm_DataMapper_Abstract */
        $data_mapper->setMap($map);

        // Cache it
        $this->dataMappers[$class] = $data_mapper;

        return $data_mapper;
    }

    /**
     * Get Dorm_Map for a specific class.
     *
     * @param string|Dorm_Object $class
     * @return Dorm_Map
     */
    public function getMap($class) {
        if (!is_string($class)) $class = $class->class; // $class is a Dorm_Object
        return $this->maps[$class];
    }

    /**
     * @param object $domain_object
     * @return array
     */
    public function getId($domain_object) {
        $object = $this->getDormObject($domain_object);
        return $object->id;
    }

    /**
     * @return Dorm_IdentityMap
     */
    public function getIdentityMap() {
        if (!isset($this->identityMap)) $this->identityMap = new Dorm_IdentityMap();
        return $this->identityMap;
    }

    /**
     * @todo Remove $object from identityMap after deleting
     */
    public function delete($domain_object) {
        $object = $this->getDormObject($domain_object);

        if ($object->isNew()) throw new Exception('This object is not even in the DB, why do you want to delete it ?');

        $this->getDataMapper($object)->delete($object);
        $this->getIdentityMap()->unregister($object);
    }

    /**
     * @todo
     */
    public function setDataMapperClass($object_class, $data_mapper_class) {
        if (!class_exists($data_mapper_class))
            throw new Exception('Data mapper ' . $data_mapper_class . ' doesn\'t exist.');

        $this->customDataMapperClasses[$object_class] = $data_mapper_class;
    }

    /**
     * Returns the connection of the first map
     *
     * @return PDO
     */
    public function getConnection() {
        $map = reset($this->maps);
        return $map->getConnection();
    }

    /**
     * @return boolean
     */
    public function flushCache() {
        if ($this->getCache())
            return $this->getCache()->flush();
    }


    ////////////////////////////////////////////////////////////////////////////
    // CACHE
    ////////////////////////////////////////////////////////////////////////////

    const CACHE_NONE = 1; // no caching
    const CACHE_APC = 2; // APC
    const CACHE_FILESYSTEM = 3; // serialize to file system

    /**
     * Default cache system.
     *
     * @var integer
     */
    public static $cacheType = self::CACHE_FILESYSTEM;

    /**
     * @var Dorm_Cache_Abstract
     */
    private $cache;

    /**
     * @var boolean
     */
    private $isLoadedFromCache = false;

    /**
     * Save everything we can to cache.
     */
    private function saveToCache() {
        if (!$this->isLoadedFromCache) // dont save if its already there !
            $this->getCache()->store('maps', $this->maps);
    }

    /**
     * @return boolean True if loaded, false if not loaded
     */
    private function loadFromCache() {
        if (!$this->getCache()) return false; // cache disabled

        // load Maps from cache
        $maps = $this->getCache()->fetch('maps', $time_stored);

        if (!$maps) return false; // nothing in cache
        if (filemtime($this->configPath) > $time_stored) return false; // config file was modified since last cache

        $this->maps = $maps;
        $this->isLoadedFromCache = true;
        return true;
    }

    /**
     * @return Dorm_Cache_Abstract
     */
    public function getCache() {
        if (!isset($this->cache)) {
            switch (self::$cacheType) {
                case self::CACHE_APC:
                    $cache = new Dorm_Cache_APC();
                    break;
                case self::CACHE_FILESYSTEM:
                    $cache = new Dorm_Cache_Filesystem();
                    break;
                default:
                    return false;
            }

            // unique prefix for each config file
            $cache_prefix = md5($this->configPath);
            $cache->setPrefix($cache_prefix);
            $this->cache = $cache;
        }
        return $this->cache;
    }


    ////////////////////////////////////////////////////////////////////////////
    // MAGIC AND OVERLOAD METHODS
    ////////////////////////////////////////////////////////////////////////////

    /**
     * $dorm->getClassName(id) => $dorm->get('ClassName', id);
     * $dorm->getClassNameCollection() => $dorm->getCollection('ClassName');
     */
    public function __call($method_name, $arguments) {
        $prefix = substr($method_name, 0, 3);
        $suffix = substr($method_name, 3);
        if ($prefix === 'get') {
            // $dorm->getClassnameCollection($where, $limit);
            if (substr($suffix, -10) === 'Collection') {
                $class = substr($suffix, 0, -10);
                return $this->getCollection($class);
            }
            // $dorm->getClassname($id);
            else {
                if (!isset($arguments[0])) $arguments[0] = null;
                return $this->get($suffix, $arguments[0]);
            }
        }
    }

    public function __destruct() {
        $this->saveToCache();
    }
}