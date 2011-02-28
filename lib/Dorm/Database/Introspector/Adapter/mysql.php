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

class Dorm_Database_Introspector_Adapter_mysql
    extends Dorm_Database_Introspector_Adapter_Abstract {

    /**
     * @param Dorm_Database $database
     */
    public function loadTables($database) {
        $sql = "SHOW TABLES";
        foreach ($this->getConnection()->query($sql) as $row) {
            $table_name = $row[0];
            $table = new Dorm_Database_Table($table_name);
            $database->addTable($table);
        }
    }

    /**
     * @param Dorm_Database_Table $table
     */
    public function loadFields($table) {
        $sql = "SHOW COLUMNS FROM `{$table->getName()}`";
        foreach($this->getConnection()->query($sql) as $row) {
            $name = $row['Field'];
            $type = $row['Type'];
            $default_value = $row['Default'];
            $is_nullable = ($row['Null'] === 'YES');
            $is_auto_increment = ($row['Extra'] === 'auto_increment');

            $regex = '^(\w+)[\(]?([\d]+)?,?([\d]+)?[\)]?$';
            //          int   (   n     ,    m      )
            preg_match('|' . $regex . '|', $type, $matches);

            $data_type = $matches[1];
            $size = isset($matches[2]) ? $matches[2] : null;
            $precision = isset($matches[3]) ? $matches[3] : null;

            $field = new Dorm_Database_Table_Field($name);
            $field->setDataType($data_type);
            $field->setSize($size);
            $field->setPrecision($precision);

            $field->setDefaultValue($default_value);
            $field->setNullable($is_nullable);
            $field->setAutoIncrement($is_auto_increment);

            $table->addField($field);
        }
    }

    /**
     * @param Dorm_Database_Table $table
     */
    public function loadPrimaryKey($table) {
        $pk = new Dorm_Database_Table_PrimaryKey();

        // Get the primary key
        $sql = "SHOW KEYS FROM `" . $table->getName() . "` WHERE `Key_name` = 'PRIMARY'";

        foreach($this->getConnection()->query($sql) as $row) {
            $pk->addField($row['Column_name']);
            $table->getField($row['Column_name'])->setPrimaryKey(true);
        }
        $table->setPrimaryKey($pk);
    }

    /**
     * @todo !! Improve performance because this method his is _very_ slow (98.7% of execution time / 280 ms) !!
     *
     * @param Dorm_Database_Table $table
     */
    public function loadForeignKeys($table) {
        // Get current database name (used is SQL to get foreign keys)
        $db_name = $table->getDatabase()->getName();

        // Get name of table
        $local_table = $table->getName();

        // Get foreign key constraints using the information_schema database
        $sql_foreign_keys = "SELECT tc.`constraint_name`, tc.`constraint_type`, kcu.`column_name`,
                                    kcu.`referenced_table_name`, kcu.`referenced_column_name`
                             FROM information_schema.`table_constraints` AS tc
                             LEFT JOIN information_schema.`key_column_usage` AS kcu
                                ON tc.`table_name` = kcu.`table_name`
                                AND tc.`constraint_name` = kcu.`constraint_name`
                             WHERE tc.`table_schema` = '" . $db_name . "'
                                AND tc.`table_name` = '" . $local_table . "'
                                AND tc.`constraint_name` != 'PRIMARY'
                             ORDER BY tc.`constraint_name`";

        $result = $this->getConnection()->query($sql_foreign_keys);

        $last_name = null;
        foreach ($result as $row) {
            $name = $row['constraint_name'];

            // New constraint name (diff. from previous constraint name... SQL is ordered by constraint name)
            if ($name != $last_name) {
                $foreign_table = $table->getDatabase()->getTable($row['referenced_table_name']);

                $foreign_key = new Dorm_Database_Table_ForeignKey($name);
                $table->addForeignKey($foreign_key);
            }

            $local_field = $table->getField($row['column_name']);
            $foreign_field = $foreign_table->getField($row['referenced_column_name']);
            $foreign_key->bind($local_field, $foreign_field);
            //$local_field->bindToForeignKey($foreign_key);

            $last_name = $name;
        }
    }
}