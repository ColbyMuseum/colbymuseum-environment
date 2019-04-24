<?php
	$printView = (isset($_GET['print']) || isset($_GET['renderforprint'])?true:false);
?>
			<?php
		          $pageTemplate = get_page_template();
		          if (is_active_sidebar('footer1') && (strpos($pageTemplate,'/page-three-column.php')===false )){
		          ?>
		            <?php
		            	$pageTemplate =  get_page_template();

		            	if(strpos($pageTemplate,'/page-three-column.php')!==false || strpos($pageTemplate,'homepage')!==false) { ?>
			            	<div id="siteFooter">
					          <div id="inner-footer" class="clearfix">
					          <div id="widget-footer" class="clearfix row-fluid">
		            	<?php
			            	dynamic_sidebar('footer1');?>
			            	</div>
					</div>
			            	<?php
			            }
						?>

				</div>

					<?php
					}?>
			</div>

<?php
	if(!$printView) {?>
	<div class="push"></div>
<?php
}?>
	</div>
			<?php
			if(!$printView){?>
			<footer id="mainFooter">

			<div id="footerWrapper"><?php
				if ( ! is_front_page() ) : ?>
				Colby College Museum of Art 5600 Mayflower Hill, Waterville, Maine 04901 | <a href="tel://207-859-5600">207-859-5600</a> | <a href="mailto:museum@colby.edu">museum@colby.edu</a>
				<?php
				else :
						?>
					<div id="footerConnect">
						<a title="Support" href="museum/support" id="joinButton"><img src="<?php echo get_stylesheet_directory_uri();?>/images/support.png" alt="Support" /></a>
						<a title="Museum Newsletter" href="https://visitor.r20.constantcontact.com/d.jsp?llr=86ila46ab&p=oi&m=1131690697659&sit=6pp6uq6mb&f=4af3dcef-a3b3-4e4c-b030-31e4318e3e2b" id="newsletter-channel">
							<svg class=newsletter-icon>
								<use xlink:href="#newsletter-icon"></use>
							</svg>
						</a>
						</a>
						<a title="Facebook" href="http://www.facebook.com/colbymuseum" id="facebook-channel">
							<svg class=facebook-icon>
								<use xlink:href="#facebook-icon"></use>
							</svg>
						</a>
						</a>
						<a title="Twitter" href="http://www.twitter.com/colbymuseum" id="twitter-channel">
							<svg class=twitter-icon>
								<use xlink:href="#twitter-icon"></use>
							</svg>
						</a>
						<a title="Vimeo" href="http://www.vimeo.com/colbymuseum" id="vimeo-channel">
							<svg class=vimeo-icon>
								<use xlink:href="#vimeo-icon"></use>
							</svg>
						</a>
						<a title="Instagram" href="http://www.instagram.com/colbymuseum" id="instagram-channel">
							<svg class=instagram-icon>
								<use xlink:href="#instagram-icon"></use>
							</svg>
						</a>
					</div>
						<?php
				endif;
					?>
			</div>
				<div class="clearfix">&nbsp;</div>
			</footer>
			<?php
			}?>
		<!--[if lt IE 7 ]>
  			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
  			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		<![endif]-->
		<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri();?>/library/js/respond.min.js"></script><![endif]-->
		<?php

				if(is_single() || is_post_type_archive() || true) { ?>
				<script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo 'ra-52274afd3385fbef'; ?>"></script>
			<?php
			}?>
		<?php
			wp_footer();
			include 'assets/svg/sprite.svg';
