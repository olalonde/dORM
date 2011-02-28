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

class Dorm_Database_Table_Field {

    private $name;
    private $type;
    private $size;
    private $precision;
    private $defaultValue;

    private $isPrimaryKey = false;
    private $isUnique = false;
    private $isAutoIncrement = false;
    private $isNullable = false;


    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {return $this->name;}

    public function setDataType($data_type) {$this->dataType = $data_type;}
    public function getDataType() {return $this->dataType;}

    public function setSize($size) {$this->size = $size;}
    public function getSize() {return $this->size;}

    public function setPrecision($precision) {$this->precision = $precision;}
    public function getPrecision() {return $this->precision;}

    public function setDefaultValue($default_value) {$this->defaultValue = $default_value;}
    public function getDefaultValue() {return $this->defaultValue;}

    public function setPrimaryKey($bool = true) {$this->isPrimaryKey = (boolean)$bool;}
    public function isPrimaryKey() {return $this->isPrimaryKey;}

    public function setUnique($bool = true) {$this->isUnique = (boolean)$bool;}
    public function isUnique() {return $this->isUnique;}

    public function setAutoIncrement($bool = true) {$this->isAutoIncrement = (boolean)$bool;}
    public function isAutoIncrement() {return $this->isAutoIncrement;}

    public function setNullable($bool = true) {$this->isNullable = (boolean)$bool;}
    public function isNullable() {return $this->isNullable;}

}