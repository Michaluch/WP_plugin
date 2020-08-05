<?php
  /**
  * Template Name: WOS Homework
  * Template Post Type: thing
  */

get_header(); 
?>

<p> Post Title: <?php the_title() ?> </p>
<p> TextField Metabox Content: 

<?php

// Retrieves the stored value from the database
$textfield_value = get_post_meta( $post->ID, '_text_value_key', true );
$checkbox_status = get_post_meta( $post->ID, 'custom_checkbox_cmb' ,true);

if (($textfield_value != '') && ($checkbox_status != '')) {
    echo $textfield_value;
    }else { 
    echo "Content of textbox is hiden";
    }

?>
</p>