/**
 * Main Theme JavaScript
 */

(function() {
	'use strict';

	// Mobile menu toggle
	const menuToggle = document.querySelector('.menu-toggle');
	const navMenu = document.querySelector('.nav-menu');

	if (menuToggle && navMenu) {
		menuToggle.addEventListener('click', function() {
			navMenu.classList.toggle('active');
			const isExpanded = navMenu.classList.contains('active');
			menuToggle.setAttribute('aria-expanded', isExpanded);
		});

		// Close menu when clicking outside
		document.addEventListener('click', function(event) {
			if (!menuToggle.contains(event.target) && !navMenu.contains(event.target)) {
				navMenu.classList.remove('active');
				menuToggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	// Smooth scroll for anchor links
	document.querySelectorAll('a[href^="#"]').forEach(anchor => {
		anchor.addEventListener('click', function(e) {
			const href = this.getAttribute('href');
			if (href !== '#' && href.length > 1) {
				const target = document.querySelector(href);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({
						behavior: 'smooth',
						block: 'start'
					});
				}
			}
		});
	});

})();

