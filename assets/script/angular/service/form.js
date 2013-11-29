myApp.factory('form', ['navigation', function(navigation) {
	
	var service	=	{
		'clear'	:	function(form, fields) {

			var dom;
			for (var h in fields) {

				dom	=	this.dom(form.ctrl, fields[h]);
				if (dom.type == 'password') {

					dom.value	=	'';

					form.ang[fields[h]]	=	null;
					

				}
				
			}

			form['input'].$setPristine();

		},
		'dom'	:	function(ctrl, model) {

			return document.querySelector('*[ng-controller="' + ctrl + '"] form *[ng-model="ang.' + model + '"]');

		},
		'getData'	:	function(form, fields) {

			var data	=	{};
			for (var h in fields)
				data[fields[h]]	=	this.getDomValue(this.dom(form.ctrl, fields[h]), form);

			return data;

		},
		'getDomValue'	:	function(dom, form) {

			switch (dom.nodeName.toLowerCase()) {
				case 'select':

					var elem	=	angular.element(dom);
					var value	=	elem.val();

					/* Extract list used for select */
					var list	=	elem.attr('ng-options').replace(/^.+\bin\s+ang\.([\w_]+).*?$/, "$1");
							list	=	form.ang[list];
					
					if (typeof value == 'object') {

						var arr	=	[];
						for (var i in value)
							arr.push(list[value[i]].key);
						
						return arr;

					}

					return list[value];
				default:

					return dom.value;
			}

		},
		'post'		:	function(form, fields, keep_data) {
			
			if ( ! fields)
				fields	=	form.fields;

			var data	=	{};
			var fallback	=	this.getData(form, fields);
			for (var h in fields)
				data[fields[h]]	=	(form.ang[fields[h]] || fallback[fields[h]]);

			var action	=	document.location.href;
			if (typeof form.action != 'undefined')
				action	=	form.action;

			var query	=	angular.copy(data);

			if ( ! keep_data)
				this.clear(form, fields);

			navigation.post(action, query);
			
		}
	};

	return service;
	
}]);