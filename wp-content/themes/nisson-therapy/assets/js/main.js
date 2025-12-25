/**
 * Main Theme JavaScript
 */

(function() {
	'use strict';

	// Mobile menu toggle
	const menuToggle = document.querySelector('.menu-toggle');
	const navMenu = document.querySelector('.nav-menu');
	const mainNavigation = document.querySelector('.main-navigation');

	function closeMenu() {
		if (mainNavigation) {
			mainNavigation.classList.remove('active');
		}
		if (navMenu) {
			navMenu.classList.remove('active');
		}
		if (menuToggle) {
			menuToggle.setAttribute('aria-expanded', 'false');
		}
		document.body.style.overflow = '';
	}

	function openMenu() {
		if (mainNavigation) {
			mainNavigation.classList.add('active');
		}
		if (navMenu) {
			navMenu.classList.add('active');
		}
		if (menuToggle) {
			menuToggle.setAttribute('aria-expanded', 'true');
		}
		document.body.style.overflow = 'hidden';
	}

	if (menuToggle && navMenu && mainNavigation) {
		menuToggle.addEventListener('click', function(e) {
			e.stopPropagation();
			const isActive = mainNavigation.classList.contains('active');
			
			if (isActive) {
				closeMenu();
			} else {
				openMenu();
			}
		});

		// Close menu when clicking on overlay
		mainNavigation.addEventListener('click', function(e) {
			if (e.target === mainNavigation) {
				closeMenu();
			}
		});

		// Close menu on escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && mainNavigation.classList.contains('active')) {
				closeMenu();
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

