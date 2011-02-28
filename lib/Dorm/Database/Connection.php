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

class Dorm_Database_Connection {

    /**
     * Connections identified by their dsn
     *
     * @var array of PDO
     */
    private static $registry = array();

    /**
     * If connection is already opened, return from cache. Otherwise, open connection, save it to cache and
     * return it.
     *
     * @param string $dsn phptype(dbsyntax)://username:password@protocol+hostspec/database?option=value
     * @return PDO
     */
    public static function get($dsn) {
        if (!isset(self::$registry[$dsn])) {
            $parsedDsn = self::parseDsn($dsn);
            try {
                $pdo = new PDO($parsedDsn['dsn'], $parsedDsn['user'], $parsedDsn['pass']);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$registry[$dsn] = $pdo;
        }
        return self::$registry[$dsn];
    }

    /**
     * Maps dsn strings with their parsed dsn array
     *
     * @var array of dsn arrays. key is dsn string
     */
    private static $dsnRegistry = array();

    /**
     * Taken from Doctrine.
     *
     * @param string $dsn
     * @return array
     */
    public static function parseDsn($dsn) {
        if (isset(self::$dsnRegistry[$dsn])) return self::$dsnRegistry[$dsn];

        // fix sqlite dsn so that it will parse correctly
        $dsn = str_replace("////", "/", $dsn);
        $dsn = str_replace("\\", "/", $dsn);
        $dsn = preg_replace("/\/\/\/(.*):\//", "//$1:/", $dsn);

        // silence any warnings
        $parts = @parse_url($dsn);

        $names = array('dsn', 'scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment');

        foreach ($names as $name) {
            if ( ! isset($parts[$name])) {
                $parts[$name] = null;
            }
        }

        if (count($parts) == 0 || ! isset($parts['scheme'])) {
            throw new Exception('Could not parse dsn');
        }

        switch ($parts['scheme']) {
            case 'sqlite':
            case 'sqlite2':
            case 'sqlite3':
                if (isset($parts['host']) && $parts['host'] == ':memory') {
                    $parts['database'] = ':memory:';
                    $parts['dsn']      = 'sqlite::memory:';
                } else {
                    //fix windows dsn we have to add host: to path and set host to null
                    if (isset($parts['host'])) {
                        $parts['path'] = $parts['host'] . ":" . $parts["path"];
                        $parts['host'] = null;
                    }
                    $parts['database'] = $parts['path'];
                    $parts['dsn'] = $parts['scheme'] . ':' . $parts['path'];
                }

                break;

            case 'mssql':
            case 'dblib':
                if ( ! isset($parts['path']) || $parts['path'] == '/') {
                    throw new Exception('No database available in data source name');
                }
                if (isset($parts['path'])) {
                    $parts['database'] = substr($parts['path'], 1);
                }
                if ( ! isset($parts['host'])) {
                    throw new Exception('No hostname set in data source name');
                }

                $parts['dsn'] = $parts['scheme'] . ':host='
                              . $parts['host'] . (isset($parts['port']) ? ':' . $parts['port']:null) . ';dbname='
                              . $parts['database'];

                break;

            case 'mysql':
            case 'informix':
            case 'oci8':
            case 'oci':
            case 'firebird':
            case 'pgsql':
            case 'odbc':
            case 'mock':
            case 'oracle':
                if ( ! isset($parts['path']) || $parts['path'] == '/') {
                    throw new Exception('No database available in data source name');
                }
                if (isset($parts['path'])) {
                    $parts['database'] = substr($parts['path'], 1);
                }
                if ( ! isset($parts['host'])) {
                    throw new Exception('No hostname set in data source name');
                }

                $parts['dsn'] = $parts['scheme'] . ':host='
                              . $parts['host'] . (isset($parts['port']) ? ';port=' . $parts['port']:null) . ';dbname='
                              . $parts['database'];

                break;
            default:
                throw new Exception('Unknown driver '.$parts['scheme']);
        }

        self::$dsnRegistry[$dsn] = $parts;

        return $parts;
    }

    /**
     * @param PDO $pdo
     * @return array Dsn array
     */
    public static function getDsn($pdo) {
        $key = array_search($pdo, self::$registry, true);
        return self::$dsnRegistry[$key];
    }

    /**
     * @param PDO $pdo
     */
    public static function getDatabaseName($pdo) {
        $dsn = self::getDsn($pdo);
        return $dsn['database'];
    }

    private function __construct() {}
}