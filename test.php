<!doctype html>
<html ng-app>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.8/angular.min.js"></script>
		<script>
			function Handle($scope) {
				
				$scope.items	=	[
					{ 'name' : 'Tja' }
				];
				
				$scope.derps	=	[1, 2, 4, 5];
				
				$scope.addItem	=	function() {
					$scope.items.push({ 'name' : $scope.name });
					$scope.name	=	'';
				}
				
			}
		</script>
	</head>
	<body>
		
		<h1>Create stuff</h1>
		
		<div ng-controller="Handle">
			
			<form ng-submit="addItem()">
				
				<input type="text" ng-model="name" placeholder="Name" />
				<button type="submit">Add</button>
				
			</form>
			
			<ul>
				<li ng-repeat="item in items">
					<span>{{item.name}}</span>
				</li>
			</ul>
			
			<span ng-repeat="int in derps">{{int}}<br /></span>
			
		</div>
		
	</body>
</html>