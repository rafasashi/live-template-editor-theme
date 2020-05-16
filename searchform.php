<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    
	<div class="input-group">
		
		<input type="search" class="form-control search-field form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'ltple-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search for:', 'label', 'ltple-theme' ); ?>">

		<div class="input-group-append">
		
			<button type="submit" class="search-submit btn btn-primary p-0 pl-2 pr-2">
				<i class="fa fa-search fa-lg"></i>
			</button>
			
		</div>
		
	</div>
	
</form>



