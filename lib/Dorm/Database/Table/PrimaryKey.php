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

class Dorm_Database_Table_PrimaryKey {

    /**
     * @var array
     */
    private $fields = array();

    /**
     * @return array
     */
    public function getFields() {return $this->fields;}

    /**
     * @param Dorm_Database_Table_Field $field
     */
    public function addField($field) {
        if (array_search($field, $this->getFields(), true) === false) {
            $this->fields[] = $field;
        }
    }

    /**
     * @param Dorm_Database_Table_Field $field
     */
    public function removeField($field) {
        $key = array_search($field, $this->fields, true);
        if ($key !== false) unset($this->fields[$key]);
    }

    /**
     * @param array $row
     * @return array
     */
    public function getIdFromRow($row) {
        $id = array();
        foreach ($this->getFields() as $field_name) {
            $id[$field_name] = $row[$field_name];
        }
        return $id;
    }
}