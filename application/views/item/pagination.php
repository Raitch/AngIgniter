<?ang_ctrl($str_ctrl)?>
<div <?=ang_attr('ctrl', TRUE)?> class="pagination">
	<? foreach (ang_repeat('list', 'item') as $h): ?>
	<a <?=ang_attr('href', ang_value('base_url') . ang_value('item.page', $h))?> <?=ang_attr('class', 'current', 'current(item.page)')?> <?=ang_attr('repeat')?>>
		<?=ang_value('item.name', $h)?>
	</a>
	<? endforeach; ?>
</div>
<?ang_end('ctrl')?>