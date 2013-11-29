myApp.controller('Body', ['$scope', function($scope) {
	
	$scope.startWatching	=	function() {

		var $this	=	this;

		if (typeof $this.sync_key != 'undefined' && $this.sync_ang != 'undefined') {

			if ( ! $this.sync_ang)
				if ($this.ctrl)
					$this.sync_ang	=	$this.ctrl;
				else
					$this.sync_ang	=	$this.sync_key;

			var sync	=	function(newValue) {
				
				if ( ! $this.sync_key)
					angular.extend($this, newValue);
				else
					$this[$this.sync_key]	=	newValue;

			};

			if (typeof ang[$this.sync_ang] != 'undefined')
				sync(ang[$this.sync_ang]);

			$this.$watch(function() {

				if (typeof ang[$this.sync_ang] != 'undefined')
					return ang[$this.sync_ang];

				return null;

			}, function(newValue, oldValue) {
				
				if (oldValue === newValue)
					return;

				sync(newValue);

			}, true);
					
		}

	};

}]);