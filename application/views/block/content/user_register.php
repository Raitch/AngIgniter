<h1><?=t('user_register_headline')?></h1>

<?ang_form_validate('register', TRUE)?>
<form <?=ang_attr('form')?> class="rows" ng-submit="postForm()" ng-hide="user.id">
	
	<label <?=ang_form_label('username')?>><?=t('register_username')?></label>
	<input type="text" <?=ang_attr('input', 'username')?> />
	<?=ang_form_error('username')?>
	
	<label <?=ang_form_label('password')?>><?=t('register_password')?></label>
	<input type="password" <?=ang_attr('input', 'password')?> title="<?=t('condition_password')?>" />
	<?=ang_form_error('password')?>
	
	<label <?=ang_form_label('password_confirm')?>><?=t('register_password_confirm')?></label>
	<input type="password" <?=ang_attr('input', 'password_confirm')?> title="<?=t('condition_password_confirm')?>" />
	<?=ang_form_error('password_confirm')?>
	
	<button type="submit" ng-disabled="isLoading()"><?=t('form_register')?></button>
	
</form>
<?ang_end('validate')?>

<div ng-show="user.id">
	<?=t('user_register_logged_in')?>
</div>