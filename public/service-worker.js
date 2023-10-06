const cacheName = 'laravel-pwa-cache';

const filesToCache = [
  '/',
  '/css/app.css',
  '/landing/travel/css/animate.css',
	'/landing/travel/css/owl.carousel.min.css',
	'/landing/travel/css/owl.theme.default.min.css',
	'/landing/travel/css/magnific-popup.css',
	'/landing/travel/css/bootstrap-datepicker.css',
	'/landing/travel/css/jquery.timepicker.css',
	'/landing/travel/css/flaticon.css',
	'/landing/travel/css/style.css',
'/js/crypto.js',
  '/js/app.js',
  '/landing/travel/js/jquery.min.js',
  '/landing/travel/js/jquery-migrate-3.0.1.min.js',
  '/landing/travel/js/popper.min.js',
  '/landing/travel/js/bootstrap.min.js',
  '/landing/travel/js/jquery.easing.1.3.js',
  '/landing/travel/js/jquery.waypoints.min.js',
  '/landing/travel/js/jquery.stellar.min.js',
  '/landing/travel/js/owl.carousel.min.js',
  '/landing/travel/js/jquery.magnific-popup.min.js',
  '/landing/travel/js/jquery.animateNumber.min.js',
  '/landing/travel/js/bootstrap-datepicker.js',
  '/landing/travel/js/scrollax.min.js',
  '/landing/travel/js/google-map.js',
'/landing/travel/js/main.js',
  '/images/pms-sistem-1.png',
  '/images/pms-sistem-1.png',
  '/images/pms-sistem-1.png',
  '/images/pms-sistem-1.png'
];

self.addEventListener('install', function (e) {
  e.waitUntil(
    caches.open(cacheName).then(function (cache) {
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener('fetch', function (e) {
  e.respondWith(
    caches.match(e.request).then(function (response) {
      return response || fetch(e.request);
    })
  );
});
