<?php
/**
 * @package jopfre_featured_image_position_selector
 * @version 1.0
 */

/*
Plugin Name: jopfre featured image postion selector 
Plugin URI:
Description: adds a dropdown selector to the featured image position with 'top', 'center' and 'bottom' options to allow users to pick the css background-position for their featured images when used as a background-image. for example usage see the jopfre-featured-image-position-selector.php file comments. based on: Featured_Image_Metabox_Customizer https://github.com/bradvin/Featured-Image-Metabox-Customizer-Class and voodoo_dropdown http://wordpress.stackexchange.com/a/77073/43501
Author: jopfre
Version:
Author URI:
*/

/*
example usage:   
<div style="background-image: url(<?php the_featured_image_url(); ?>); background-position: center <?php the_featured_image_position(); ?>;">
  <?php the_title(); ?>
</div>
*/

add_action( 'add_meta_boxes', 'jopfre_replace_featured_image_metabox' );
add_action( 'save_post', 'jopfre_featured_image_position_save' );

function jopfre_replace_featured_image_metabox() {
  //remove original featured image metabox
  remove_meta_box( 'postimagediv', 'page', 'side' );
  //add our customized metabox
  add_meta_box( 'postimagediv', 'Featured Image', 'jopfre_post_thumbnail_meta_box', 'page', 'side', 'low' );
}

//custom metabox display
function jopfre_post_thumbnail_meta_box( $post ) {
  //from wp core post_thumbnail_meta_box()
  $thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
  echo _wp_post_thumbnail_html( $thumbnail_id, $post->ID );

  //use nonce for verification
  wp_nonce_field( basename( __FILE__ ), 'jopfre_featured_image_position_nonce' );

  // get current value
  $dropdown_value = get_post_meta( $post->ID, 'jopfre_featured_image_position', true );
?>
  <p><strong>Position</strong></p>
  <select name="jopfre_featured_image_position" id="jopfre_featured_image_position">
    <option value="center" <?php if ($dropdown_value == 'center') echo 'selected'; ?>>center</option>
    <option value="top" <?php if ($dropdown_value == 'top') echo 'selected'; ?>>top</option>
    <option value="bottom" <?php if ($dropdown_value == 'bottom') echo 'selected'; ?>>bottom</option>
  </select>
<?php 
}

//dropdown saving
function jopfre_featured_image_position_save( $post_id ) { //post_id is automatically passed when using adding actions to 'save_post'
  //if doing autosave don't do nothing
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }
  //verify nonce
  if ( !wp_verify_nonce( $_POST['jopfre_featured_image_position_nonce'], basename( __FILE__ ) ) ) {
    return;
  }
  //check permissions
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) ) {
      return;
    }
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  }
  //save the new value of the dropdown
  $new_value = $_POST['jopfre_featured_image_position'];
  update_post_meta( $post_id, 'jopfre_featured_image_position', $new_value );
}

function the_featured_image_position() {
   $post = get_post();

   if (!empty( $post )) {
    echo get_post_meta( $post->ID, 'jopfre_featured_image_position', true );
   } else {
    return;
   }
}
//optional helper function
function the_featured_image_url() {
  echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
}
?>