<?php
	/*
		Post template for events...
	*/
	// Vars...
	global $title;
	global $event;
	global $post;

	$events = new colbyEvents();
	
	if(!isset($event)){
		// Event isn't set. Check to see if there is an rID, bID combo associated with this event.
		if(get_field('event_information_source')!=""){
		
			if(get_field('ems_event')!=""){
				$fieldvals = explode(",",get_field('ems_event'));
				
				$eventDetails = $events->getEventDetails($fieldvals[0],$fieldvals[1]);
				
				if(count($eventDetails))
					$event = $eventDetails->event;
			}
		}
	}
	
	if(isset($event)){
		// EMS record...set variables based on returned data.
		$event['starttime'] = $events->formatColbyTime($event['starttime']);
		$event['endtime'] = $events->formatColbyTime($event['endtime']);
		$postStartDate = $event['startdate'] . ' ' .$event['startyear']. ', '.$event['starttime'];
		$postStartDate = date('F j, Y',strtotime($postStartDate));
		$postStartTime = $event['starttime'];
		$postEndTime = $event['endtime'];
		$postEndDate = $event['enddate'] . ' ' .$event['endyear'].', '.$event['endtime'];
		$postLocation = $event['building'].(strlen(trim($event['room']))?(' / '.$event['room']):'');
		
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $event->description) as $line){
			$postDescription .= trim($line) . '<br> ';
		} 
		
		$postDescription = str_ireplace('<br> <br>','<br />',$postDescription);
		
		// The Regular Expression filter for looking for links and change the URL to a link...
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		if(preg_match($reg_exUrl, $postDescription, $url)) {

		       // make the urls hyper links
		       $postDescription = preg_replace($reg_exUrl, '<a href="'.$url[0].'">'.$url[0].'</a>', $postDescription);
		}
		$postDescription = str_replace('<br>','',$postDescription);
		$status = trim($event['status_id']);
		$postPrivacy = 'Public event';
		
		if($status=="2" || $status=="4" || $status == "13" || $status=="18")
			$postPrivacy = 'Private event';
		
		if($status=="27")
			$postPrivacy = 'Event is by invitation only.';
		
		if($status=="11")
			$postPrivacy = 'Open to the Colby community';
		$wpid ='0';
		
		if(intval($event['wp_id']) > 0 && ($event['wp_id']) != '' && is_numeric(intval($event['wp_id']))){		
			// WordPress ID set on EMS side...

			$post = get_post(intval($event['wp_id']));
			if($post->post_status != 'trash' && $post->post_status != 'draft'){

				$wpid = $event['wp_id'];
				$temptitle = get_the_title($wpid);
				if(strlen(trim($temptitle)))
					$title = $temptitle;
			}
			else{
				$wpid = 0;
			}
		}
		
		// WPID may not be set in WordPress, but may be set from back-end...
		// Check DB for ems_event field that contains RID, BID combo
		$eventOverride = colbyEvents::featuredOverrideCheck($event['rID'],$event['bID']);
		if($eventOverride !== false){
			if(count($eventOverride)){
				$eventOverride = $eventOverride[0];
				if($title != get_the_title() && strlen($title))
					$title = $eventOverride->post_title;			// Title is probably already output...
//				$title = '';
				$wpid = $eventOverride->ID;
				$postDescription = $eventOverride->post_content;
				$postDescription = apply_filters('the_content', $postDescription);
				$postDescription = str_replace(']]>', ']]>', $postDescription);
			}
		}
	}
	else{
		// Event field (EMS) not set. Pull the fields for manual WP events...
		if(get_field('event_information_source')=="Manual"){
			$postEndDate = $postStartDate = get_field('event_datetime');
			$postLocation = get_field('event_location');						
			if( $postLocation != 'William D. Adams Gallery, Museum Lobby' && stripos( $postLocation, 'William D. Adams') === false )  {
				$postLocation = str_ireplace('Lobby', 'William D. Adams Gallery, Museum Lobby', $postLocation);	
			}
			
			$status = 1;
			$postPrivacy = get_field('event_access');
			$postStartTime = colbyEvents::formatColbyTime(date('g:i a',strtotime($postStartDate)));
			$postEndTime = colbyEvents::formatColbyTime(date('g:i a',strtotime($postStartDate . ' + 1 hour')));
		}		
	}

	if(is_single() && $wpid <= 0)
		$wpid = $post->ID;

	wp_register_style( 'eventcss', plugin_dir_url('colbyEvents' ). 'colbyEvents/css/events.css', array(), $themeDetails->Version, 'all' );	
    wp_enqueue_style( 'eventcss' );
	
	
	if(function_exists('isAjax') &&  isAjax()){
		?>
		<script>
		var win=null;
		function printIt(printThis) { 
			win = window.open();
			self.focus();
			win.document.open();
			win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
			win.document.write('body, td { font-family: Verdana; font-size: 10pt;}');
			win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
			win.document.write(printThis);
			win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
			win.document.close();
			win.print();
			win.close();
			}
		</script>	
		<?php
	} 
	?>
	
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
							<div id="eventArea">
							<?php
							if(strlen($title)){?>
							<div class="page-header"><h1 class="single-title" itemprop="headline"><?php echo $title;?></h1></div>
							<?php
							}?>
						
						<?php
								$postTemplate = false;						?>
							</div>
						</header>
	<div id="eventArea">

<p class="meta<?php echo ($status == 3 || $status==4)?' canceled':'';?>">	
<?php

//running query in order to find if this event post has a duplicate
$args = array( 'cat' => '12', 'orderby' => 'meta_value_num', 'meta_key' => 'start_date', 'order' => 'ASC', 'posts_per_page' => 75,
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'start_date',
				'value' =>  strtotime(date('Y-m-d',strtotime('now'))),	/* BRG added strtotime and changed to NUMERIC */
				'type' => 'NUMERIC',
				'compare' => '>='
			),
			array(
				'key' => 'event_datetime',
				'value' =>  date('Y-m-d',strtotime('now')),
				'type' => 'DATE',
				'compare' => '>='
			)
		));

$eventQuery = new WP_Query( $args );

$loop = $eventQuery->get_posts();

$colbyevent = new colbyEvents();
$repeats = $colbyevent->find_duplicate_events($loop, get_the_title());

if(strlen($postStartDate) && count($repeats) <= 1){?>
<div id="shareBottom" class="addthis_toolbox addthis_default_style pull-right span2">

<?php 
	$likeuri = $_SERVER['REQUEST_URI'];
	$likeuri = str_ireplace( '&print=1&ajax=1','',$likeuri );
        $likeuri = str_ireplace( '=','',$likeuri );
        $likeuri = str_ireplace( '"','',$likeuri );
        $likeuri = str_ireplace( "'",'',$likeuri );
        $likeuri = str_ireplace( "%22",'',$likeuri );
?>
		
			<a href="http://www.addthis.com/bookmark.php" class="addthis_button" style="text-decoration:none;">
				<a class="addthis_button_facebook_like" fb:like:layout="button_count"  data-url="<?php echo "http://".$_SERVER['HTTP_HOST'].$likeuri; ?>"></a>
		    
		</div>
 <date class=""><?php echo date('l, F j, Y',strtotime($postStartDate)); ?></date>, <time><?php echo $postStartTime; ?></time>
 <?php
 }
 else {
	 if(count($repeats) == 0) {
		 header("HTTP/1.0 404 Not Found");
		 echo '<date><em>No date/location information could be found for this event. It may need to be added to EMS.</em></date>';
		}
 }

$endTime = get_field('event_endtime');
if( !empty($endTime) ) {
	echo '- '.trim( $events->formatColbyTime($endTime) );
}

 if(trim($postStartDate) != trim($postEndDate) && false){
 	 if(trim($event['startdate']) == trim($event['enddate'])){
 	 	$postEndDate = '';
 	 }
 	 else
		 $postEndDate = date('l, F j, Y',strtotime($postEndDate));	 
 ?>
 - <date class=""><?php echo $postEndDate;?></date> <time><?php echo $event['endtime']; ?></time>
 <?php
 }
 
 
 if($status == 3 || $status==4){
	 echo '<div class="error">Event canceled</div>';
 }
 else{
	
	echo '<div class="calendar-left" style="margin-right:20px;">';
	echo '<div class="calendar-left-day">'.date('j',strtotime($postStartDate)).'</div>';
	echo '<div class="calendar-left-month">'.date('M',strtotime($postStartDate)).'</div>';
	echo '</div>'; 
	 
	$eventTemp = new colbyEvents();
 	$curID = get_the_ID();
 	
	if (count($repeats) > 1) {
		// Set array to store values...
		$sortArray = array();
		
		foreach($repeats as $repeat) {
			$sortArray[strtotime(strip_tags($eventTemp->getEventStartDate($repeat)))] = $repeat;
		}
		
		ksort($sortArray);	// Sort by date/time
		$curDate = '';
		
		foreach($sortArray as $repeats) {
			$eventDate = $eventTemp->getEventStartDate($repeats);
			
			if($curDate != date('m/d/Y',strtotime(strip_tags($eventDate)))) {
				if($curDate != '')
					echo '<br />';
					
				$curDate = date('m/d/Y',strtotime(strip_tags($eventDate)));
				echo $eventDate;
			}
			else {
				// Same date. just output the time...
				echo ', ' . $events->formatColbyTime(date('g:i a',strtotime(strip_tags($eventDate))));
			}
	 	}				 	
	}
	
	echo '<br />';
	
 	echo $postLocation;
 	
 	wp_reset_postdata();
 } ?>
</p>
<hr style="margin:0; padding:0; margin-top: 20px; margin-bottom: 20px;clear:both;" />
			
<section class="post_content clearfix" itemprop="articleBody">
	<div class="event-description">
	<?php 
	// Display content. If there isn't any content, output the description from EMS...
	if(strlen(trim(strip_tags($post->post_content))) || $event['featured']=='yes' && !isset($postDescription)){

		if(isset($post) && !isset($eventOverride)){
			$postDescription = $post->post_content;
			$postDescription = apply_filters('the_content', $postDescription);
			$postDescription = str_replace(']]>', ']]>', $postDescription);
			echo $postDescription;
		}
		if(isset($eventOverride)){
			$postDescription = $eventOverride->post_content;
			$postDescription = apply_filters('the_content', $postDescription);
			$postDescription = str_replace(']]>', ']]>', $postDescription);
			echo $postDescription;
		}
	}
	else{
		echo $postDescription; 
		
	}?>
	</div>
	<hr />
	<?php
	if($title=='')
		$title = strip_tags(get_the_title());
	?>
	<div class="event-privacy"><?php echo $postPrivacy;?></div>
	<?php
if(strlen($postStartDate)){?>
	<div class='event-subscribe'><span>Add event to:</span> <a class="gCal" href="http://www.google.com/calendar/event?action=TEMPLATE&amp;text=<?php echo urlencode($title);?>&amp;dates=<?php echo date("Ymd\THi00",strtotime(date('m/d/Y',strtotime($postStartDate))." ".$postStartTime)); ?>/<?php echo date("Ymd\THi00",strtotime(date('m/d/Y',strtotime($postEndDate))." ".$postEndTime));?>&amp;details=<?php echo urlencode(strlen($description)?$description:'Colby College');?>&amp;location=<?php echo urlencode($postLocation);?>&amp;trp=false&amp;sprop=http%3A%2F%2Fwww.colby.edu%2Fevents&amp;sprop=name:Colby%20College%20Events" target="_blank"><i class="icon-calendar"></i> Google Calendar</a><?php if(isset($event)){?><a class="iCal" href="/events/viewevent/?rID=<?php echo $event['rID'];?>&amp;bID=<?php echo $event['bID']; ?>&amp;view=ics"><i class="icon-calendar"></i> iCal</a><?php }?></div>
	<?php
	}
		if((function_exists('isAjax') && !isAjax()) || !function_exists('isAjax')){?>
		
		 
	<?php
	}
	else
		if(is_page())
			echo '</div>';
	
	?>
	
	<?php wp_link_pages(); ?>
	</section> <!-- end article section -->
												
	<footer>

		<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","bonestheme") . ':</span> ', ' ', '</p>'); ?>
		
	</footer> <!-- end article footer -->

</article> <!-- end article -->
