<!doctype html>
<html lang="en" ng-app="myApp">
<head>
	<meta charset="utf-8">
	<title><?=$str_index_title?></title>
    <?php $this->load->view('index/head_src') ?>
</head>
<?ang_ctrl('Body')?>
<body <?=ang_attr('ctrl', FALSE, 'user')?>>

<?php $this->angularjs->message->load_view() ?>

<div id="container">
	<a id="banner" href="<?=site_url()?>">
		
	</a>
	<?php $this->load->view('index/menu') ?>
	<?ang_ctrl('Content')?>
	<div id="content" <?=ang_attr('ctrl')?>>
		<?php $this->general->view->load_block('content', $str_index_content) ?>
	</div>
	<?ang_end('ctrl')?>
</div>

</body>
<?ang_end('ctrl')?>
</html>