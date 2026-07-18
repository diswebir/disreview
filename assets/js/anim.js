/* DisReview entrance animation for comments/reviews. */
(function () {
	'use strict';

	if (!('IntersectionObserver' in window)) {
		return;
	}

	function init() {
		var items = document.querySelectorAll('.disreview-active .comment, .disreview-active .review, .disreview-active .edd-review');
		if (!items.length) {
			return;
		}

		items.forEach(function (el) {
			el.classList.add('dr-anim-in');
		});

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.15 });

		items.forEach(function (el) {
			observer.observe(el);
		});
	}

	if (document.readyState !== 'loading') {
		init();
	} else {
		document.addEventListener('DOMContentLoaded', init);
	}
})();
