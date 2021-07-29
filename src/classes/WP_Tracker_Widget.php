<?php
/**
 * Adds Foo_Widget widget.
 */
class WP_Tracker_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'WP_Tracker_Widget', // Base ID
            'WP Tracker Widget', // Name
            array( 'description' => __( 'A Widget which can echo datas from tracker', 'text_domain' ), ) // Args
        );
    }

    public static function register_widget() {
        function register_tracker_widget() {
            register_widget('WP_Tracker_Widget');
        }
        add_action( 'widgets_init', 'register_tracker_widget' );
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
        $maxViewers = $instance[ 'maxViewers' ] ? 'true' : 'false';
        $totalVisitOnPageViewers = $instance[ 'totalVisitOnPageViewers' ] ? 'true' : 'false';

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $db = new WP_Tracker_Database();
        if( 'on' == $instance[ 'todayTotalViewers' ] ) : ?>
            <div style="<?= $this->getStyle(); ?>">
                Visite Aujourd'hui : <?= $db->getTodayVisit() ?>
            </div>
        <?php elseif( 'on' == $instance[ 'maxViewers' ] ) : ?>
            <div style="<?= $this->getStyle(); ?>">
                Visite total : <?= $db->getMaxVisit() ?>
            </div>
        <?php elseif( 'on' == $instance[ 'totalVisitOnPageViewers' ] ) : ?>
            <div style="<?= $this->getStyle(); ?>">
                Visite total sur la page : <?= $db->getMaxVisitOnPage(WP_Tracker_Track::getSlug()) ?>
            </div>
        <?php endif; ?>

        <div class="textwidget">
            <p><?= esc_attr( $text ); ?></p>
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
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'maxViewers' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'maxViewers' ); ?>" name="<?php echo $this->get_field_name( 'maxViewers' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'maxViewers' ); ?>">Afficher le nombre de visites total du site</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'totalVisitOnPageViewers' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'totalVisitOnPageViewers' ); ?>" name="<?php echo $this->get_field_name( 'totalVisitOnPageViewers' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'totalVisitOnPageViewers' ); ?>">Afficher le nombre de visites total de la page</label>
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
        $instance['maxViewers'] = $new_instance['maxViewers'];
        $instance['totalVisitOnPageViewers'] = $new_instance['totalVisitOnPageViewers'];

        return $instance;
    }

    private function getStyle() {
        return "@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');  font-family: 'Roboto', sans-serif;display: inline-block;background-color: #555555;padding: .50rem .50rem;color: #f5f5f5;font-weight: 500;";
    }

}
?>