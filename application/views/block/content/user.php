<h1><?=t('user_index_title', ang_value('ang.name'))?></h1>

<?ang_form_validate('user', TRUE)?>
<form <?=ang_attr('form')?> class="rows" ng-submit="postForm()">

	<label <?=ang_form_label('name')?>><?=t('register_name')?></label>
	<input type="text" <?=ang_attr('input', 'name')?> />
	<?=ang_form_error('name')?>
	
	<button type="submit" ng-disabled="isLoading()"><?=t('form_update')?></button>
	
</form>
<?ang_end('validate')?>