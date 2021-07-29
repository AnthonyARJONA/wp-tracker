<?php
/**
 * Adds Foo_Widget widget.
 */
class Tracker_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'Tracker_Widget', // Base ID
            'Tracker_Widget', // Name
            array( 'description' => __( 'A Widget which can echo datas from tracker', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $text = $instance[ 'text' ];
        $todayTotalViewers = $instance[ 'todayTotalViewers' ] ? 'true' : 'false';

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        // Retrieve the checkbox
        if( 'on' == $instance[ 'todayTotalViewers' ] ) : ?>
            <div>
                <?php
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

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Counter', 'text_domain' );
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
            <input class="widefat"  id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'todayTotalViewers' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'todayTotalViewers' ); ?>" name="<?php echo $this->get_field_name( 'todayTotalViewers' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'todayTotalViewers' ); ?>">Afficher le nombre de visites aujourd'hui</label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>">Rajoutes un commentaire</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" cols="10" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_attr( $instance[ 'text' ] ); ?></textarea>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['text'] = ( !empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
        $instance['todayTotalViewers'] = $new_instance['todayTotalViewers'];

        return $instance;
    }

} // class Foo_Widget

?>