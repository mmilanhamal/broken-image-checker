<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Broken Image Checker
Description: Check the featured image of the posts if they are broken or not.
Version:     2.0
Author:      Saurab Adhikari
Author URI:  http://saurabadhikari.com.np
Domain Path: /languages
Text Domain: broken-image-checker

Broken Image Checker is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Broken Image Checker is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Broken Image Checker. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

// Plugin base URL.
define( "BIC_BASE_URL", plugin_dir_url( __FILE__ ) );
// Plugin base path.
define( "BIC_BASE_PATH", dirname( __FILE__ ) );
error_reporting(0);

/**
* Load plugin textdomain.
*
* @since 1.0.0
*/
function bic_load_textdomain() {
    load_plugin_textdomain( 'broken-image-checker', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'bic_load_textdomain' );


/**
* Enqueue plugin style and scripts.
*
* @since 1.0.0
*/
function bic_load_plugin_css(){

    wp_enqueue_style( 'plugin-css', BIC_BASE_URL . 'assets/bic-style.css'); 
    wp_enqueue_script( 'plugin-js', BIC_BASE_URL . 'assets/bic-custom.js'); 

}   
add_action( 'admin_enqueue_scripts', 'bic_load_plugin_css' );


/**
* Admin Menu page.
*
* @since 1.0.0
*/
function bic_register_menu_page() {
    add_menu_page( 'Broken Image Checker', 'Broken Image Checker', 'manage_options', 'Broken Image Checker', 'bic_function', '', 6 );

}
add_action( 'admin_menu', 'bic_register_menu_page' );


/**
* Main plugin function.
*
* @since 1.0.0
*/
function bic_function() { ?>

<div class="wrap">
<div class="bic-plugin-header"><?php _e('Broken Image Checker','broken-image-checker');?></div>

        <form>
        <select id="foo" name="myselect" onchange="self.location=self.location+'&idx='+this.value">
        <option><?php _e('choose','broken-image-checker');?></option>
        <?php
        $blogusers = get_users( array( 'fields' => array( 'display_name','ID' ), 'who' => 'author' ) );
        // Array of stdClass objects.
        foreach ( $blogusers as $user ) {
             echo '<option value="' .$user->ID .'">' .$user->display_name .'</option>';
        }
        ?>
        </select>
        </form>
        <div id="bic-table"> 
<?php
    if(isset($_GET['idx'])){
$uid = esc_attr($_GET['idx']);  
    $args = array(
        'post_type' => 'any',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
        'author' => $uid,
        );    
    $the_query = new WP_Query( $args ); 
    $author_name = get_userdata($uid)->display_name;
echo '<h2>'.__('Posts from: ','broken-image-checker').$author_name.'</h2>';
}
else{   
   $args = array(
        'post_type' => 'any',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
        ); 
    $the_query = new WP_Query( $args ); 
echo '<h2>'.__('Post from all the authors: ','broken-image-checker').'</h2>';
} 
    if ( $the_query->have_posts() ) { ?>             
        <table>
            <thead><tr><th><?php _e('Edit Post','broken-image-checker');?></th><th><?php _e('Post Title','broken-image-checker');?></th><th><?php _e('Post Type','broken-image-checker');?></th><th><?php _e('Post Status','broken-image-checker');?></th><th><?php _e('Post Date','broken-image-checker');?></th><th><?php _e('Message','broken-image-checker');?></th></tr></thead><tbody>
            <?php
            while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <tr><td><?php edit_post_link(); ?></td><td><?php the_title(); ?></td><td><?php echo get_post_type( get_the_ID() );?></td><td><?php echo get_post_status(get_the_ID());?></td><td><?php echo get_the_date();?></td>
                <?php $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); 
                if($image_url[0]!='')
                {
                    $url=getimagesize($image_url[0]);
                    if(!is_array($url)) { ?>
                    <td><font color="red"><?php _e('The featured image is broken','broken-image-checker');?></font></td>
                    <?php  }
                }
                else{ ?>
                <td><?php _e('Featured image not found','broken-image-checker');?></td></tr>
                <?php }
                endwhile; 
                ?>
            </tbody>
        </table>
       
    
    <?php
    }
    else{ ?>
        <div class="msg-box"><div class="msg"><?php _e('No Posts found from this Author','broken-image-checker');?></div>
        <button type="button" onClick="history.go(-1); return true;"><?php _e('Back','broken-image-checker');?></button></div>
    <?php
    }
    ?>
</div></div>
<?php
}
?>
 