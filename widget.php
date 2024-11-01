<?php
class st_breadcrumb_widget extends WP_Widget {
    function st_breadcrumb_widget() {
        parent::WP_Widget( $id = 'st_breadcrumb_widget', $name = 'ST Breadcrumb' , $options = array( 'description' => 'Simple plugin to create breadcrumb for wordpress.' ) );
    }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $instance['title'] = esc_attr( $instance['title'] );
?>
        <div>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" type="text" name="<?php echo $this->get_field_name('title')?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </p>
        </div>
<?php
    }
    function widget( $args, $instance ) {
        global $st_breadcrumb;
        extract( $args, EXTR_SKIP );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? "" : $instance['title'], $instance );
        echo $before_widget;
            if ( $title != '' && !is_home() ) :
                echo $before_title . $title . $after_title;
            endif;
            $Opts = array (
                'delt' => '<span> '.$st_breadcrumb->get_setting('delimiter', '&raquo;').' </span>',
                'delt_cat' => '<span> &bull; </span>',
                'home_text' => $st_breadcrumb->get_setting('texthome', 'Home'),
                'textcat' => $st_breadcrumb->get_setting('textcat', 'Archive Category:'),
                'textptag' => $st_breadcrumb->get_setting('textptag', 'Posts Tagged:'),
                'textsearch' => $st_breadcrumb->get_setting('textsearch', 'Search Results for:'),
                'textauthor' => $st_breadcrumb->get_setting('textauthor', 'Archived Article(s) by Author:'),
                'notice' => 'Error 404 – Not Found.',
                'year' => get_the_time('Y'),
                'month' => get_the_time('F'),
                'day' => get_the_time('d'),
                'day_full' => get_the_time('l'),
                'max' => 30
            );
            echo $st_breadcrumb->st_Breadcrumb( $Opts );
        echo $after_widget;
    }
}// END WIDGET
add_action( 'widgets_init', create_function( '', 'register_widget("st_breadcrumb_widget");' ) );