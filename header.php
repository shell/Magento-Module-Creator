<html>
<head>
    <title> Module Creator </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="Magento, Varien, E-commerce, Module Creator, developement" />
	<link rel="stylesheet" type="text/css" href="style.css" media="all" />
	<script src="js/prototype.js" type="text/javascript"></script>
	<script src="js/effects.js" type="text/javascript"></script>
	<script src="js/moduleCreator.js" type="text/javascript"></script>
</head>
<body>
	<div id="main">
	<h1>Magento Module Creator</h1>
                <form name="newmodule" method="POST" action="" />
                	<div class="element">
                		<!--<div class="description">Namespace:<br /><span class="annotation">(e.g. your Company Name)</span></div>-->
                		<label class="description" for="namespace">Namespace:<br /><span class="annotation">(e.g. your Company Name)</span></label>
                		<input id="namespace" name="namespace" class="text" type="text" length="50" value="<?php echo @$_POST['namespace'] ?>" />
                	</div>
                	<div id="module" class="element">
                		<!-- <div class="description">Module:<br /><span class="annotation">(e.g. Blog, News, Forum)</span></div> -->
                		<label class="description" for="name">Module:<br /><span class="annotation">(e.g. Blog, News, Forum)</span></label>
                		<input id="name" name="module" class="text" type="text" length="50" value="<?php echo @$_POST['module'] ?>" />
                	</div>
                	
                	<div id="magento_root_container" class="element">
                		<!--<div class="description">Magento Root Directory:<br /><span class="annotation">(optional, required for uninstall)</span></div> -->
                		<label class="description" for="magento_root">Magento Root Directory:<br /><span class="annotation">(optional, required for uninstall)</span></label>
                		<input id="magento_root" name="magento_root" class="text" type="text" length="255" value="<?php echo replaceDirSeparator(@$_POST['magento_root']) ?>" />
                	</div>
                	<div id="interface_container" class="element">
                		<!--  <div class="description">Design:<br /><span class="annotation">(interface, default is \'default\')</span></div> -->
                		<label class="description" for="interface">Design:<br /><span class="annotation">(interface, default is \'default\')</span></label>
                		<input id="interface" name="interface" class="text" type="text" length="100" value="<?php echo @$_POST['interface'] ?>" />
                	</div>
                	<div id="theme_container" class="element">
                		<!-- <div class="description">Design:<br /><span class="annotation">(theme, default is \'default\')</span></div> -->
                		<label class="description" for="theme">Design:<br /><span class="annotation">(theme, default is \'default\')</span></label>
                		<input id="theme" name="theme" class="text" type="text" length="100" value="<?php echo @$_POST['theme'] ?>" />
                	</div>
                	<div class="generate-admin_container">
                		<!-- <div class="description">Generage admin module?:<br /><span class="annotation">(default is \'yes\')</span></div> -->
                		<label class="description" for="generate-admin">Generage admin module?:<br /><span class="annotation">(default is \'yes\')</span></label>
                		<input id="generate-admin" name="generate-admin" class="checkbox" type="checkbox" checked /> <a href="#" title="Admin module will be separated from main module">?</a>
                	</div>
                	<div id="moduleadmin_container" class="element">
                		<!--<div class="description">Moduleadmin:<br /><span class="annotation">(e.g. Blogadmin, NewsAdmin, Control)</span></div> -->
                		<label class="description" for="moduleadmin">Moduleadmin:<br /><span class="annotation">(e.g. Blogadmin, NewsAdmin, Control)</span></label>
                		<input id="moduleadmin" name="moduleadmin" class="text" type="text" length="50" value="<?php echo @$_POST['moduleadmin'] ?>" />
                	</div>
                	<div id="submit">
                		<input type="submit" value="create" name="create" id="create" /> <input type="submit" value="uninstall" name="uninstall" id="uninstall" />
                	</div>
                </form>