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
 * Do the Item tab form.
 *
 * @return void.
 */
function _doItemForm($item)
{

    $db = get_db();
    $maps = $db->getTable('NeatlineMapsMap')->getMapsByItem($item);

    ob_start();
    include NEATLINE_MAPS_PLUGIN_DIR . '/forms/neatline-maps-form.php';
    return ob_get_clean();

}

/**
 * Include the GeoServer .js and .css dependencies in the public theme header.
 *
 * @return void.
 */
function _doHeaderJsAndCss()
{

    ?>

    <!-- Neatline Maps Dependencies -->

    <?php
        queue_css('leaflet', null, null, 'javascripts/leaflet/dist');
    ?>

    <script type="text/javascript" src="http://openlayers.org/api/OpenLayers.js"></script>

    <!-- End Neatline Maps Dependencies -->

    <?php

}

/**
 * Include the GeoServer .js and .css dependencies in the public theme header.
 *
 * @return void.
 */
function _doItemAdminHeaderJsAndCss()
{

    ?>

    <!-- Neatline Maps Dependencies -->

    <link rel="stylesheet" href="<?php echo css('neatline-maps-admin'); ?>" />

    <!-- End Neatline Maps Dependencies -->

    <?php

}

/**
 * Include the custom css for the admin tab.
 *
 * @return void.
 */
function _doTabAdminHeaderJsAndCss()
{

    ?>

    <!-- Neatline Maps Dependencies -->

    <link rel="stylesheet" href="<?php echo css('neatline_maps_main'); ?>" />

    <!-- End Neatline Maps Dependencies -->

    <?php

}

/**
 * Create a new GeoServer namespace.
 *
 * @param string $geoserver_url The location of the GeoServer.
 * @param string $geoserver_namespace_prefix The name of the namespace.
 * @param string $geoserver_user The admin username.
 * @param string $geoserver_password The admin password.
 * @param string $geoserver_namespace_url The URL attached to the namespace.
 *
 * @return boolean True if GeoServer accepts the file.
 */
function _createGeoServerNamespace(
            $geoserver_url,
            $geoserver_namespace_prefix,
            $geoserver_user,
            $geoserver_password,
            $geoserver_namespace_url)
{

    // Set up curl to dial out to GeoServer.
    $geoServerConfigurationAddress = $geoserver_url . '/rest/namespaces';
    $geoServerNamespaceCheck = $geoServerConfigurationAddress . '/' . $geoserver_namespace_prefix;

    $clientCheckNamespace = new Zend_Http_Client($geoServerNamespaceCheck);
    $clientCheckNamespace->setAuth($geoserver_user, $geoserver_password);

    // Does the namespace already exist?
    if (strpos(
            $clientCheckNamespace->request(Zend_Http_Client::GET)->getBody(),
            'No such namespace:'
    ) !== false) {

        $namespaceJSON = '
            {
                "namespace": {
                    "prefix": "' . $geoserver_namespace_prefix . '",
                    "uri": "' . $geoserver_namespace_url . '"
                }
            }
        ';

        $ch = curl_init($geoServerConfigurationAddress);
        curl_setopt($ch, CURLOPT_POST, True);

        $authString = $geoserver_user . ':' . $geoserver_password;
        curl_setopt($ch, CURLOPT_USERPWD, $authString);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $namespaceJSON);

        $successCode = 201;
        $buffer = curl_exec($ch);

        // return $buffer;

    }

}

/**
 * Post a file to GeoServer and see if it accepts it as a valid geotiff.
 *
 * @param Omeka_record $file The file to send.
 * @param Omeka_record $server The server to use.
 * @param string $namespace The namespace to add the file to.
 *
 * @return boolean True if GeoServer accepts the file.
 */
function _putFileToGeoServer($file, $server, $namespace)
{

    // Does GeoServer recognize the file as a map?
    $zip = new ZipArchive();
    $zipFileName = ARCHIVE_DIR . '/' . $file->original_filename . '.zip';
    $zip->open($zipFileName, ZIPARCHIVE::CREATE);
    $zip->addFile(ARCHIVE_DIR . '/files/' . $file->archive_filename, $file->original_filename);
    $zip->close();

    $coverageAddress = $server->url . '/rest/workspaces/' .
        $namespace . '/coveragestores/' . $file->original_filename .
        '/file.geotiff';

    $ch = curl_init($coverageAddress);
    curl_setopt($ch, CURLOPT_PUT, True);

    $authString = $server->username . ':' . $server->password;
    curl_setopt($ch, CURLOPT_USERPWD, $authString);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/zip'));
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($zipFileName));
    curl_setopt($ch, CURLOPT_INFILE, fopen($zipFileName, "r"));
    curl_setopt($ch, CURLOPT_PUTFIELDS, $zipFileName);

    $successCode = 201;
    $buffer = curl_exec($ch);
    $info = curl_getinfo($ch);

    return ($info['http_code'] == $successCode);

}

/**
 * Deletes a coveragestore from GeoServer.
 *
 * @param Omeka_record $file The file corresponding to the coveragestore.
 * @param Omeka_record $map The parent map.
 * @param Omeka_record $server The parent server.
 *
 * @return boolean True if GeoServer accepts the file.
 */
function _deleteFileFromGeoserver($file, $map, $server)
{

    $coverageAddress = $server->url . '/rest/workspaces/' .
        $namespace . '/coveragestores/' . $file->original_filename;

    $ch = curl_init($coverageAddress);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

    $authString = $server->username . ':' . $server->password;
    curl_setopt($ch, CURLOPT_USERPWD, $authString);

    $successCode = 405;
    $buffer = curl_exec($ch);
    $info = curl_getinfo($ch);

    return ($info['http_code'] == $successCode);

}

/**
 * Build the main WMS address for the JavaScript.
 *
 * @return string The url.
 */
function _getWmsAddress($item)
{

    $namespace = $item->getElementTextsByElementNameAndSetName('Namespace', 'Item Type Metadata');
    $namespace = $namespace[0]->text;

    return get_option('neatlinemaps_geoserver_url') . '/' .
        $namespace . '/wms';

}

/**
 * A homebrew colum sorter, implemented so as to keep more control
 * over how the record loop is handled in the view.
 *
 * @param object $request The incoming request dispatched by the 
 * front controller.
 *
 * @return string $order The sorting parameter for the query.
 */
function _doColumnSortProcessing($sort_field, $sort_dir)
{

    if (isset($sort_dir)) {
        $sort_dir = ($sort_dir == 'a') ? 'ASC' : 'DESC';
    }

    return (isset($sort_field)) ? trim(implode(' ', array($sort_field, $sort_dir))) : '';

}



/**
 * Retrieves items to populate the listings in the itemselect view.
 *
 * @param string $page The page to fetch.
 * @param string $order The constructed SQL order clause.
 * @param string $search The string to search for.
 *
 * @return array $items The items.
 */
function _getItems($page = null, $order = null, $search = null)
{

    $db = get_db();
    $itemTable = $db->getTable('Item');

    // Wretched query. Fallback from weird issue with left join where item id was
    // getting overwritten. Fix.
    $select = $db->select()
        ->from(array('item' => $db->prefix . 'items'))
        ->columns(array('item_id' => 'item.id', 
            'Type' =>
            "(SELECT name from `$db->ItemType` WHERE id = item.item_type_id)",
            'item_name' =>
            "(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 50 LIMIT 1)",
            'creator' =>
            "(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 39)"
            ));

    if (isset($page)) {
        $select->limitPage($page, get_option('per_page_admin'));
    }
    if (isset($order)) {
        $select->order($order);
    }
    if (isset($search)) {
        $select->where("(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 50 LIMIT 1) like '%" . $search . "%'");
    }

    return $itemTable->fetchObjects($select);

}

/**
 * Retrieves a single item with added columns with name, etc.
 *
 * @param $id The id of the item.
 *
 * @return object $item The item.
 */
function _getSingleItem($id)
{

    $db = get_db();
    $itemTable = $db->getTable('Item');

    $select = $db->select()
        ->from(array('item' => $db->prefix . 'items'))
        ->columns(array('item_id' => 'item.id', 
            'Type' =>
            "(SELECT name from `$db->ItemType` WHERE id = item.item_type_id)",
            'item_name' =>
            "(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 50 LIMIT 1)",
            'creator' =>
            "(SELECT text from `$db->ElementText` WHERE record_id = item.id AND element_id = 39)"
            ))
        ->where('item.id = ' . $id);

    return $itemTable->fetchObject($select);

}
