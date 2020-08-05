<?php
/**
 * Plugin Name: WP Custom Post
 * Description: Homework for WOS
 * Version: 1.0
 * Author: Mykhailo Omelchuk
 */


function add_custom_pt(){
    $labels = array(
        'name' => __('Things'),
        'singular_name' => __('Thing'),
    );

    $post_args = array(
        'labels' => array(
            'name' => __('Things'),
            'singular_name' => __('Thing'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor' ),
    );
    register_post_type('thing', $post_args);
}


function add_textfield_meta_box() {
    add_meta_box('textfield_metabox', 'A TextField', 'textfield_metabox_callback','thing');
}

function textfield_metabox_callback( $post ) {
    wp_nonce_field('save_textfield_metabox', 'save_textfield_metabox_nonce');
    
    $text_value = get_post_meta($post->ID, '_text_value_key', true);
?>
    <label for="custom_text_cmb">Custom TextField: </label>
    <input type="text" id="text_cmb" name="custom_text_cmb" value="<?php echo esc_attr($text_value) ?>"/>
<?php
}

function save_textfield_metabox( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $can_edit = current_user_can('edit_post', $post_id);
    $is_valide_nonce = (isset( $_POST['save_textfield_metabox_nonce']) && wp_verify_nonce($_POST['save_textfield_metabox_nonce'], 'save_textfield_metabox') ) ? 'true' : 'false';
    
    if ( !$is_valide_nonce || $is_autosave || !$can_edit) {
        return;
    }

    $textfield_data = sanitize_text_field( $_POST[ 'custom_text_cmb' ] );
    if ( isset( $_POST['custom_text_cmb']) ) {
        update_post_meta( $post_id, '_text_value_key',  $textfield_data);
    }
}


function add_checkbox_meta_box() {
    add_meta_box('checkbox_metabox', 'A Checkbox', 'checkbox_metabox_callback', 'thing', 'side');
}

function checkbox_metabox_callback( $post ) {
    wp_nonce_field('save_checkbox_status', 'save_checkbox_status_once');
    $prfx_stored_meta = get_post_meta($post->ID);

?>
    <input type="checkbox" name="custom_checkbox_cmb" id="custom_checkbox_cmb" value="yes" 
    <?php 
        if ( isset ( $prfx_stored_meta['custom_checkbox_cmb'] ) ) checked( $prfx_stored_meta['custom_checkbox_cmb'][0], 'yes' ); 
    ?> />
    <label for="custom_checkbox_cmb">Show text from TextField</label>

<?php
}

function save_checkbox_status( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $can_edit = current_user_can('edit_post', $post_id);
    $is_valide_nonce = (isset( $_POST['save_checkbox_status_once']) && wp_verify_nonce($_POST['save_checkbox_status_once'], 'save_checkbox_status') ) ? 'true' : 'false';
    
    if ( !$is_valide_nonce || $is_autosave || !$can_edit) {
        return;
    }
    $checkbox_status = $_POST[ 'custom_checkbox_cmb' ] ? 'yes' : '';
    update_post_meta( $post_id, 'custom_checkbox_cmb', $checkbox_status );
    
}


function custom_ctp_template( $single_template ) {
	global $post;

	if ( 'thing' === $post->post_type ) {
		$single_template = dirname( __FILE__ ) . '/single-thing.php';
	}

	return $single_template;
}


add_action( 'init', 'add_custom_pt' );
add_action( 'add_meta_boxes', 'add_textfield_meta_box');
add_action( 'add_meta_boxes', 'add_checkbox_meta_box');
add_action( 'save_post', 'save_textfield_metabox');
add_action( 'save_post', 'save_checkbox_status');

add_filter( 'single_template', 'custom_ctp_template' );
