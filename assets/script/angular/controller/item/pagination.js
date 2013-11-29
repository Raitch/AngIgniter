myApp.controller('ItemPagination', ['$scope', function($scope) {
	
	$scope.current	=	function(page) {

		return (page == this.cur_page);

	};

}]);