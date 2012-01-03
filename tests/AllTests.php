<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4; */

/**
 * Test gatherer.
 *
 * PHP version 5
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package     omeka
 * @subpackage  neatlinemaps
 * @author      Scholars' Lab <>
 * @author      Bethany Nowviskie <bethany@virginia.edu>
 * @author      Adam Soroka <ajs6f@virginia.edu>
 * @author      David McClure <david.mcclure@virginia.edu>
 * @copyright   2010 The Board and Visitors of the University of Virginia
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache 2 License
 * @version     $Id$
 */

require_once 'NeatlineMaps_Test_AppTestCase.php';

class NeatlineMaps_AllTests extends PHPUnit_Framework_TestSuite
{

    public static function suite()
    {

        $suite = new NeatlineMaps_AllTests('NeatlineMaps Tests');

        $collector = new PHPUnit_Runner_IncludePathTestCollector(
            array(
                dirname(__FILE__) . '/integration',
                dirname(__FILE__) . '/unit'
            )
        );

        $suite->addTestFiles($collector->collectTests());

        return $suite;

    }

}
