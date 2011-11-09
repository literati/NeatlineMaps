<?php echo $this->partial('maps/admin-header.php', array('subtitle' => 'Create Map')); ?>

<div id="primary">

    <?php echo flash(); ?>

    <?php if (count($items) == 0 && $search == NULL): ?>

        <p>There are no items yet.</p>

    <?php else: ?>

            <h2>Select an item to associate the map with:</h2>

            <table class="neatline">

                <div id="simple-search-form">
                    <form id="simple-search" action="<?php echo uri('neatline-maps/maps/create'); ?>" method="get">
                        <fieldset>
                            <input type="text" name="search" id="search" value="<?php echo $search; ?>" class="textinput">
                            <input type="submit" name="submit_search" id="submit_search" value="Search Items">
                        </fieldset>
                    </form>
                </div>

                <p class="neatline-connector-search-reset"><a href="<?php echo uri('neatline-maps/maps/create'); ?>">Reset</a></p>

                <thead>
                    <tr>
                        <?php browse_headings(array(
                            'Title' => 'item_name',
                            'Type' => 'Type',
                            'Creator' => 'creator',
                            'Date Added' => 'added',
                            'Add to Item' => NULL
                        )); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><a href="<?php echo public_uri('items/show/' . $item->id); ?>"><?php echo _previewString($item->item_name, 50); ?></a></td>
                            <td><?php echo $item->Type != '' ? $item->Type : '<span style="font-size: 0.8em; color: gray;">[not available]</span>'; ?></td>
                            <td><?php echo $item->creator != '' ? $item->creator : '<span style="font-size: 0.8em; color: gray;">[not available]</span>'; ?></td>
                            <td><?php echo _formatDate($item->added); ?></td>
                            <td><?php echo $this->partial('maps/map-add-action.php', array('id' => $item->id)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

    <?php endif; ?>

          <div class="pagination">

<?php echo pagination_links(array('scrolling_style' => 'All',
:
              'page_range' => '5',
              'partial_file' => 'common/pagination_control.php',
              'page' => $current_page,
              'per_page' => $results_per_page,
              'total_results' => $total_results)); ?>

          </div>

</div>

<?php foot(); ?>
