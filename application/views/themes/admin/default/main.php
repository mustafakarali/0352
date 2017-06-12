<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />

	<title><?=$BC->_getPageTitle()?></title>

	<?=include_css('css/zero.css')?>
	<?=include_css('css/css3-icons.css')?>
	<?=include_css($BC->_getTheme().'styles.css')?>

	<?foreach ($BC->_getCSSFiles() as $css_file):?>
    <?=include_css($css_file)?>
    <?endforeach?>

    <?=load_inline_js('inc/js-IE-fix')?>
    <?=load_inline_js('inc/js-jquery')?>
    <?=load_inline_js('inc/js-flash-msg')?>

    <?=load_inline_js('inc/js-proxino'); ?>
    <?//=load_inline_js('inc/js-qbaka'); ?>

    <?=include_css($BC->_getTheme().'navmenu-h.css')?>
    <?=include_js($BC->_getTheme().'navmenu-h.js')?>

    <!-- No-Cache -->
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

</head>

<body>

    <div id="header">

    	<div id="header_left">
    		<?load_theme_view('inc/navmenu-h')?>
    	</div>

    	<div id="header_right">
    		<b class="title"><?=language('section')?>:  <?=$BC->_getPageTitle()?></b>

    		<ul id="menu-right"><?=$BC->_built_right_menu()?></ul>
    	</div>

    </div>

    <div class="clear"></div>

    <div id="outer">

    	<?load_theme_view($tpl_page);?>

    </div>

    <div id="footer">
    	<p>Page rendered in {elapsed_time}. Memory usege {memory_usage}.</p>
    </div>

</body>
</html>