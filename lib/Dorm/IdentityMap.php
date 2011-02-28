<?php
/**
 * This file is part of Dorm and is subject to the GNU Affero General
 * Public License Version 3 (AGPLv3). You should have received a copy
 * of the GNU Affero General Public License along with Dorm. If not,
 * see <http://www.gnu.org/licenses/>.
 *
 * @category Dorm
 * @package Dorm_IdentityMap
 * @copyright Copyright (c) 2008-2009 Olivier Lalonde <olalonde@gmail.com>
 */


/**
 * Keeps track of all Dorm_Object.
 *
 * Every object is registered with a unique id (table, id, db) so that we don't load objects more
 * than once from the DB.
 *
 * *** Important ***
 * "key" is used to identify objects internally in the registry
 * "id" is the id of objects in the database
 *
 * @see http://martinfowler.com/eaaCatalog/identityMap.html
 */
class Dorm_IdentityMap {

    private $registry = array();

    /**
     * Saves an object in registry
     *
     * @param Dorm_Object $object
     * @todo Make sure object is not already in registry
     *       Make a unique hash with id for faster retrieval
     */
    public function register($object) {
        $this->registry[] = $object;
    }

    /**
     * Removes object from registry
     *
     * @param Dorm_Object $object
     */
    public function unregister($object) {
        $key = array_search($object, $this->registry, true);
        unset($this->registry[$key]);
    }

    /**
     * Retrieve an object from the registry, based on its class/id or its domain object
     *
     * @param string|object $class Class or domain object
     * @param array $id
     *
     * @return Dorm_Object
     */
    public function get($class, $id = null) {
        // class/id
        if (is_array($id)) {
            foreach ($this->registry as $object)
                if ($object->class === $class && $object->id === $id) return $object;
        }
        // domain object
        else {
            $domain_object = $class;
            foreach ($this->registry as $object)
                if ($object->_ === $domain_object) return $object;
        }
        return false;
    }
}