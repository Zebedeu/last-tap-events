<?php

/* 
Template Name: Location  Layout
*/

get_header(); ?>


    <div id="main-content" class="ch-col-9 main-content" style="background:#fff;">

        <?php

        ?>
        <div id="primary" class="content-area">
            <div id="content" class="ch-col-12 site-content" role="main">

                <?php

                if (have_posts()) :
                    while (have_posts()) : the_post();
                        ?>
                        <div class="ch-row" style="background:; color: #ffffff;">
                            <?php
                            the_content($more_link_text = null, $strip_teaser = false);
                            ?>
                        </div>
                    <?php endwhile;
                else :
                    get_template_part('content', 'none');
                endif;
                ?>

            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->

    <div class="ch-col-3" style="background: #fff;">
        <?php get_sidebar(); ?>
    </div>
<?php
get_footer();