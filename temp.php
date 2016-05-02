<?php
/*
 * 
 * Plugin Name: CYAC
 * Description: Cover Your Assets Compliance allow admins to create user    accounts and add/remove any pdf to their accounts ntiva 9/8/15. <a   href="http://www.potomacco.com/cyac-tutorial/" target="blank" />Tutorial</a>
 * Version: 1.0.0
 * Author: Ntiva
 * Author URI: https://www.ntiva.com/
 * Notes:
 * License: GPL2 
 * 
 */
function cyac_register_post_type() {
	 // register the post type
	 register_post_type( 'cyac', array(
			'labels'	=>	array( 
				'name' => __( 'CYAC' ),
				'singular_name' => __( 'CYAC' ),
				'edit_item' => __( 'Edit CYAC' ),
				'view_item' => __( 'View CYAC' )
			),
			'description'			=>	__( 'Used to create and edit CYAC Users.' ),
			'public'				=>	true,
			 'post_status' => 'published',
			'show_ui' => true, 
			'menu_position'			=>	3,
			'menu_icon'				=>	'dashicons-admin-page',
			'capability_type'		=>	'page',
			'supports'				=>	array( 'title', 'thumbnail' ),
			'register_meta_box_cb'	=>	'cyac_panel'
			
	 	)
	 );	 
 }
 add_action( 'init', 'cyac_register_post_type' );
 //admin interface -enter title, select user, select documents.
function cyac_panel( $post ) {
	add_meta_box( 'cyac_panel', __( 'CYAC (Cover Your Assets Compliance) User Set Up' ), 'cyac_panel_meta_callback', 'cyac' );
}
function cyac_panel_meta_callback($post) {
global $wpdb;
//global $post;
?>
	<div style="width:750px;">
<h2>Select from the list of users with the role of CYAC User</h2>
<div class="_cyac_user_setup">
<?php

$args=array(
  'post_type' => 'cyac',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'caller_get_posts'=> 1
);
$my_query = null;
$my_query = new WP_Query($args);

if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
        $my_query->the_post();
      $listHolder[] .= get_the_title() ;
    }
}

//gets user by role (subscriber) is the role assigned to users created to view their documents
$cyacname = get_post_meta($post->ID,'cyac_user',true);
$pdf_fileselect = get_post_meta($post->ID,'fileselect',true);
$title = get_post_meta($post->ID,'post_title',true);
$query = "SELECT ID from $wpdb->users";
$author_ids = $wpdb->get_results($query);
$users = array();
foreach($author_ids as $author) {
   // Get user data
   $curauth = get_userdata($author->ID);
   // Get link to author page will need to set the role it searches for
  if($curauth->roles[0] == 'subscriber' &&  (!in_array($curauth->user_login, $listHolder,true))){
   $link = "/member/" . $curauth->user_login;
   $name = $curauth->user_login;
   $users[$link] = $name;   
   }
}
asort($users);
?>
</div>
<?php
?>
<div>
<?php
if($cyacname){	
$name = $cyacname;	
echo '<h2><label>'. $name.'</label></h2>';
}else{
?>
<select name = "cyac_user">
<?php
foreach($users as $link => $name) :
?>
<option name = "cyac_user_name[]"  value ="<?php echo $name; ?>">  <?php  echo $name; ?></option>
<?php endforeach;?>
</select><?php } ?>
</div>
<div>
<h2>Selected PDFs</h2>
<?php
/*lists the documents saved in wp-content/uploads/cyac the assigned folder that will contain all the cyac documents.
may need to set the path */
				$handle = opendir('/home/potomac/public_html/wp-content/uploads/cyac');
while (false !==($read = readdir($handle))){
if ($read!='..' && $read!='.' && $read!='index.php'){
for($i = 0; $i< count($read);$i++):
if (in_array($read, $pdf_fileselect)) {
?>
<input  type="checkbox"  name="fileselect[]"  <?php  if (in_array($read, $pdf_fileselect,true)) echo 'checked = "checked"';""?> value="<? echo $read;?>" ><? echo $read.'<br/>';
}else{
	?>
				<input  type="checkbox"  name="fileselect[]" <?php if ($selected == '<?php echo $read; ?>' ) echo 'selected'; ?> value="<? echo $read;?>" ><? echo $read.'<br/>';
						}
						endfor;
						}
}closedir($handle);
?>
    </div>
    </div>
    <?php	
}
function save_custom_meta($post) {
global $post;
if(get_post_meta($post->ID,'cyac_user',true)){
	update_post_meta($post->ID, 'fileselect', $_POST['fileselect']);
	}else{
		update_post_meta($post->ID, 'fileselect', $_POST['fileselect']);
		update_post_meta($post->ID, 'cyac_user', $_POST['cyac_user']);
		}		
	

}

add_action('save_post', 'save_custom_meta',99,2);


