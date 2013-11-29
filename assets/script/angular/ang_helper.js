var ang_helper	=	{
	'escapeRegExp'	:	function(str) {

		return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
		
	},
	'getCtrl'	:	function(ctrl) {
		
		var appElement	=	document.querySelector('[ng-controller="' + ctrl + '"]');
		
		return appElement;
		
	},
	'getScope'	:	function(elem) {
		
		var $scope		=	angular.element(elem).scope();
		
		return $scope;
		
	},
	'runScope'	:	function(func, $scope) {
		
		$scope.$apply(func($scope));
		
	}
};