<?php 
/* Plugin Name: Sandbox
 * Description: Hover display page contents in sidebar
 * Author: Evan Grob
 * URL: evangrob.com
 * Version: 1.0.1
 */
 
 
 function register_display_sidbar() {
	 // register_color_projects
	 register_post_type( 'display_sidbar', array(
			'labels'	=>	array( 
				'name' => __( 'Display Content Sidbar' ),
				'singular_name' => __( 'Display Content Sidbar' ),
				'public' => true,
        'show_ui' => true,
        
        'hierarchical' => false,
        'has_archive' => true,
        'rewrite' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
				'edit_item' => __( 'Edit Display Content Sidbar' ),
				'view_item' => __( 'View Display Content Sidbar' )
			),
			'description'			=>	__( 'Used to create and edit Display Content Sidbar.' ),
			'public'				=>	true,
			 'post_status' => 'published',
			'show_ui' => true, 
			'menu_position'			=>	4,
			'menu_icon'				=>	'dashicons-welcome-add-page',
			'capability_type'		=>	'post',
			'supports'				=>	array( 'title', 'thumbnail' ),
			'register_meta_box_cb'	=>	'display_sidbar_panel'
			
	 	)
		
	 );	
	
	
	 
 }
 add_action( 'init', 'register_display_sidbar' ); 
 

 function display_sidbar_panel( $post ) {
	add_meta_box( 'display_sidbar', __( 'Display Content Sidbar' ), 'display_sidbar_meta_callback', 'display_sidbar' );
}
 
 function display_sidbar_meta_callback( $post ) {
	 // a nonce field so we can check for it later
	 //wp_nonce_field( 'ifdc_save_story', 'ifdc_meta_box_nonce' );
	 
	global $post; 
	// $subtitle = get_post_meta( $post->ID, '_ifdc_story_subtitle', true );
	echo '<p>';
	echo 'Select post to display in the sidebar.';
	$page_id=get_all_page_ids();
	
	echo '<selection>';
	foreach ($page_id as $detail){
		echo '<br><input  type="checkbox"  name="fileselect[]" value="test"';
		echo '<br>'.get_the_title($detail).' | Page ID: '.$detail;
	}
	
   echo '</p>';
	 
		}
 
 function build_headerData() {
     echo ' 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script src="http://localhost/sandbox.com/wp-content/plugins/custom-plugin/js/tool.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
     <link rel="stylesheet" href="http://localhost/sandbox.com/wp-content/plugins/custom-plugin/css/display.css" type="text/css">
     ';
 }
 
 add_action( 'wp_head', 'build_headerData');
 
 
 
 // Creating the widget 
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpb_widget', 

// Widget name will appear in UI
__('Display Page for Hover', 'wpb_widget_domain'), 

// Widget description
array( 'description' => __( 'Display Contents of Page during hover', 'wpb_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
echo __( 'Display Page Contents Here', 'wpb_widget_domain' );
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Display Page Contents', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
 
 /* */
 
  function hover_widgets_init(){
  	
  	register_sidebar( array(
	'name'			=> 'Hover Display',
	'id'			=> 'hover_display',
	'before_widget' => '<div>',
	'after_widget' 	=> '<div id="ajax"></div></div>',
	'before_title' 	=> '<h6 >',
	'after_title' 	=> '</h6>'
	));
  }
  add_action('widgets_init', 'hover_widgets_init');
 /*Create a page template from plugin*/
 class PageTemplater {

		/**
         * A Unique Identifier
         */
		 protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

                if( null == self::$instance ) {
                        self::$instance = new PageTemplater();
                } 

                return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

                $this->templates = array();


                // Add a filter to the attributes metabox to inject template into the cache.
                add_filter(
					'page_attributes_dropdown_pages_args',
					 array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the save post to inject out template into the page cache
                add_filter(
					'wp_insert_post_data', 
					array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the template include to determine if the page has our 
				// template assigned and return it's path
                add_filter(
					'template_include', 
					array( $this, 'view_project_template') 
				);


                // Add your templates to this array.
                $this->templates = array(
                        'hover_page.php'     => 'Hover Page',
                        'display_page.php'	 => 'Display Page',	
                         
                 );
				
        } 


        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         *
         */

        public function register_project_templates( $atts ) {

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

                // Retrieve the cache list. 
				// If it doesn't exist, or it's empty prepare an array
                $templates = wp_get_theme()->get_page_templates();
                if ( empty( $templates ) ) {
                        $templates = array();
                } 

                // New cache, therefore remove the old one
                wp_cache_delete( $cache_key , 'themes');

                // Now add our template to the list of templates by merging our templates
                // with the existing templates array from the cache.
                $templates = array_merge( $templates, $this->templates );

                // Add the modified cache to allow WordPress to pick it up for listing
                // available templates
                wp_cache_add( $cache_key, $templates, 'themes', 1800 );

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_project_template( $template ) {

                global $post;

                if (!isset($this->templates[get_post_meta( 
					$post->ID, '_wp_page_template', true 
				)] ) ) {
					
                        return $template;
						
                } 

                $file = plugin_dir_path(__FILE__). get_post_meta( 
					$post->ID, '_wp_page_template', true 
				);
				
                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
				else { echo $file; }

                return $template;

        } 


} 

add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );


add_action( 'wp_enqueue_scripts', 'my_enqueued_assets' );

function my_enqueued_assets() {
	wp_enqueue_script( 'my-script', plugin_dir_url( __FILE__ ) . '/js/tool.js', array( 'jquery' ), '1.0', true );
}


?>