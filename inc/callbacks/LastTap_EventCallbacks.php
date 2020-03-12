<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class LastTap_EventCallbacks extends LastTap_BaseController

{

       public function lt_sanitize_number($number){
    return preg_replace('/[^0-9]/', '', $number);

    }

    public function formatDate($date, string $format = "F j Y H:i"){
        return $newData = date($format, strtotime($date));

    }

    public function comments($value='')
    {
            global $post;

         if ( 'open' == $post->comment_status ) : ?>
        
        <div id="respond">
        
        <h3><?php comment_form_title(); ?></h3>
        
        <?php cancel_comment_reply_link(); ?>
        
        <?php if ( get_option( 'comment_registration' ) && !$user_ID ) : ?>
        
        <p><?php _e('You must be','last-tap-events');?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('logged in</a> to post a comment.', 'last-tap-events');?></p>
        
        <?php else : ?>
        
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        
        <?php if ( $user_ID ) : ?>
        
        <p><?php esc_html_e('Logged in as','last-tap-events');?> <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php esc_attr_e(' Log out of this account','last-tap-events');?>"><?php esc_html_e('Log out &raquo;','last-tap-events');?></a></p>
        
        <?php else : ?>
        
        <p>
        <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
        <label for="author"><?php _e('Name ','last-tap-events');?><?php if ( $req ) echo "( required )"; ?></label>
        </p>
        
        <p>
        <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
        <label for="email"><?php _e('Email', 'last-tap-events');?> ( <?php if ( $req ) echo "required, "; ?><?php _e(' never shared', 'last-tap-events' );?></label>
        </p>
        
        <p>
        <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
        <label for="url"><?php _e('Website','last-tap-events');?></label>
        </p>
        
        <?php endif; ?>
        
        <p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>
        
        <p><?php _e('Some HTML is ok', 'last-tap-events');?>: <code><?php echo allowed_tags(); ?></code></p>
        
        <p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment', 'last-tap-events');?>" /></p>
        <?php do_action( 'comment_form', $post->ID ); comment_id_fields(); ?>
        
        </form>
        
        <?php endif; // If registration required and not logged in ?>
        </div>
        
        <?php endif; // If comments are open: delete this and the sky will fall on your head 
    }

}