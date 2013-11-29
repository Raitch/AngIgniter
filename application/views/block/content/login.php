<?ang_form_validate('login', TRUE)?>
<form <?=ang_attr('form')?> class="rows" ng-submit="postForm()" ng-show="ang.require_login">
	
	<label <?=ang_form_label('username')?>><?=t($arr_login['lang_username'])?></label>
	<input type="text" <?=ang_attr('input', $arr_login['post_username'])?> />
	<?=ang_form_error($arr_login['post_username'])?>

	<label <?=ang_form_label('password')?>><?=t($arr_login['lang_password'])?></label>
	<input type="password" <?=ang_attr('input', $arr_login['post_password'])?> />
	<?=ang_form_error($arr_login['post_password'])?>
	
	<button type="submit" <?=ang_attr('input', $arr_login['post_login'])?> value="true">
		<?=t('form_login')?>
	</button>

</form>
<?ang_end('validate')?>

<div ng-hide="ang.require_login">
	<h1><?=t('login_logged_in_head')?></h1>
	<p>	<?=t('login_logged_in_body')?></p>
</div>