<?php
/**
 * This file is part of Dorm and is subject to the GNU Affero General
 * Public License Version 3 (AGPLv3). You should have received a copy
 * of the GNU Affero General Public License along with Dorm. If not,
 * see <http://www.gnu.org/licenses/>.
 *
 * @category Dorm
 * @package Dorm_Config
 * @copyright Copyright (c) 2008-2009 Olivier Lalonde <olalonde@gmail.com>
 */

class Dorm_Config_XML {

    private $maps;

    private $dom;

    private $path;

    /**
     * @param string $config_path Location of the XML file
     */
    public function __construct($config_path) {
        $this->path = $config_path;
    }

    private function loadMaps() {
        $this->maps = array();
        $dom = simplexml_load_file($this->path);
        // DATABASES
        foreach ($dom->map as $map_node) {
            $dsn = (string)$map_node['dsn'];
            $pdo = Dorm_Database_Connection::get($dsn);
            $database = Dorm_Database_Introspector::getInstance($pdo)->getDatabase();

            // CLASSES
            foreach ($map_node->children() as $class_node) {
                $class_name = $class_node->getName();
                $table_name = (string)$class_node['table'];

                $table = $database->getTable($table_name);
                $map = new Dorm_Map();
                $map->setTable($table);
                $map->setClassName($class_name);

                // PROPERTIES
                foreach ($class_node->children() as $property_node) {
                    $property_name = $property_node->getName();

                    $property = new Dorm_Map_Property($property_name);
                    $map->addProperty($property);

                    $property->setSetter((string)$property_node['setter']);
                    $property->setGetter((string)$property_node['getter']);

                    // Bind to field
                    if (isset($property_node['field'])) {
                        $field_name = (string)$property_node['field'];
                        $field = $table->getField($field_name);
                        $property->bindToField($field);
                    }
                    // Bind to pivot table
                    elseif (isset($property_node['pivot'])) {
                        $table_name = (string)$property_node['pivot'];
                        $foreign_class = (string)$property_node['class'];

                        $pivot_table = $database->getTable($table_name);

                        // Fkey was specified
                        if (isset($property_node['parentFkey'])) {
                            $constraint_name = (string)$property_node['parentFkey'];
                            $property->setParentFkeyName($constraint_name);
                        }

                        $property->bindToPivotTable($pivot_table, $foreign_class);
                        $property->setKey((string)$property_node['key']);
                    }
                    // Bind to foreign key
                    elseif (isset($property_node['fkey'])) {
                        $constraint_name = (string)$property_node['fkey'];
                        $foreign_class = (string)$property_node['class'];

                        $foreign_key = $table->getForeignKey($constraint_name);

                        $property->bindToForeignKey($foreign_key, $foreign_class);
                    }
                }
                $this->addMap($map);
            }

        }
    }

    private function addMap($map) {
        // save map
        $class_name = $map->getClassName();
    	$this->maps[$class_name] = $map;
    }

    public function getMaps() {
        if (!isset($this->maps)) {
            $this->loadMaps();
        }
        return $this->maps;
    }

}