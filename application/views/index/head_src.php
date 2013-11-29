<script>
	<?=$str_ang_data?>
</script>

<?=$this->custom_minify->parse($this->angularjs->is_robot())?>
<? $this->general->view->load_block('content_script') ?>