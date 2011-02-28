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
 * This class is used to wrap domain objects and associate them with an ID and
 * other useful information.
 *
 * @see http://www.martinfowler.com/eaaCatalog/unitOfWork.html
 */
class Dorm_IdentityMap_Capsule {

    /**
     * Holds the object that is wrapped.
     *
     * @var object Domain object
     */
    public $_;

    /**
     * @var string
     */
    public $class;

    /**
     * @var array
     */
    public $id;

    /**
     * @param object|string $object Object or class name
     * @param array $id
     */
    public function __construct($object) {
        if (is_string($object)) {
            $class = $object;
            $object = new $object();
        }
        else {
            $class = get_class($object);
        }
        $this->_ = $object;
        $this->class = $class;
    }

    public function setId($id) {
        $this->id = $id;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Unit of work pattern
    ////////////////////////////////////////////////////////////////////////////

    // LOAD
    public $isLoaded = false;

    public function markLoaded() {
        $this->isLoaded = true;
    }
    public function markNotLoaded() {$this->isLoaded = false;}
    public function isLoaded() {return $this->isLoaded;}
    public function isNotLoaded() {return !$this->isLoaded;}

    // SAVE
    private $isNew = false;

    public function markNew() {$this->isNew = true;}
    public function markNotNew() {$this->isNew = false;}
    public function isNew() {return $this->isNew;}
    public function isNotNew() {return !$this->isNew;}

    /**
     * Holds an array of dirty columns.
     *
     * @var array
     */
    private $dirtyColumns = array();

    /**
     * Count of dirty columns.
     *
     * @var integer
     */
    private $dirtyCount = 0;



    /**
     * Marks a column dirty.
     *
     * @param string $column_name
     * @return void
     */
    public function markDirty($column_name) {
        if ($this->isDirty($column_name)) return; // Column is already dirty

        array_push($this->dirtyColumns, $column_name);
        $this->dirtyCount++;
    }

    /**
     * Returns true if column $column_name is dirty, false otherwise.
     *
     * @param string $column_name
     * @return boolean
     */
    public function isDirty($column_name) {
        return in_array($column_name, $this->dirtyColumns);
    }

    /**
     * Returns true if there is at least 1 column dirty, false otherwise.
     *
     * @return boolean
     */
    public function isGloballyDirty() {
        return ($this->dirtyCount > 0);
    }

    /**
     * Remove column name from the dirty list.
     *
     * @param string $column_name
     * @return void
     */
    public function markClean($column_name) {
        if ($this->isClean($column_name)) return; // Column is already clean

        foreach ($this->dirtyColumns as $index => $dirty_column_name) {
        	if ($column_name === $dirty_column_name) {
        	    unset($this->dirtyColumns[$index]);
        	    $this->dirtyCount--;
        	    break; // stop looping, we have found and removed the value
        	}
        }
    }

    /**
     * Returns true if column is clean, false if dirty.
     *
     * @param string $column_name
     * @return boolean
     */
    public function isClean($column_name) {
        return !$this->isDirty($column_name);
    }

    /**
     * Returns true if there is no dirty column, false otherwise.
     *
     * @return boolean
     */
    public function isGloballyClean() {
        return !$this->isGloballyDirty();
    }
}