myApp.controller('ItemMessage', ['$scope', '$timeout', function($scope, $timeout) {
	
	$scope.duration	=	4000;

	var close;
	$scope.trackUpdate	=	function() {
		
		if (typeof $scope.message == 'undefined')
			return false;

		$timeout.cancel(close);

		close	=	$timeout(function() {
			
			ang[$scope.ctrl].hide	=	true;
			$scope.$digest();

		}, $scope.duration);
		
		return true;

	};

}]);