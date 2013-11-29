myApp.directive('a', ['navigation', function(navigation) {
	
	return {
		'restrict'	:	'E',
		'link'		:	function(scope, elem, attr) {
			
			elem.bind('click', function(e) {
				
				var bool	=	(attr.href.substr(0, 1) == '/');
						bool	=	((attr.href.substr(0, ang.init.base_url.length) == ang.init.base_url) || bool);

				if (bool) {

					e.preventDefault();
					navigation.url(attr.href);
					
				}

			});
			
		}
	};
	
}]);