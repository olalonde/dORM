<?php
/**
 * This file is part of Dorm and is subject to the GNU Affero General
 * Public License Version 3 (AGPLv3). You should have received a copy
 * of the GNU Affero General Public License along with Dorm. If not,
 * see <http://www.gnu.org/licenses/>.
 *
 * @category Dorm
 * @package Dorm_UnitOfWork
 * @copyright Copyright (c) 2008-2009 Olivier Lalonde <olalonde@gmail.com>
 */

/**
 * Keeps track of queries to execute.
 *
 * @todo
 */
class Dorm_UnitOfWork {

    public function registerNew() {}

    public function registerDirty() {}

    public function registerClean() {}

    public function registerDeleted() {}

}