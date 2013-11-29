myApp.controller('Content', ['$scope', '$http', function($scope, $http) {

	$scope.active	=	function() {
		
		return (ang.main == this.ctrl);
		
	};
	
	$scope.isLoading	=	function() {
		
		return $http.pendingRequests.length !== 0;
		
	};
	
	$scope.gotContent	=	function(e) {

		return Boolean(e.srcElement.innerHTML);

	};

}]);