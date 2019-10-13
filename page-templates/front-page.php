<?php


get_header(); ?>


    <div id="main-content" class="ch-col-9 main-content" style="background:#fff;">

        <?php

        ?>
        <div id="primary" class="content-area">
            <div id="content" class="ch-col-12 site-content" role="main">
                <?php
                /*
                 * The WordPress Query class.
                 *
                 * @link http://codex.wordpress.org/Function_Reference/WP_Query
                 */
                $args = array(


                    // Choose ^ 'any' or from below, since 'any' cannot be in an array
                    'post_type' => array(
                        'sermon'
                    )

                );

                $query = new WP_Query($args);

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        ?>
                        <div class="ch-row ch-col-6" style="color: #000;">
                            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                            <?php
                            the_excerpt();
                            $args = array(
                                'before' => '<div class="page-links-XXX"><span class="page-link-text">' . __('More pages: ', 'textdomain') . '</span>',
                                'after' => '</div>',
                                'link_before' => '<span class="page-link">',
                                'link_after' => '</span>',
                                'next_or_number' => 'next',
                                'separator' => ' | ',
                                'nextpagelink' => __('Next &raquo', 'textdomain'),
                                'previouspagelink' => __('&laquo Previous', 'textdomain'),
                            );

                            wp_link_pages($args);
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