				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article ">
						
						<header>
							<?php
								$permalink = get_the_permalink();
								$theTitle = get_the_title();
							?>
							<h4><a href="<?php echo $permalink; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo $theTitle; ?></a></h4>
							<?php
							
								if(has_post_thumbnail()){ 
									?>
									<a href="<?php echo $permalink; ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
									<?php
										the_post_thumbnail( 'medium',array('class'=>'')); 
									?></a><?php
								}	
								?>
						</header>
						<section class="post_content">
							<?php
								$excerpt = strip_tags(get_field('overview_top_text'),'<i><em><b><strong><h3>');
								//echo wp_trim_words($excerpt,35,'... <a href="'.get_the_permalink().'">more&nbsp;></a>');
							?>					
						</section>						
						<footer>							
						</footer>					
					</article>