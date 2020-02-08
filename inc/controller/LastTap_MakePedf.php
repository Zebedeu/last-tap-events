<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$pdf = new PDF_HTML('P','mm','A5');

if( isset($_POST['generate_posts_pdf'])){
    output_pdf();
}

add_action( 'admin_menu', 'lt_fpdf_create_admin_menu' );
function lt_fpdf_create_admin_menu() {
    $hook = add_submenu_page(
        'tools.php',
        'LT PDF Generator',
        'LT PDF Generator',
        'manage_options',
        'event_pdf',
        'lt_fpdf_create_admin_page'
    );
}

function output_pdf() {
	 $defaults = array(
        'numberposts'      => 5,
        'category'         => 0,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'include'          => array(),
        'exclude'          => array(),
        'meta_key'         => '',
        'meta_value'       => '',
        'post_type'        => 'participant',
        'suppress_filters' => true,
    );
    $posts = get_posts( $defaults );

    if( ! empty( $posts ) ) {
        global $pdf;
        $title_line_height = 10;
        $content_line_height = 8;

        $pdf->AddPage();
        $pdf->SetFont( 'Arial', '', 42 );
        $pdf->Write(20, 'Events');

        foreach( $posts as $post ) {

            $pdf->AddPage();
            $pdf->SetFont( 'Arial', '', 22 );
            $pdf->Write($title_line_height, $post->post_title);

            // Add a line break
            $pdf->Ln(15);

            // Image
            $page_width = $pdf->GetPageWidth() - 20;
            $max_image_width = $page_width;

            $image = get_the_post_thumbnail_url( $post->ID );
            if( ! empty( $image ) ) {
                $pdf->Image( $image, null, null, 100 );
            }
            
            // Post Content
            $pdf->Ln(10);
            $pdf->SetFont( 'Arial', '', 12 );
            $pdf->WriteHTML($post->post_content);
        }
    }

    $pdf->Output('D','lastTap_fpdf.pdf');
    exit;
}


function lt_fpdf_create_admin_page() {
?>
<div class="wrap">
    <h1>Atomic Smash - WordPress PDF Generator</h1>
    <p>Click below to generate a pdf from the content inside all the WordPress Posts. </p>
    <p>Each post will be on its own pdf page containing the post title and post content.</p>
	<form method="post" id="lastTap-fdpf-form">
        <button class="button button-primary" type="submit" name="generate_posts_pdf" value="generate">Generate PDF from WordPress Posts</button>
    </form>
</div>
<?php
}