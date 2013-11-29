myApp.directive('matches', ['form', function(form) {
	
	return {
		'restrict'	:	'A',
		'link'			:	function(scope, elem, attr) {
			
			if (typeof scope.ctrl != 'undefined') {

				var match_elem	=	form.dom(scope.ctrl, attr.matches);

				angular.element(match_elem).bind('change', function(e) {

					elem.attr('pattern', '^' + ang_helper.escapeRegExp(e.currentTarget.value) + '$');

				});

			}

		}
	};
	
}]);