<?php

/* 
Template Name: Event Layout
*/

get_header(); ?>

<div class="container">
    <div class="row">
          <?php $the_posts = get_posts(array('post_type' => 'event'));
                            $data = array();
                        foreach ($the_posts as $key => $value) {
                            $post_id = $value->ID;

                            $event_title = get_the_title($post_id);
                            $event_permalink = get_permalink($post_id);

                            $event_thumbnail = get_the_post_thumbnail($post_id , 'event-thumb', array( 'class' => 'img-responsive', 'alt'=> 'Event Image') );
                $event_content = apply_filters('the_content' , $value->post_content);

                    $event_content = strip_shortcodes(wp_trim_words($event_content , 40 , '...'));

                           $mm =  get_post_meta( $post_id, '_lt_start_month', true );
                           $dd =  get_post_meta( $post_id, '_lt_start_day', true );
                           $yyy =  get_post_meta( $post_id, '_lt_start_year', true );
                           $start =  get_post_meta( $post_id, '_lt_start_eventtimestamp', true );
                           $end =  get_post_meta( $post_id, '_lt_end_eventtimestamp', true );

                                           $current_time = $end;
                list($end_year , $end_month , $end_day , $hour , $minute) = preg_split('([^0-9])' , $current_time);
                $current_timestamp = $end_year . '-' . $end_month . '-' . $end_day . ' ' . $hour . ':' . $minute;
                                                
                        ?>

        <div class="col-sm-3">
            <div class="panel panel-primary event-primary">
                <div class="panel-heading"><h2><a href="<?php echo $event_permalink;?>"><?php echo $event_title;?></a></h2></div>
                <div class="panel-body nopadding">
                    <?php echo $event_thumbnail; ?>
                    <div class="row nopadding">
                        <div class="col-sm-6 nopadding">
                            <time class="start pink">
                                <?php _e('Start', 'last-tap-event');?> <span class="day"><?php echo $dd;?></span>
                                <span class="month"><?php echo date('M', strtotime($start));?></span>
                                <span class="year"><?php echo $yyy; ?></span>
                            </time>
                        </div>
                        <div class="col-sm-6 nopadding">
                            <time class="end purple">
                                <?php _e('End', 'last-tap-event');?> <span class="day"><?php echo $end_day; ?></span>
                                <span class ="month"><?php echo date('M', strtotime($current_timestamp));?></span>
                                <span class="year"><?php echo $end_year;?></span>
                            </time>
                        </div>
                    </div>
                </div>
                <div class="panel-footer panel-primary">
                    <p><?php /* echo  $event_content; */ ?></p>
                    <?php get_template_part('single-event'); ?>
                    <a href="<?php echo $event_permalink;?>" class="btn btn-success"><?php _e('Read more...','last-tap-event');?></a>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
<?php 
get_footer();