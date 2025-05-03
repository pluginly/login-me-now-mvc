// When the route changes, we need to update wp-admin's menu with the correct submenu & menu link.
window.astWpMenuClassChange = function ( path ) {
	const pageSlug = lmn_admin.home_slug;
	let pageUrl = 'admin.php?page=' + pageSlug;

	if ( 'custom-layouts' === path ) {
		pageUrl = 'admin.php?page=' + pageSlug + '&path=custom-layouts';
	}
	const currentItemsSelector = `.wp-submenu-wrap li > a[href$="${ pageUrl }"]`;

	const currentItems = document.querySelectorAll( currentItemsSelector );

	/* Remove current */
	Array.from( document.getElementsByClassName( 'current' ) ).forEach(
		function ( item ) {
			if ( item.parentElement.classList.contains( 'wp-submenu' ) ) {
				item.classList.remove( 'current' );
			}
		}
	);

	/* Add current */
	Array.from( currentItems ).forEach( function ( item ) {
		item.parentElement.classList.add( 'current' );
	} );
};
