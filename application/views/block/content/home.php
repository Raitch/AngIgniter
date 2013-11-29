<h1><?=ang_value('user.username')?></h1>
<h2><?=ang_value('user.name')?></h2>
<?=ang_value('ang.greeting')?>

<ul>
	<? foreach(ang_repeat('ang.items', 'item') as $arr_item): ?>
	<li <?=ang_attr('repeat')?>>
		<?=ang_value('item.name', $arr_item)?>
	</li>
	<? endforeach; ?>
</ul>