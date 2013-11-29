<? if ($arr_classes): ?>
<script>
	<? foreach($arr_classes as $str_class): ?>
	myApp.controller('<?=$str_class?>', function() {
		
	});
	<? endforeach; ?>
</script>
<? endif; ?>