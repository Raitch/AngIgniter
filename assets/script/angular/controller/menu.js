myApp.controller('Menu', ['$scope', 'navigation', function($scope, navigation) {
	
	window.onpopstate	=	function(e) {

		navigation.url(document.location.href);

	};

}]);