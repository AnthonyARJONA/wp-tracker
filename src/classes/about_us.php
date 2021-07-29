<?php

class about_us extends WP_Widget {

    function about_us() {
        $widget_ops = array( 'classname' => 'about_us', 'description' => __( 'About Us', 'wptheme' ) );
        $this->WP_Widget( 'about_us', __( 'About Us', 'wptheme' ), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $text = $instance[ 'text' ];
        // The following variable is for a checkbox option type
        $avatar = $instance[ 'avatar' ] ? 'true' : 'false';

        echo $before_widget;

            if ( $title ) {
                echo $before_title . $title . $after_title;
            }

            // Retrieve the checkbox
            if( 'on' == $instance[ 'avatar' ] ) : ?>
                <div class="about-us-avatar">
                    <?php
//                    echo get_avatar( get_the_author_meta( 'user_email' ), '50', '' );
                    $db = new WP_Tracker_Database();
                    echo $db->getTodayVisit()
                    ?>
                </div>
            <?php endif; ?>

            <div class="textwidget">
                <p><?php echo esc_attr( $text ); ?></p>
            </div>

            <?php
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        $instance[ 'text' ] = strip_tags( $new_instance[ 'text' ] );
        // The update for the variable of the checkbox
        $instance[ 'avatar' ] = $new_instance[ 'avatar' ];
        return $instance;
    }

    function form( $instance ) {
        $defaults = array( 'title' => __( 'About Us', 'wptheme' ), 'avatar' => 'off' );
        $instance = wp_parse_args( ( array ) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
            <input class="widefat"  id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" />
        </p>
        <!-- The checkbox -->
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'avatar' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'avatar' ); ?>" name="<?php echo $this->get_field_name( 'avatar' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'avatar' ); ?>">Show avatar</label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>">About Us</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" cols="10" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_attr( $instance[ 'text' ] ); ?></textarea>
        </p>
        <?php
    }

}