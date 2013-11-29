<?ang_ctrl($str_ctrl)?>
<div id="message" <?=ang_attr('ctrl', TRUE)?> ng-show="trackUpdate()" <?=ang_attr('class', 'hide')?>>
	<div>
		<?=ang_value('message')?>
	</div>
</div>
<?ang_end('ctrl')?>