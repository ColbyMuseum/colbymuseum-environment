<?php
	get_header();
?>
	<div id="content" class="clearfix row-fluid">
		<div id="main" class="clearfix" role="main"><?php
	global $headerImage;
	$topTitle = 'File Not Found';
	$subhead = '';

?>
	<article id="post-not-found" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
		<?php $hideTitle = get_post_custom_values('hidetitle');
			echo topHeader($headerImage,$topTitle,$subhead);

		?>
		<section class="container-fluid post_content clearfix" itemprop="articleBody">
		<div class="span9 offset3">
		<?php
			echo '<p>Please check the URL and try again. You can also <a href="'.get_bloginfo('url').'/?s=">search our website</a> and archives.</p>';

		?>
		</div>
		</section>
		<footer></footer>
	</article>

				</div>
			</div>
<?php get_footer(); ?>
