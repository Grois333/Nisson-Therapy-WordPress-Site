/**
 * Services Block JavaScript
 * Handles scroll-based blur effect on service cards
 */

(function() {
	'use strict';

	function initServicesBlur() {
		const serviceCards = document.querySelectorAll('.service-card');
		
		if (serviceCards.length === 0) {
			return;
		}

		function updateCardBlur() {
			const viewportCenter = window.innerHeight / 2;
			const scrollOffset = window.pageYOffset || document.documentElement.scrollTop;

			serviceCards.forEach((card) => {
				const cardRect = card.getBoundingClientRect();
				const cardCenter = cardRect.top + (cardRect.height / 2) + scrollOffset;
				const cardTop = cardRect.top + scrollOffset;
				const cardBottom = cardTop + cardRect.height;

				// Calculate distance from viewport center
				const currentScroll = window.pageYOffset + viewportCenter;
				const distanceFromCenter = Math.abs(currentScroll - cardCenter);
				
				// Calculate blur intensity based on distance
				// When card is at center (distance = 0), blur = 0
				// When card is far from center, blur = max
				const maxDistance = window.innerHeight * 1.2; // Increased threshold - cards stay clearer longer
				const blurIntensity = Math.min(distanceFromCenter / maxDistance, 1);
				const blurValue = blurIntensity * 4; // Reduced max blur from 8px to 4px
				const opacityValue = 0.75 + (1 - blurIntensity) * 0.25; // Increased base opacity from 0.6 to 0.75

				// Apply blur and opacity
				card.style.filter = `blur(${blurValue}px)`;
				card.style.opacity = opacityValue;

				// Add/remove classes for CSS transitions
				if (blurIntensity < 0.15) {
					card.classList.remove('blurred');
					card.classList.add('focused');
				} else {
					card.classList.remove('focused');
					card.classList.add('blurred');
				}
			});
		}

		// Initial update
		updateCardBlur();

		// Update on scroll with throttling
		let ticking = false;
		function onScroll() {
			if (!ticking) {
				window.requestAnimationFrame(() => {
					updateCardBlur();
					ticking = false;
				});
				ticking = true;
			}
		}

		window.addEventListener('scroll', onScroll, { passive: true });
		window.addEventListener('resize', updateCardBlur);
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initServicesBlur);
	} else {
		initServicesBlur();
	}
})();

