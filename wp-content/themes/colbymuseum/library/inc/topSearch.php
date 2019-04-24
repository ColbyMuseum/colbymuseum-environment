<?php

	require_once("/web/prod/colby/wp-load.php"); // Needed for calling Wordpress functions...

?>
<div class="topSlideDown" style="display:none;height:auto;">
	<div class="container-fluid well text-center">
		<div id="top-close">
		<a class="close" href="javascript:void(0)" onclick="jQuery('.topSlideDown').slideUp(600);">Close</a>
		</div>
		<div class="tabbable">
			<ul class="nav nav-tabs">
			  	 <li id="top-search" class="<?php echo isset($_GET['activeTab']) && $_GET['activeTab'] == '1'?' active':'';?>"><a href="#tab1" data-toggle="tab">Search Colby Magazine</a></li>
			  	 <li id="top-directory" class="<?php echo isset($_GET['activeTab']) && $_GET['activeTab'] == '2'?' active':'';?>"><a href="#tab2" data-toggle="tab">Search Colby.edu</a></li>
			  </ul>
			<div class="tab-content">
			  	<div class="tab-pane<?php echo isset($_GET['activeTab']) && $_GET['activeTab'] == '2'?' active in':'';?>" id="tab2">
			  	  <h2 id="search-title" class="page-title">Search</h1>
			      <form class="form-search" action="/search/" method="GET">
			      <?php
			      	// -- Autocomplete Code --
			      	// Create JSON object for typeahead values, pulled from the 'About' site RSS feed for a_z_terms for the site
			      	$aboutBlog = $blog_details = get_blog_details('about');

				  	switch_to_blog($aboutBlog->blog_id);

			      	$jsonValue = '[';
			      	$args = array('post_type' => 'a_z_terms','posts_per_page' => '-1');
			      	$aboutquery = new WP_Query( $args );

			      	if ( $aboutquery->have_posts() ) {
						while ( $aboutquery->have_posts() ) {
							$aboutquery->the_post();
							if($jsonValue != '[')
								$jsonValue .= ',';
							$jsonValue .= "{'TERMURL' : '".addslashes(get_permalink())."','TERMTEXT' : '".addslashes(get_the_title())."'}";
						}
					}
			      	$jsonValue .= ']';
					
					restore_current_blog();
				  	wp_reset_postdata();
				  	?>
				  	  <script>
				  	  	var searchTerms = <?php echo $jsonValue;?>;
				  	  	var objs = [];
				  	  	jQuery(document).ready(function(){
							jQuery('.topSlideDown #tab1 #searchBox').typeahead({
								source: function (query, process) {								    
								    var termarray = [];
								    var data = searchTerms;
								    jQuery.each(data, function (i, searchTerm) {
								        termarray.push(searchTerm.TERMTEXT);
								        objs.push(searchTerm);
								    });
								    process(termarray);
								},
								matcher: function (item) {
									query = this.query;
									for(x=0;x<searchTerms.length;x++){
										if(item == searchTerms[x].TERMTEXT){
											if (searchTerms[x].TERMTEXT.toLowerCase().indexOf(query.toLowerCase()) != -1) {
									        	return true;
											}
										}
									}
								},
								updater: function (item) {
									for(i=0;i<searchTerms.length;i++){
										if(item == searchTerms[i].TERMTEXT){
											selectedURL = searchTerms[i].TERMURL;
						
										    if(selectedURL.length){
											    window.location = selectedURL;
												return item;
											}
											else
												jQuery("#tab1 form").submit();
											
										}
									}
								}	
							})
						});
				  	  	
				  	  </script>

					  <div class="input-append"><?php
						  if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT']))
						  	echo 'Search for: ';
					  ?>
					  	  <script>
						  </script>
						  <input id="searchBox" type="text" class="input-large span2" placeholder="Search for..." autofocus="autofocus" autocomplete = "off" name="q">
						  <button class="btn" type="submit"><i class="icon-search"></i></button>
				  		  </div>
				  		  <div id="directoryLinks" class="clearfix" style="border-top:0;">
							<a href="/about/a_z_terms/">A-Z Index</a> | <a href="/search/">Advanced Search</a> |  <a href="/academics/majors-minors/">Areas of Study</a>
						</div>
					</form>
			    </div>
			    <div class="tab-pane<?php echo isset($_GET['activeTab']) && $_GET['activeTab'] == '1'?' active in':'';?>" id="tab1">
			    	<h2>College Directory</h2>
			    	<form class="form-search" action="/magazine/">
					  <div class="input-append">
						  <input id="searchDirectoryBox" type="text" class="input-large span2" placeholder="Enter search term..." autofocus="autofocus" name="s">
						  <input type="hidden" name="post_type" value="post" />
						  <button class="btn" type="submit"><i class="icon-search"></i></button>
			  		  </div>
					</form>
					
			    </div>

			</div>		  
		</div>
	</div>
</div>