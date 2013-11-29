myApp.factory('navigation', ['$rootScope', '$http', function($rootScope, $http) {
	
	var service	=	{
		'post'	:	function(url, query) {
			
			if (typeof query == 'undefined')
				query	=	{};
			
			query.angularjs		=	true;
			query.current_url	=	ang.url;
			
			$http.post(url, query)
				.success(function(data) {
					
					if (typeof data == 'object') {

						if (typeof data.redirect == 'string')
							service.url(data.redirect);
						else {

							ang	=	angular.extend(ang, data);
							
							if (data.url != document.location.href)
								history.pushState({}, '', data.url);
							
						}

					}

				});
			
		},
		'url'	:	function(href) {
			
			this.post(href);
			
		}
	};
	
	return service;
	
}]);