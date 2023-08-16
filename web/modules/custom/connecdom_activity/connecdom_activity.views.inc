<?php

/**
 * @file
 * Contains connecdom_activity\connecdom_activity.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */


/**
* Implements hook_views_data().
*/
function connecdom_activity_views_data() {

    $data['views']['table']['group'] = t('Custom Global');
    $data['views']['table']['join'] = [
      // #global is a special flag which allows a table to appear all the time.
      '#global' => [],
    ];

    $data['views']['connecdom_activity_link'] = [
        'title' => t('Connecdom Activity link'),
        'help' => t('Link to the activity history.'),
        'field' => [
            'id' => 'connecdom_activity_link',
        ],
    ];
    return $data;
}
