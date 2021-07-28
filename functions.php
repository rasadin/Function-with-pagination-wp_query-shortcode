function publications_with_country_name($atts){
	extract(shortcode_atts(array(
	   'jurisdiction' => '', //shortcode theke valu asbe
	), $atts));

	$the_query = new WP_Query( array(
	'post_type' => 'publications',
<!-- 	'posts_per_page' => 1,
    	'paged' =>  get_query_var( 'paged' ), -->
	'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
	'meta_query'    => array(
		array(
			'key'       => 'jurisdiction',
			'value'     => $jurisdiction, //shortcode theke je valu receive hoyse
			'compare'   => 'LIKE',
		),
	),
));
	if ( $the_query->have_posts() ) : 

		
		$i = 0;
		$return_html = '<div class="publications-home-page-post"> <div class="publications-post-middle-content" id="js-publications-post-appender">';
		foreach( $the_query->posts as $post ): 
			$show_in_home_page = get_post_meta($post->ID, 'show_in_home_page', true);
			$file_type = get_post_meta($post->ID, 'file_type', true);
			$publication_image = get_post_meta($post->ID, 'publication_image', true);
			$im = wp_get_attachment_image_src( $publication_image, 'full' );
			$no_im = home_url('/wp-content/uploads/2021/03/no-file-image.png');

			// if ($show_in_home_page == 1) {
			if ($im != '') {
			$return_html .= '
			<article id="post-'.$post->ID.'" class="publications-post-single-content publications-masonary-item">
				<div class="publications-ok">
					<div class ="publications-type">
						<span class="publications-blogpost-read sub-'.$i.'">'.$file_type.'</span>
					</div>
					<div class="publications-blogpost publications-latest-news">
						<div class="pub-img pub-'.$i.'"><img src='.$im[0].' alt="Image for publications"></div>
						<h3 class="publications-blogpost-title"> <a href=" '.get_the_permalink($post->ID).'">'. get_the_title($post->ID).'</a></h3>
					</div>
				</div>
			</article>
				'; 
			}else{
			$return_html .= '
			<article id="post-'.$post->ID.'" class="publications-post-single-content publications-masonary-item">
				<div class="publications-ok">
					<div class ="publications-type">
						<span class="publications-blogpost-read sub-'.$i.'">'.$file_type.'</span>
					</div>
					<div class="publications-blogpost publications-latest-news">
						<div class="pub-img pub-'.$i.'"><img src='.$no_im .' alt="No image for publications"></div>
						<h3 class="publications-blogpost-title"> <a href=" '.get_the_permalink($post->ID).'">'. get_the_title($post->ID).'</a></h3>
					</div>
				</div>
			</article>
				';
			}
		// }
		$i++;
		endforeach;
	$return_html .= '</div></div>';
	$big = 999999999; // need an unlikely integer
	 $pagination = paginate_links( array(
			'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $the_query->max_num_pages
	) );
	$return_html .= $pagination;
	endif;
	wp_reset_query();
	return $return_html;
}
add_shortcode('publications_with_country_name_shortcode', 'publications_with_country_name');

<!-- ex: [publications_with_country_name_shortcode jurisdiction="Thailand"] -->
