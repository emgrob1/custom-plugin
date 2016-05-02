<?php
/* Template Name: The Content Sidebar
 * 
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
}
?>
<div id="content-hover" class="content-hover widget-area" role="complementary">
	<?php //dynamic_sidebar( 'sidebar-2' );?>
	<p>I am sidebar for Hover</p>
	<div id="ajax"></div>
</div><!-- #content-sidebar -->
