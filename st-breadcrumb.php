<?php
/**
 * Plugin Name: ST Breadcrumb WP
 * Plugin URI: http://beautiful-templates.com/
 * Description: Simple plugin to create breadcrumb for wordpress
 * Author: Beautiful-Templates.com Team
 * Version: 1.0.1
 * Author URI: http://beautiful-templates.com/
 
 */
define('ST_BC_ROOT',dirname(__FILE__).'/');
define('ST_BC_CSS_URL',  trailingslashit(plugins_url('/css/', __FILE__) ));
define('ST_BC_IMG_URL',  trailingslashit(plugins_url('/images/', __FILE__) ));
define('ST_BC_LANG_URL',  trailingslashit(plugins_url('/languages/', __FILE__) ));
class st_breadcrumb {
    function __construct() {
        $this->actions();
        $this->reg_act_hook();
        $this->shortcode();
    }
    function actions() {
        // Active notice 
        $notice = get_option( 'st_breadcrumb_admin_notice' );  
        if ( $notice == 'TRUE' && is_admin() ) {
            add_action('admin_notices', array(&$this, 'st_breadcrumb_activation_notice'));
            update_option('st_breadcrumb_admin_notice','FALSE');   
        }
        add_action( 'plugins_loaded', array($this, 'st_breadcrumb_wp_init') );
        add_action( 'admin_menu', array($this, 'st_register_admin_menu_page') );
        add_action( 'wp_enqueue_scripts', array($this, "add_style") );
        add_action( 'admin_enqueue_scripts', array($this, 'admin_style') );
    }
    function st_breadcrumb_activation_notice(){
        echo '<div class="updated" style="background-color: #53be2a; border-color:#199b57">            
        <p>Thank you for installing <strong>ST Breadcrumb WP</strong></p>
    </div>';
    }
    function st_breadcrumb_activate(){
        update_option('st_breadcrumb_admin_notice','TRUE');
    }
    function reg_act_hook() {
        register_activation_hook( __FILE__, array(&$this, 'st_breadcrumb_activate') );
    }
    function st_breadcrumb_wp_init() {
        $plugin_dir = basename(dirname(__FILE__)).'/languages/';
        load_plugin_textdomain( 'st-breadcrumb-wp', false, $plugin_dir );
    }
    /* 

    *Admin Menu Item
    
    */
    function st_register_admin_menu_page(){
        add_options_page( 'ST Breadcrumb WP', 'ST Breadcrumb WP', 'manage_options', 'st_breadcrumb_wp', array(&$this, 'st_Breadcrumb_admin_menu_page') ); 
    }
    //////////////////////////////////////////////////////////////////////////////
    function st_Breadcrumb_admin_menu_page() {
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ) :
            $this->set_setting('texthome', trim($_POST['texthome']));
            $this->set_setting('textcat', trim($_POST['textcat']));
            $this->set_setting('textptag', trim($_POST['textptag']));
            $this->set_setting('textsearch', trim($_POST['textsearch']));
            $this->set_setting('textauthor', trim($_POST['textauthor']));
            $this->set_setting('delimiter', trim($_POST['delimiter']));
            $this->save_code('css/style.css', $_POST['customcss']);
        endif;
?>
        <div id="st-breadcrumb" class="st-breadcrumb">
            <h2><?php _e('ST Breadcrumb WP', 'st-breadcrumb-wp')?></h2>
            <div class="main box left">
                <h3 class="box-title"><div class="dashicons dashicons-admin-generic"></div><?php _e('Global Setting', 'st-google-map')?></h3>
                <div class="content">
                    <form method="post" action="" id="signUp">
                        <div class="clearBoth fieldset">
                            <fieldset>
                                <legend><?php _e('Breadcrumb Setting', 'st-breadcrumb-wp')?></legend>
                                <div class="show-code">
                                    <p class="label"><?php _e('ST Breadcrumb shortcode', 'st-breadcrumb-wp')?> :</p>
                                    <input class="put-big" type="text" class="code1" value="[ST-breadcrumb]" readonly="readonly">
                                </div>
                                <div>
                                    <p class="label"><?php _e('ST Breadcrumb shortcode for templates', 'st-breadcrumb-wp')?> :</p>
                                    <input class="put-big" type="text" class="code2" value="&lt;?php echo do_shortcode( '[ST-breadcrumb]' ); ?&gt;" readonly="readonly">
                                </div>
                                <div>
                                    <p class="label"><?php _e('Text for the home link', 'st-breadcrumb-wp')?> :</p>
                                    <input type="text" class="put-big" name="texthome" value="<?php echo $this->get_setting('texthome', 'Home')?>" />
                                </div>
                                <div>
                                    <p class="label"><?php _e('Text for the category', 'st-breadcrumb-wp')?> :</p>
                                    <input type="text" class="put-big" name="textcat" value="<?php echo $this->get_setting('textcat', 'Archive Category:')?>" />
                                </div>
                                <div>
                                    <p class="label"><?php _e('Text for the Posts Tagged', 'st-breadcrumb-wp')?> :</p>
                                    <input type="text" class="put-big" name="textptag" value="<?php echo $this->get_setting('textptag', 'Posts Tagged:')?>" />
                                </div>
                                <div>
                                    <p class="label"><?php _e('Text for the Search Results', 'st-breadcrumb-wp')?> :</p>
                                    <input type="text" class="put-big" name="textsearch" value="<?php echo $this->get_setting('textsearch', 'Search Results for:')?>" />
                                </div>
                                <div>
                                    <p class="label"><?php _e('Text for the author', 'st-breadcrumb-wp')?> :</p>
                                    <input type="text" class="put-big" name="textauthor" value="<?php echo $this->get_setting('textauthor', 'Archived Article(s) by Author:')?>" />
                                </div>
                                <div>
                                    <p class="label"><?php _e('Delimiter', 'st-breadcrumb-wp')?> :</p>
                                    <input type="text" class="put-big" name="delimiter" value="<?php echo $this->get_setting('delimiter', '&raquo;')?>" />
                                </div>
                                <div>
                                    <div class="label"><p><?php _e('Custom Css', 'st-breadcrumb-wp')?></p></div>
                                    <textarea name="customcss"><?php $this->load_code('style.css');?></textarea>
                                </div>
                                <div class="sts-actions popup">
                                    <div class="box-button">
                                        <input class="sts-button save" type="submit" value="save" />
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
            <div class="main box right">
                <?php $this->copyright();?>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($) {
        	$(window).load(function() {
          		var feedURL = 'http://beautiful-templates.com/evo/category/products/feed/';
            	$.ajax({
        	        type: "GET",
        	        url: document.location.protocol + '//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=1000&callback=?&q=' + encodeURIComponent(feedURL),
        	        dataType: 'json',
        	        success: function(xml){
        	            var item = xml.responseData.feed.entries;
        	            
        	            var html = "<ul>";
        	            $.each(item, function(i, value){
        	            	html+= '<li><a href="'+value.link+'">'+value.title+'</a></li>';
        	            	if (i===9){
        	            		return false;
        	            	}
        	            });
        	             html+= "</ul>";
        	             $('.st_load_rss').html(html);
        	        }
        	        
        	    });
          });
        });
        </script>
<?php
    }// END ADMIN SETTING
    function set_setting($name, $value) {
        if( get_option('st_bc_opts_'.$name) !== false ) :
            update_option('st_bc_opts_'.$name, $value);
        else :
            add_option('st_bc_opts_'.$name, $value);
        endif;
    }
    function get_setting( $name, $default = '' ) {
        if( get_option('st_bc_opts_'.$name) !== false ) :
            return get_option('st_bc_opts_'.$name, $default);
        else :
            return $default;
        endif;
    }
    function load_code( $file ) {
        $filename = ST_BC_CSS_URL . trim($file);
        if( @file_get_contents($filename) == true ) :
            $code = @file_get_contents($filename);
            echo $code;
        else :
            return false;
        endif;
    }
    function save_code( $file, $content ) {
        if ( current_user_can('edit_plugins') ) :
            $filename = ST_BC_ROOT . $file;
            $setContent = wp_unslash($content); // Remove slashes from a string or array of strings.
            if( is_writeable( $filename ) ) :
                $setfile = fopen($filename, "w+") or die("Unable to open file!");
                if( $setfile !== false ) :
                    fwrite($setfile, urldecode($setContent));
                    fclose($setfile);
                endif;
            endif;
        else :
            wp_die('<p>'.__('You do not have sufficient permissions to edit plugin for this site.').'</p>');
        endif;
    }
    function add_style() {
        wp_enqueue_style( 'st-bc-style', ST_BC_CSS_URL.'style.css' ); 
    }
    function admin_style() {
        wp_enqueue_style( 'st-bc-admin-style', ST_BC_CSS_URL.'admin.css' ); 
    }
    function shortcode() {
        add_shortcode( 'ST-breadcrumb' , array(&$this, 'st_shortcode') );
    }
    function st_shortcode() {
        $args = array (
            'delt' => '<span> '.$this->get_setting('delimiter', '&raquo;').' </span>',
            'delt_cat' => '<span> &bull; </span>',
            'home_text' => $this->get_setting('texthome', 'Home'),
            'textcat' => $this->get_setting('textcat', 'Archive Category:'),
            'textptag' => $this->get_setting('textptag', 'Posts Tagged:'),
            'textsearch' => $this->get_setting('textsearch', 'Search Results for:'),
            'textauthor' => $this->get_setting('textauthor', 'Archived Article(s) by Author:'),
            'notice' => 'Error 404 – Not Found.',
            'year' => get_the_time('Y'),
            'month' => get_the_time('F'),
            'day' => get_the_time('d'),
            'day_full' => get_the_time('l'),
            'max' => 30
        );
        return $this->st_Breadcrumb( $args );
    }
    function st_Breadcrumb( $args = array() ) {
        extract( $args );
        $year_link = get_year_link($year);
        $month_link = get_month_link( $year, $month );
        if (!is_front_page()) :
        
            $html = '<div class="st_breadcrumb">';
            
                global $post, $cat;
                
                $home_link = get_option('home');
                $html .= '<a href="' . $home_link . '">' . $home_text . '</a>' . $delt;
                if ( is_single() ) :
                    $category = get_the_category();
                    $countCat = count($category);
                    // Đếm cat
                    
                    if( $countCat <= 1 ) : 
                        $html .= get_category_parents($category[0],  true,' ' . $delt . ' ');
                        $html .= ' ' . get_the_title();
                    else :
                        $categories = get_the_category();
                        $html .= ' ' . $categories[0]->cat_name;
                        if ( strlen(get_the_title()) >= $max ) :
                            $html .= ' ' . $delt . trim(substr(get_the_title(), 0, $max)) . ' …'; // CẮT TITLE
                        else :
                            $html .= ' ' . $delt . get_the_title();
                        endif; // END TITLE CAT
                    endif; // END Count Cat
                elseif ( is_category() ) :
                    $html .= $textcat . get_category_parents($cat, true) ;
                elseif ( is_tag() ) :
                    $html .= $textptag . single_tag_title("", false);
                elseif ( is_day() ) :
                    $html .= '<a href="' . $year_link . '">' . $year . '</a> ' . $delt . ' ';
                    $html .= '<a href="' . $month_link . '">' . $month . '</a> ' . $delt . $day . ' (' . $day_full . ')';
                elseif ( is_month() ) :
                    $html .= '<a href="' . $year_link . '">' . $year . '</a> ' . $delt . $month;
                elseif ( is_year() ) :
                    $html .= $year;
                elseif ( is_search() ) :
                    $html .= $textsearch . get_search_query();
                elseif ( is_page() && !$post->post_parent ) :
                    $html .= get_the_title();
                elseif ( is_page() && $post->post_parent ) :
                    $post_array = get_post_ancestors($post);
                    krsort($post_array);
                    foreach ( $post_array as $key=>$postid ) :
                        $post_ids = get_post($postid);
                        $title = $post_ids->post_title;
                        $html .= '<a href="' . get_permalink($post_ids) . '">' . $title . '</a>' . $delt;
                    endforeach;
                    $html .= the_title();
                elseif ( is_author() ) :
                    global $author;
                    $user_info = get_userdata($author);
                    $html .=  $textauthor . $user_info->display_name ;
                elseif ( is_404() ) :
                    $html .=  $notice;
                endif; // END IF
            $html .= '</div>';
        endif; // END IS_FRONT_PAGE
        if( isset( $html ) ) :
            return $html;
        endif;
    }
    function copyright() {
?>
        <h3 class="box-title"><div class="dashicons dashicons-sos"></div><?php _e('Abouts', 'st-google-map')?></h3>
        <div class="st-box">
        	<div class="box-content">
        		<div class="st-row">
        			Hi,</br></br>We are Beautiful-Templates and we provide Wordpress Themes & Plugins, Joomla Templates & Extensions.</br>Thank you for using our products. Let drop us feedback to improve products & services.</br></br>Best regards,</br> Beautiful Templates Team
        		</div>
        	</div>
        	<div class="st-row st-links">
        		<div class="col col-8 links">
        			<ul>
        				<li>
        					<a href="http://beautiful-templates.com/" target="_blank"><?php _e('Home', 'st-breadcrumb-wp')?></a>
        				</li>
        				<li>
        					<a href="http://beautiful-templates.com/amember/" target="_blank"><?php _e('Submit Ticket', 'st-breadcrumb-wp')?></a>
        				</li>
        				<li>
        					<a href="http://beautiful-templates.com/evo/forum/" target="_blank"><?php _e('Forum', 'st-breadcrumb-wp')?></a>
        				</li>
        				<li>
        					<?php add_thickbox(); ?>
        					<a href="<?php echo plugins_url( '/doc/index.html', __FILE__ ); ?>?TB_iframe=true&width=1000&height=600" class="thickbox"><?php _e('Document', 'st-breadcrumb-wp')?></a>
        				</li>
        			</ul>
        		</div>
        		<div class="col col-2 social">
        			<ul>
        				<li>
        					<a href="https://www.facebook.com/beautifultemplates/" target="_blank"><div class="dashicons dashicons-facebook-alt"></div></a>
        				</li>
        				<li>
        					<a href="https://twitter.com/cooltemplates/" target="_blank"><div class="dashicons dashicons-twitter"></div></a>
        				</li>
        			</ul>
        		</div>
        	</div>
        </div>
        <div class="st-box st-rss">
        	<div class="box-content">
        		<div class="st-row st_load_rss">
        			<span class="spinner" style="display:block;"></span>
        		</div>
        	</div>
        </div>
<?php
    } // END COPPYRIGHT
} // END CLASS
$st_breadcrumb = new st_breadcrumb;
require_once(ST_BC_ROOT . 'widget.php');