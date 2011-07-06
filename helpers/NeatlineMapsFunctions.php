<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4; */

/**
 * Procedural helper functions.
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
?>

<?php

/**
 * Include the GeoServer .js and .css dependencies in the public theme header.
 *
 * @return void.
 */
function _doJsAndCss()
{

    ?>

    <!-- Neatline Maps Dependencies -->
    <link rel="stylesheet" href="<?php echo css('show'); ?>" />
    <script type="text/javascript" src="http://openlayers.org/api/OpenLayers.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>

    <!--
    <script type="text/javascript">
        jQuery = jQuery.noConflict();
    </script>
    -->

    <?php
        echo js('ba-debug.min');
        echo js('proj4js/proj4js-compressed');
        echo js('maps/show/show');
        //echo js('cloudmade');
    ?>

    <!-- End Neatline Maps Dependencies -->

    <?php

}
