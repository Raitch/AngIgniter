<a href="<?=site_url('user/logout')?>" class="button right" ng-show="user.id">
	<?=t('user_index_logout')?>
</a>

<?=ang_ctrl('Menu')?>
<div id="menu" <?=ang_attr('ctrl', TRUE)?>>
	<? foreach(ang_repeat('items', 'item') as $h): ?>
	<a href="<?=ang_value('item.href', $h)?>" class="<?=ang_value('item.class', $h)?>" <?=ang_attr('class', 'active', 'item.class')?> <?=ang_attr('repeat')?>>
		<?=ang_value('item.name', $h)?>
	</a>
	<? endforeach; ?>
</div>
<?ang_end('ctrl')?>