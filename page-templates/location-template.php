<?php

/* 
Template Name: Location Layout
*/

get_header(); ?>


    <div id="main-content" class="lastTap col-lg-9 main-content" style="background:#fff;">

        <?php

        ?>
        <div id="primary" class="lastTap content-area">
            <div id="content" class="lastTap col-lg-12 site-content" role="main">

                <?php

                    $args = array(
                
                        // Type & Status Parameters
                        'post_type'   => 'location',            
                    );
                
                $query = new WP_Query( $args );
                

                if (have_posts()) :
                    while (have_posts()) : the_post();
                        ?>
                        <div class="lastTap row" style="background:; color: #ffffff;">
                            <?php
                            the_content();
                            ?>
                        </div>
                    <?php endwhile;
                else :
                    // get_template_part('content', 'none');
                endif;
                ?>

            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->

    <div class="lastTap col-lg-3" style="background: #fff;">
    </div>
<?php
get_footer();