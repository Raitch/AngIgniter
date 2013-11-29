<?=ang_ctrl($str_class)?>
<div <?=ang_attr('ctrl', TRUE, 'ang')?> ng-show="active()">
	<?php $this->load->view($str_view) ?>
</div>
<?ang_end('ctrl')?>