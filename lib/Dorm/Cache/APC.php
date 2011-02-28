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


class Dorm_Cache_APC extends Dorm_Cache_Abstract {

    public function fetch($key, &$time_stored) {
        $data = apc_fetch(self::PREFIX . $this->getPrefix()  . $key);

        $var = $data['var'];
        if ($var instanceof ArrayObject)
            $var = $var->getArrayCopy();

        $time_stored = $data['time_stored'];
        return $var;
    }

    public function store($key, $var, $time_to_last = 0) {
        /*
        if (is_array($var)) {
            $var = new ArrayObject($var);
        }
        */

        $data = array('var' => $var, 'time' => time());
        $data = new ArrayObject($data);

        return apc_store(self::PREFIX . $this->getPrefix() . $key, $data, $time_to_last);
    }

    public function delete($key) {
        return apc_delete(self::PREFIX . $this->getPrefix() . $key);
    }

    public function flush() {
        apc_clear_cache('user');
        apc_clear_cache();
    }
}