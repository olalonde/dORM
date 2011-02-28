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


/**
 * inspired from Sabre_Cache_Filesystem
 *
 */
class Dorm_Cache_Filesystem extends Dorm_Cache_Abstract {

    private $cacheDir;

    public function fetch($key, &$time_stored = null) {
        $filename = $this->getFileName($key);

        // file doesn't exist
        if (!file_exists($filename))
            return false;

        $fp = fopen($filename, 'r');

        // can't open file... wrong permissions
        if (!$fp) return false;

        // Getting a shared lock
        flock($fp, LOCK_SH);

        // reading file
        $data = file_get_contents($filename);

        fclose($fp);

        $data = @unserialize($data);
        if (!$data) {
            // If unserializing somehow didn't work out, delete the file
            unlink($filename);
            return false;
        }

        $expire = $data['expire'];
        $var = $data['var'];

        if (time() > $expire && $expire != 0) {
            // Unlink if the file is expired
            unlink($filename);
            return false;

        }

        $time_stored = $data['time'];

        return $var;
    }

    public function store($key, $var, $time_to_last = 0) {
        $fp = fopen($this->getFileName($key), 'a+');

        if (!$fp) throw new Exception('Could not write to cache');

        flock($fp, LOCK_EX); // exclusive lock, will get released when the file is closed
        fseek($fp, 0);

        ftruncate($fp, 0);

        if ($time_to_last === 0) {
            $expiration = 0;
        }
        else {
            $expiration = time() + $time_to_last;
        }

        $data = serialize(array('var' => $var, 'time' => time(), 'expire' => $expiration));

        if (fwrite($fp, $data) === false)
            throw new Exception('Could not write to cache');

        fclose($fp);

    }

    public function delete($key) {
        $filename = $this->getFileName($key);
        if (file_exists($filename)) {
            return unlink($filename);
        } else {
            return false;
        }
    }

    private function getFileName($key) {
        return $this->getCacheDir() . self::PREFIX . $this->getPrefix() . md5($key);
    }

    private function getCacheDir() {
        if (!isset($this->cacheDir)) {
            //$this->cacheDir = ini_get('session.save_path') . '/';
            $this->cacheDir = sys_get_temp_dir() . '/';
        }
        return $this->cacheDir;
    }

    /**
     * Flush all filesystem cache
     */
    public function flush() {
        $dh  = opendir($this->getCacheDir());
        $prefix_count = strlen(self::PREFIX);

        while (false !== ($filename = readdir($dh))) {
            if (substr($filename, 0, $prefix_count) == self::PREFIX) {
                unlink($this->cacheDir . $filename);
            }
        }

    }

}