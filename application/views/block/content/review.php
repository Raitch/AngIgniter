<?ang_form_validate('review', TRUE)?>
<form <?=ang_attr('form')?> class="rows" ng-submit="postForm()">
	
	<label <?=ang_form_label('author')?>><?=t('register_author')?></label>
	<input type="text" <?=ang_attr('input', 'author')?> />
	<?=ang_form_error('author')?>

	<label <?=ang_form_label('type')?>><?=t('register_type')?></label>
	<?=ang_form_dropdown('type', 'types')?>
	<?=ang_form_error('type')?>

	<label <?=ang_form_label('text')?>><?=t('register_text')?></label>
	<textarea <?=ang_attr('input', 'text')?>></textarea>
	<?=ang_form_error('text')?>
	
	<button type="submit" ng-disabled="isLoading()"><?=t('form_post')?></button>
	
</form>
<?ang_end('validate')?>

<ul>
	<? foreach(ang_repeat('ang.items.list', 'item') as $h): ?>
	<li <?=ang_attr('repeat')?>>
		<strong><?=ang_value('item.author', $h)?> <?=t('review_index_list_type', ang_value('item.type'))?></strong>
		<p><?=ang_value('item.text', $h)?></p>
	</li>
	<? endforeach; ?>
</ul>

<? $this->angularjs->pagination->load_view() ?>