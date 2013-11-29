myApp.controller('ItemForm', ['$scope', 'form', function($scope, form) {
	
	angular.extend($scope, form);

	$scope.postForm	=	function() {

		form.post(this);

	};

}]);