<?php
function enqueue_my_scripts() {
    wp_enqueue_script('move', plugin_dir_url(__FILE__) . 'plugins/20-20/js/jquery.event.move.js', array('jquery'));
    
}
function enqueue_my_scripts() {
    wp_enqueue_script('2020', plugin_dir_url(__FILE__) . 'plugins/20-20/js/jquery.twentytwenty.js', array('jquery'));
    
}
?>