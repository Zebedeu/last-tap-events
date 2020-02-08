<?php


/* 
Template Name: Event MemberShip
*/

get_header();

$current_user = wp_get_current_user();


if(! ( $current_user instanceof WP_User ) ) {
                return;
  }

$name = $current_user->display_name;
$full_name = $current_user->user_firstname. $current_user->user_lastname;
$lastTap_user_id = $current_user->ID;
if( !$full_name) {
	$full_name = $name;
}

$email = $current_user->user_email;

?>
<!-- <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->

<div class="lastTap  container">
	<div class="lastTap  col-lg-12 ">
		<?php if( ! $current_user->ID ){?>
			<h3 class="lastTap text-center"><a href="<?php echo wp_login_url( esc_url( home_url( ) ) ); ?>">Login</a></h3>

		<?php } else { ?>
		<div class="lastTap  row">
			<div class="lastTap  col-lg-3">
				<div class="col-md-12">
                <?php echo get_avatar( $email, 220, null, null, array( 'class'=> 'lastTap rounded img-responsive pl-4') ); ?>
                </div>
			<div class="col-md-12">
				<div class="lastTap col-lg-12 card">
					<a href="<?php echo wp_lostpassword_url( home_url() );?>"><?php _e('Reset Password','last-tap-events');?></a>
					<a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e('Logout','last-tap-events');?></a>
				</div>
			</div>
			</div>
			<div class="lastTap  col-lg-9">
				<div class="lastTap  row">
					<div class="lastTap  col-lg-12">
						<div class="col-md-12">
							<h4><?php esc_html_e('Name', 'last-tap-events');?>: <?php echo $full_name; ?></h4>
							<h4><?php esc_html_e('Email', 'last-tap-events');?>: <?php echo $email; ?></h4>
<!-- 		                    <ul class="social-network social-circle">
		                        <li><a href="#" class="lastTap-icon icoRss" title="Rss"><i class="fa fa-rss"></i></a></li>
		                        <li><a href="#" class="lastTap-icon icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
		                        <li><a href="#" class="lastTap-icon icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
		                        <li><a href="#" class="lastTap-icon icoGoogle" title="Google +"><i class="fa fa-google-plus"></i></a></li>
		                        <li><a href="#" class="lastTap-icon icoLinkedin" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
		                    </ul> -->				
							
						</div>
						<div class="lastTap  col-lg-12">
							<ul class="lastTap  nav nav-tabs" id="myTab" role="tablist">
								<li class="lastTap nav-item">
									<a class="lastTap nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php _e('Events','last-tap-events');?></a>
								</li>
										  <!-- <li class="lastTap nav-item">
										    <a class="lastTap nav-link" id="profile-tab" data-toggle="tab" href="#perfil" role="tab" aria-controls="profile" aria-selected="false">Perfil</a>
										  </li> -->
										  <!-- <li class="lastTap nav-item">
										    <a class="lastTap nav-link" id="contact-tab" data-toggle="tab" href="#contato" role="tab" aria-controls="contact" aria-selected="false">Contato</a>
										  </li> -->
							</ul>
							<div class="lastTap tab-content" id="myTabContent">
								<div class="lastTap tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
								<?php 
									$partys = get_posts(array('post_type' => 'participant'));
						             foreach ($partys as $key => $party) {
						                	  $get_data_participants = get_post_meta( $party->ID, '_event_participant_key', false );
						                	  foreach ($get_data_participants as $key => $get_data_participant) {
						                	   	if($lastTap_user_id == $get_data_participant['lastTap_user_id'] || '1' == $get_data_participant['approved'] ) {
						                	    $get_event_detal = get_post_meta($get_data_participant['post_event_id'], '_event_detall_info', true); 
												$get_event_post = get_post( $get_data_participant['post_event_id'], ARRAY_A );
												$event_permalink = get_permalink($get_event_post['ID']);
												$event_emage = get_the_post_thumbnail($get_event_post['ID'], 'thumbnail');


								?>
													      		
										<div class="col-md-12">
											<div class="well well-sm">
												<div class="row">
													<div class="col-xs-3 col-md-3 text-center">
													<?php
														if(! $event_emage){?>
															<img class="lastTap card-img-top" src="https://res.cloudinary.com/marciozebedeu/image/upload/v1576563110/no-image-available-icon-6_lmeii0.png" />
														     <?php }else{
														            echo $event_emage;
														                   			} ?>
													</div>
													<div class="lastTap col-xs-9 col-md-9 section-box">
														<h2>
														    <?php echo $get_event_post['post_title'];?> 
														    <a href="<?php  echo esc_url($event_permalink);?>" ><span class="lastTap glyphicon glyphicon-new-window"></span><?php _e('View Event', 'last-tap-events');?></a>
														</h2>
														<p>
														   <?php echo $get_event_post['post_excerpt'];?>
														    <hr />
													</div>
												</div>
											</div>
										</div>
													<?php
													     		}

						                	  				}

						                				}
						                			?>

						          </div> <!-- tab home -->
									<div class="lastTap tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="profile-tab">
										<div class="wrap">
						    				<h1>Atomic Smash - WordPress PDF Generator</h1>
						    				<p>Click below to generate a pdf from the content inside all the WordPress Posts. </p>
						    				<p>Each post will be on its own pdf page containing the post title and post content.</p>
											<form method="post" id="lastTap-fdpf-form">

						        				<button class="button button-primary" type="submit" name="generate_posts_pdf" value="generate">Generate PDF from WordPress Posts</button>
						    				</form>
						    			</div>
				  					</div>
				  					<div class="lastTap tab-pane fade" id="contato" role="tabpanel" aria-labelledby="contact-tab">3</div>
						</div>
					</div>
				</div>
			</div>	
		</div>
	<?php } ?>
					<!-- <div class="lastTap col-lg-3">
						<?php //esc_html_e('My Profile', 'last-tap-events');?> 
					</div> -->
	</div><!-- end row -->
</div>

<?php get_footer(  ); 