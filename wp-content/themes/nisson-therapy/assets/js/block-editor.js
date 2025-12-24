/**
 * Block Editor JavaScript
 * Ensures blocks appear in Gutenberg editor
 */

(function() {
	'use strict';

	// Wait for Gutenberg to be ready
	if ( typeof wp === 'undefined' || typeof wp.domReady === 'undefined' ) {
		return;
	}

	wp.domReady(function() {
		// Verify block category exists
		const categories = wp.blocks.getCategories();
		const nissonCategory = categories.find(cat => cat.slug === 'nisson-therapy');
		
		if (!nissonCategory) {
			console.warn('Nisson Therapy: Block category "nisson-therapy" not found in editor');
		} else {
			console.log('Nisson Therapy: Block category found:', nissonCategory);
		}

		// Verify block is registered
		const blockTypes = wp.blocks.getBlockTypes();
		const heroBlock = blockTypes.find(block => block.name === 'acf/nt-hero-section');
		
		if (!heroBlock) {
			console.error('Nisson Therapy: Hero block "acf/nt-hero-section" not found in editor');
			console.log('Available ACF blocks:', blockTypes.filter(b => b.name.startsWith('acf/')));
		} else {
			console.log('Nisson Therapy: Hero block found:', heroBlock);
		}

		// Force refresh block inserter if needed
		if (typeof wp.data !== 'undefined') {
			const store = wp.data.select('core/block-editor');
			if (store) {
				console.log('Nisson Therapy: Block editor store available');
			}
		}
	});

})();

