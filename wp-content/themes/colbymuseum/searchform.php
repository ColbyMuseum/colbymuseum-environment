<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo 'Search for'; ?></span>
		<input type="search"
			   class="search-field test"
			   placeholder="<?php echo esc_attr_x( 'Search', 'placeholder' ) ?>"
			   <?php echo ( trim( get_search_query() ) ? 'value="' . trim( get_search_query() ) . '"' : '' ); ?>
			   name="s"
			   title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
</form>
<?php 