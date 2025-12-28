/**
 * FAQ Block Accordion Functionality
 */
(function() {
	function initFAQ() {
		const faqQuestions = document.querySelectorAll('.faq-question');
		
		if (faqQuestions.length === 0) {
			return;
		}
		
		faqQuestions.forEach(function(question) {
			// Check if already initialized
			if (question.hasAttribute('data-faq-initialized')) {
				return;
			}
			
			question.setAttribute('data-faq-initialized', 'true');
			
			question.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				const targetId = this.getAttribute('data-faq-target');
				if (!targetId) {
					return;
				}
				
				const answer = document.getElementById(targetId);
				const icon = this.querySelector('.faq-icon');
				
				if (!answer || !icon) {
					return;
				}
				
				const isExpanded = this.getAttribute('aria-expanded') === 'true';
				const allQuestions = document.querySelectorAll('.faq-question');
				
				// Close all other FAQ items
				allQuestions.forEach(function(otherQuestion) {
					if (otherQuestion !== question) {
						const otherTargetId = otherQuestion.getAttribute('data-faq-target');
						if (otherTargetId) {
							const otherAnswer = document.getElementById(otherTargetId);
							const otherIcon = otherQuestion.querySelector('.faq-icon');
							
							if (otherAnswer && otherIcon) {
								otherQuestion.setAttribute('aria-expanded', 'false');
								otherAnswer.classList.remove('faq-answer-open');
								otherAnswer.setAttribute('aria-hidden', 'true');
								otherIcon.textContent = '+';
							}
						}
					}
				});
				
				// Toggle current FAQ item
				if (isExpanded) {
					this.setAttribute('aria-expanded', 'false');
					answer.classList.remove('faq-answer-open');
					answer.setAttribute('aria-hidden', 'true');
					icon.textContent = '+';
				} else {
					this.setAttribute('aria-expanded', 'true');
					answer.classList.add('faq-answer-open');
					answer.setAttribute('aria-hidden', 'false');
					icon.textContent = '−';
				}
			});
		});
	}
	
	// Run when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initFAQ);
	} else {
		// DOM is already ready
		initFAQ();
	}
	
	// Also run after a short delay to catch dynamically loaded content
	setTimeout(initFAQ, 100);
})();

