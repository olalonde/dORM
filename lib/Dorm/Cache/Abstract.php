<?php
/**
 * This file is part of Dorm and is subject to the GNU Affero General
 * Public License Version 3 (AGPLv3). You should have received a copy
 * of the GNU Affero General Public License along with Dorm. If not,
 * see <http://www.gnu.org/licenses/>.
 *
 * @category Dorm
 * @package Dorm_Cache
 * @copyright Copyright (c) 2008-2009 Olivier Lalonde <olalonde@gmail.com>
 */

abstract class Dorm_Cache_Abstract {

    const PREFIX = 'dorm_cache_';

    private $prefix;

    public function setPrefix($prefix) {$this->prefix = $prefix;}

    public function getPrefix() {return $this->prefix;}

    /**
     * @param string $key
     * @return mixed|boolean False if can't find key.
     */
    abstract function fetch($key, &$time_stored = null);

    /**
     * @param string $key
     * @param mixed $var
     * @param int $time_to_last Seconds.
     */
    abstract function store($key, $var, $time_to_last = 0);

    /**
     * @param string $key
     */
    abstract function delete($key);

    /**
     * @return boolean
     */
    abstract function flush();
}