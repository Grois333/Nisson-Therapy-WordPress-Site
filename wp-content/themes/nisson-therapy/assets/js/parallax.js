/**
 * Parallax Effect for Hero Section
 */

(function() {
	'use strict';

	function initParallax() {
		const heroBlocks = document.querySelectorAll('.hero-parallax .hero-background');
		
		if (heroBlocks.length === 0) {
			return;
		}

		let ticking = false;

		function updateParallax() {
			heroBlocks.forEach(function(block) {
				const rect = block.getBoundingClientRect();
				const windowHeight = window.innerHeight;
				
				// Only apply parallax if element is in viewport
				if (rect.bottom >= 0 && rect.top <= windowHeight) {
					const scrolled = window.pageYOffset;
					const rate = scrolled * 0.5; // Adjust speed here (0.5 = 50% of scroll speed)
					
					block.style.transform = 'translateY(' + rate + 'px)';
				}
			});
			
			ticking = false;
		}

		function requestTick() {
			if (!ticking) {
				window.requestAnimationFrame(updateParallax);
				ticking = true;
			}
		}

		window.addEventListener('scroll', requestTick);
		window.addEventListener('resize', requestTick);
		
		// Initial call
		updateParallax();
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initParallax);
	} else {
		initParallax();
	}

})();

