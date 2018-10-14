<?php

namespace Utils;

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = in_array(true, [
        // The sidebar will be displayed if any of the following return true
        is_home(),
        is_404(),
        is_page_template('template-custom.php')
    ]);

    return $display;
}
