				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article ">
						
						<header>							
							<?php
								$permalink = get_the_permalink();
								$theTitle = get_the_title();
								
								
								if( in_category( 'In the News' )) {
									$theTitle = '';
								}
								
								if( in_category( 'Recent Acquisitions' )) {
								//	$permalink = get_field( 'link_url' );
								}
								
								if( has_post_thumbnail() ){ 
									?>
									<a href="<?php echo $permalink; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php
										the_post_thumbnail( 'thumbnail',array('class'=>'alignright')); 
									?></a><?php
								}	
								?>
							<h4><a href="<?php echo $permalink; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo $theTitle; ?></a></h4>
							<?php
								if(in_category('News') && !in_category('In the News')) {
									echo '<p class="meta">'.the_date().'</p>';
								}	
							?>
						</header>
						<section class="post_content">
							<?php
								if(in_category('In the News')) {
									$contentTemp = get_the_content();
									switch (substr_count($contentTemp,'<a href=')) {
										case 0:
											$contentTemp = '<h4>'.$contentTemp;
											$contentTemp = str_replace( '</a>','</a></h4>', $contentTemp);
											break;
											
										case 1:
											$contentTemp = str_replace( '<a href=','<h4><a href=', $contentTemp);
											$contentTemp = str_replace( '</a>','</a></h4>', $contentTemp);
											break;
									}
									
									echo strip_tags($contentTemp,'<br><a><b><i><em><strong><h4>'); 
									
								}
								else {
									$excerpt = strip_tags(get_the_excerpt(),'<i><em><b><strong>');

									echo wp_trim_words($excerpt,35,'... <a href="'.get_the_permalink().'">more&nbsp;></a>');
								}
							?>					
						</section>
						
						<footer>
							
						</footer>
					
					</article>