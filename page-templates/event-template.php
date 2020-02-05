<?php

/* 
Template Name: Event Layout
*/

get_header(); ?>


<div class="feedback feedback-toggle rotate">Schedule Visit</div>
<div class="feedback feedback-form-wrapper">
    <form id="feedback-form" class="form-horizontal" method="post">
        <legend>Schedule 24 Tech Support Visit</legend>
        <div class="form-group">
          <label class="sr-only">Your Name</label>
          <input id="username" name="username" class="form-control" autocomplete="off" placeholder="Your Name *" required="required" type="text">   
        </div>
    <div class="form-group">
          <label class="sr-only">Email ID</label>
          <input name="emailquery" id="emailquery" class="form-control" autocomplete="off" placeholder="Your Email *" required="required" type="email">
        </div>
        <div class="form-group">
          <label class="sr-only">Phone Number</label>
          <input name="phoneno" id="phoneno" class="form-control" autocomplete="off" placeholder="Phone Number *" required="required" type="number">
        </div>
        <div class="form-group">
            <label class="sr-only">Issue</label>
            <textarea id="issue" name="issue" class="form-control" rows="3" autocomplete="off" placeholder="Write Your Issue *" required="required"></textarea>
        </div>
        <div class="form-group">
          <label class="control-label">I need service on</label>
          <input class="form-control" name="dateOfReg" id="dateOfReg" autocomplete="off" placeholder="02/03/2014" required="required" min="2015-05-1" type="text">
        </div>
    
        <div class="form-group">
      <button type="submit" name="sendissuedetails" id="sendissuedetails" class="btn btn-default">Send Details</button>
        </div>
    </form>
</div>
<div class="lastTap col-sm-12">
    <div class="lastTap row">
          <?php $the_posts = get_posts(array('post_type' => 'event'));
                $data = array();
                foreach ($the_posts as $key => $value) {
                        $post_id = $value->ID;

                        $event_title = get_the_title($post_id);
                        $event_permalink = get_permalink($post_id);
                        $style = get_option( 'event_style_html' ) ?? 'class="lastTap img-responsive" alt="Event Image"';
                        $event_thumbnail = get_the_post_thumbnail($post_id, 'post-thumbnail', array( 'class' => 'lastTap img-responsive', 'alt'=> 'Event Image') );
                        $event_content = apply_filters('the_content', $value->post_content);
                        $event_content = strip_shortcodes(wp_trim_words($event_content, 3, '...'));

                        $mm =  get_post_meta( $post_id, '_event_detall_info', true )['_lt_start_month'];
                        $dd =  get_post_meta( $post_id, '_event_detall_info', true )['_lt_start_day'];
                        $yyy =  get_post_meta( $post_id, '_event_detall_info', true )['_lt_start_year'];
                        $start =  get_post_meta( $post_id, '_event_detall_info', true )['_lt_start_eventtimestamp'];
                        $end =  get_post_meta( $post_id, '_event_detall_info', true )['_lt_end_eventtimestamp'];
                        $current_time = $end;
                        list($end_year, $end_month, $end_day, $hour, $minute) = preg_split('([^0-9])', $current_time);
                        $current_timestamp = $end_year . '-' . $end_month . '-' . $end_day . ' ' . $hour . ':' . $minute;?>

        <div class="lastTap col-sm-3">
            <div class="lastTap panel panel-primary event-primary">
                <div class="lastTap panel-heading"><h2><a href="<?php echo $event_permalink;?>"><?php echo $event_title;?></a></h2>
                </div>
                <div class="lastTap panel-body nopadding">
                    <?php if( !$event_thumbnail ){
                           echo '<img class="lastTap '.  $style. '"  src="https://res.cloudinary.com/marciozebedeu/image/upload/v1576563110/no-image-available-icon-6_lmeii0.png" />';
                    }else {
                     echo $event_thumbnail;
                     } ?>
                    <div class="lastTap row nopadding">
                        <div class="lastTap col-sm-6 nopadding">
                            <time class="start pink"> <?php _e('Start', 'last-tap-events');?>
                                <span class="day"><?php echo $dd;?></span>
                                <span class="month"><?php echo date('M', strtotime($start));?></span>
                                <span class="year"><?php echo $yyy; ?></span>
                            </time>
                        </div>
                        <div class="lastTap col-sm-6 nopadding">
                            <time class="end purple"><?php _e('End', 'last-tap-events');?> <span class="day"><?php echo $end_day; ?></span>
                                <span class ="month"><?php echo date('M', strtotime($current_timestamp));?></span>
                                <span class="year"><?php echo $end_year;?></span>
                            </time>
                        </div>
                    </div>
                </div>
                <div class="lastTap panel-footer panel-primary">
                    <p><?php  echo  $value->post_excerpt;  ?></p>
                    <a href="<?php echo $event_permalink;?>" class="text-center lastTap btn btn-success" style="margin-left:20%; "><h4><?php _e('View Event...','last-tap-events');?></h4></a>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>
<?php 
get_footer();