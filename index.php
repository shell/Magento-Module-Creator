<?php
/**
 * Module Creator
 *
 * @category   Automator
 * @package    Standalone
 * @version    0.0.9.1
 * @author	   Daniel Nitz <n.nitz@netz98.de>
 * @copyright  Copyright (c) 2008 netz98 new media GmbH (http://www.netz98.de)
 * 			   Credits for blank files go to alistek (adam) from the community:
 * 			   http://www.magentocommerce.com/wiki/custom_module_with_custom_database_table
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * 
 * Patched by OggettoWeb team(http://oggettoweb.com)
 * @author Vladimir Penkin(penkinv@gmail.com)
 * @version 0.0.1
 * 
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * 
 * $Id$
 */

$root = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/') + 1);
$shop = null;

/**
 * Enter description here...
 *
 * @param string|array $from
 * @param string|array $to
 * @return boolean
 */
function copyBlankoFiles($from, $to, $shop = null)   
{
    global $root;
    
    if (!is_array($from)) {
        $from = array($from);
    }
    
    if (!is_array($to)) {
        $to = array($to);
    }
    
    if ($shop === null) {
        $shop = $root . 'new/';
        if (!is_dir($shop)) {
            mkdir($shop);
        }
    }
    
    if (count($from) !== count($to)) {
        throw new Exception('Count of from -> to files do not match.');
    }
    
    foreach ($to as $file) {
        $newPath = substr($file, 0, strrpos($file, '/'));
        createFolderPath($newPath, $shop);
    }

    for ($i = 0; $i < count($to); $i++) {
        if (copy($root.$from[$i], $shop.$to[$i]) === false) {
            throw new Exception('Could not copy blanko files.');
        }
    }
    return true;
}

/**
 * Enter description here...
 *
 * @param string|array $paths
 * @return bolean
 */
function createFolderPath($paths, $shop = null)
{
    global $root;
    
    if (!is_array($paths)) {
        $paths = array($paths);
    }

    if ($shop === null) {
        $shop = $root;
    }
    
    foreach ($paths as $path) {
        $folders = explode('/', $path);
        $current = '';
        
        foreach ($folders as $folder) {
            $fp = $current . DIRECTORY_SEPARATOR . $folder;
            if (!is_dir($shop.$fp)) {
                if (mkdir($shop.$fp) === false) {
                    throw new Exception('Could not create new path: '. $shop.$fp);
                }
            }
            $current = $fp;
        }
    }
    return true;
}

/**
 * Enter description here...
 *
 * @param array|string $files
 */
function insertCustomVars($files, $shop = null)
{
    global $root;
    
    if (!is_array($files)) {
        $files = array($files);
    }

    if ($shop === null) {
        $shop = $root . 'new'.DIRECTORY_SEPARATOR;
    }
    
    foreach ($files as $file) {
        $handle = fopen ($shop.$file, 'r+');
        $content = '';
        while (!feof($handle)) {
            $content .= fgets($handle);
        }
        fclose($handle);
        
        $type = strrchr($file, '.');
        switch ($type) {
            case '.xml':
                $content = replaceXml($content);
                break;
            case '.php':
            case '.phtml':
                $content = replacePhp($content);
                break;
            default:
                throw new Exception('Unknown file type found: '.$type);
        }
        $handle = fopen ($shop.$file, 'w');
        fputs($handle, $content);    
        fclose($handle);
    }
}

/**
 * Enter description here...
 *
 * @param string $content
 * @return string
 */
function replacePhp($content)
{
    global $capNamespace, $lowNamespace, $capModule, $lowModule, $capModuleadmin, $lowModuleadmin;
    
    $search = array(
                    '/<Namespace>/',
                    '/<namespace>/',
                    '/<Module>/',
                    '/<module>/',
                    '/<Moduleadmin>/',
                    '/<moduleadmin>/',
   					);
    
    $replace = array(
                    $capNamespace,
                    $lowNamespace,
                    $capModule,
                    $lowModule,
                    $capModuleadmin,
                    $lowModuleadmin,
                    );
    
    return preg_replace($search, $replace, $content);
}

/**
 * Enter description here...
 *
 * @param string $content
 * @return string
 */
function replaceXml($content)
{
    global $capNamespace, $lowNamespace, $capModule, $lowModule, $capModuleadmin, $lowModuleadmin;
    
    $search = array(
                    '/\[Namespace\]/',
                    '/\[namespace\]/',
                    '/\[Module\]/',
                    '/\[module\]/',
                    '/\[Moduleadmin\]/',
                    '/\[moduleadmin\]/',
                    );
                    
    $replace = array(
                    $capNamespace,
                    $lowNamespace,
                    $capModule,
                    $lowModule,
                    $capModuleadmin,
                    $lowModuleadmin,
                    );
    
    return preg_replace($search, $replace, $content);
}

/**
 * Enter description here...
 *
 * @param string $dir
 * @return boolean|string
 */
function checkShopRoot($dir)
{
    $dir = replaceDirSeparator($dir);
    if (substr($dir, strlen($dir) - 1, 1) !== DIRECTORY_SEPARATOR) {
        $dir .= DIRECTORY_SEPARATOR;
    }
    if (is_dir($dir . 'app')) {
        return $dir;
    }
    return false;
}

/**
 * Enter description here...
 *
 * @param unknown_type $dir
 * @return unknown
 */
function replaceDirSeparator($dir)
{
    $search = array('\\\\', '/');
    $dir = str_replace($search, DIRECTORY_SEPARATOR, $dir);
    
    return $dir;
}
/**
 * Enter description here...
 *
 * @param unknown_type $dir
 * @param unknown_type $module
 * @return unknown
 */
function uninstallModule($dir, $module)
{
    if (is_dir($dir.$module)) {
        $folder = rmRecurse($dir.$module);
        $sql = deleteSql($dir, $module);
        if ($folder and $sql) {
            return true;
        }
    }
    return false;
}

/**
 * Enter description here...
 *
 * @param unknown_type $dir
 * @return unknown
 */
function getMagentoDatabaseSettings($dir)
{
    $xml = simplexml_load_file($dir.'app/etc/local.xml', null, LIBXML_NOCDATA);
    
    $settings = array();
    $settings['dbUser'] = (string)$xml->global->resources->default_setup->connection->username;
    $settings['dbHost'] = (string)$xml->global->resources->default_setup->connection->host;
    $settings['dbPassword'] = (string)$xml->global->resources->default_setup->connection->password;
    $settings['dbName'] = (string)$xml->global->resources->default_setup->connection->dbname;
    
    return $settings;
}

/**
 * Enter description here...
 *
 * @param unknown_type $dir
 * @param unknown_type $module
 */
function deleteSql($dir, $module)
{
    $settings = getMagentoDatabaseSettings($dir);
    $connection = dbConnect($settings);

    $module = preg_replace('/\/$/', '', $module);
    $module = strtolower(substr(strrchr($module, '/'), 1));
    
    $tblPrefix = getTablePrefix($dir);
    
    $sql = "DELETE FROM ".$tblPrefix."core_resource WHERE code = '".$module."_setup'";
    $delete = mysql_query($sql);

    $sql = "DROP TABLE ".$tblPrefix.$module;
    $drop = mysql_query($sql); 
    
    dbDisconnect($connection);
    if ($delete and $drop) {
        return true;
    }
    return false;
}

/**
 * Enter description here...
 *
 * @return unknown
 */
function getTablePrefix($dir)
{
    $xml = simplexml_load_file($dir.'app/etc/local.xml', null, LIBXML_NOCDATA);
    $prefix = (string)$xml->global->resources->db->table_prefix;
    if ($prefix != '') {
        return $prefix.'.';
    }
    return $prefix;
}

/**
 * Enter description here...
 *
 * @param array $settings
 * @return boolean
 */
function dbConnect(array $settings)
{
    $connection = mysql_connect($settings['dbHost'], $settings['dbUser'], $settings['dbPassword']) or die
        ('Could not connect to host.');
    mysql_select_db($settings['dbName']) or die
        ('Database does not exsist.');
    
    return $connection;
}

/**
 * Enter description here...
 *
 * @param unknown_type $connection
 */
function dbDisconnect($connection)
{
    mysql_close($connection);
}

/**
 * http://de3.php.net/manual/de/function.rmdir.php
 * ornthalas at NOSPAM dot gmail dot com
 *
 * @param string $filepath
 * @return unknown
 */
function rmRecurse($filepath)
{
    if (is_dir($filepath) && !is_link($filepath)) {
        if ($dh = opendir($filepath)) {
            while (($sf = readdir($dh)) !== false) {
                if ($sf == '.' || $sf == '..') {
                    continue;
                }
                if (!rmRecurse($filepath.'/'.$sf)) {
                    throw new Exception($filepath.'/'.$sf.' could not be deleted.');
                }
            }
            closedir($dh);
        }
        return rmdir($filepath);
    }
    return unlink($filepath);
}
//--------------------------------------------------------------
/*$form = '       <h1>Magento Module Creator</h1>
                <form name="newmodule" method="POST" action="" />
                	<div class="element">
                		<div class="description">Namespace:<br /><span class="annotation">(e.g. your Company Name)</span></div>
                		<input name="namespace" class="text" type="text" length="50" value="'.$_POST['namespace'].'" />
                	</div>
                	<div id="module" class="element">
                		<div class="description">Module:<br /><span class="annotation">(e.g. Blog, News, Forum)</span></div>
                		<input name="module" class="text" type="text" length="50" value="'.$_POST['module'].'" />
                	</div>
                	<div id="moduleadmin" class="element">
                		<div class="description">Moduleadmin:<br /><span class="annotation">(e.g. Blogadmin, NewsAdmin, Control)</span></div>
                		<input name="moduleadmin" class="text" type="text" length="50" value="'.$_POST['moduleadmin'].'" />
                	</div>
                	<div id="magento_root" class="element">
                		<div class="description">Magento Root Directory:<br /><span class="annotation">(optional, required for uninstall)</span></div>
                		<input name="magento_root" class="text" type="text" length="255" value="'.replaceDirSeparator($_POST['magento_root']).'" />
                	</div>
                	<div id="interface" class="element">
                		<div class="description">Design:<br /><span class="annotation">(interface, default is \'default\')</span></div>
                		<input name="interface" class="text" type="text" length="100" value="'.$_POST['interface'].'" />
                	</div>
                	<div id="theme" class="element">
                		<div class="description">Design:<br /><span class="annotation">(theme, default is \'default\')</span></div>
                		<input name="theme" class="text" type="text" length="100" value="'.$_POST['theme'].'" />
                	</div>
                	<div class="element">
                		<div class="description">Generage admin module?:<br /><span class="annotation">(default is \'yes\')</span></div>
                		<input name="generate-admin" class="checkbox" type="checkbox" checked /> <a href="#" title="The admin module will be separated from main module">?</a>
                	</div>
                	<div id="submit">
                		<input type="submit" value="create" name="create" id="create" /> <input type="submit" value="uninstall" name="uninstall" id="uninstall" />
                	</div>
                </form>';*/

if(!empty($_POST)) {
    $namespace = $_POST['namespace'];
    $module = $_POST['module'];
    $moduleadmin = $_POST['moduleadmin'];
    $interface = $_POST['interface'];
    $theme = $_POST['theme'];
    
    if ($interface == '') {
        $interface = 'default';
    }
    
    if ($theme == '') {
        $theme = 'default';
    }
    
    if ($_POST['magento_root'] != '') {
        if (checkShopRoot($_POST['magento_root']) !== false) {
            $shop = checkShopRoot($_POST['magento_root']);
        } else {
            throw new Exception('This is not a valid Magento install dir: ' . $_POST['magento_root']);
        }
    }
    
    $capNamespace = ucfirst($namespace);
    $lowNamespace = strtolower($namespace);
    $capModule = ucfirst($module);
    $lowModule = strtolower($module);
    $capModuleadmin = ucfirst($moduleadmin);
    $lowModuleadmin = strtolower($moduleadmin);
    
    $fromFiles = array(
                        'blank/app/etc/modules/Namespace_Module.xml',
                        'blank/app/code/local/Namespace/Module/Block/Module.php',
                        'blank/app/code/local/Namespace/Module/controllers/IndexController.php',
                        'blank/app/code/local/Namespace/Module/etc/config.xml',
                        'blank/app/code/local/Namespace/Moduleadmin/etc/config.xml',
                        'blank/app/code/local/Namespace/Module/Model/Module.php',
                        'blank/app/code/local/Namespace/Module/Model/Mysql4/Module.php',
                        'blank/app/code/local/Namespace/Module/Model/Mysql4/Module/Collection.php',
						'blank/app/code/local/Namespace/Module/Model/Status.php',
                        'blank/app/code/local/Namespace/Module/sql/module_setup/mysql4-install-0.1.0.php',
                        'blank/app/design/frontend/interface/theme/layout/module.xml',
                        'blank/app/design/frontend/interface/theme/template/module/module.phtml',
                        'blank/app/code/local/Namespace/Module/Helper/Data.php',
						'blank/app/design/adminhtml/interface/theme/layout/module.xml',
                        );
    
    $toFiles = array(
                        'app/etc/modules/'.$capNamespace.'_'.$capModule.'.xml',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/Block/'.$capModule.'.php',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/controllers/IndexController.php',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/etc/config.xml',
                        'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/etc/config.xml',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/Model/'.$capModule.'.php',
    					'app/code/local/'.$capNamespace.'/'.$capModule.'/Model/Mysql4/'.$capModule.'.php',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/Model/Mysql4/'.$capModule.'/Collection.php',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/Model/Status.php',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/sql/'.$lowModule.'_setup/mysql4-install-0.1.0.php',
                        'app/design/frontend/'.$interface.'/'.$theme.'/layout/'.$lowModule.'.xml',
                        'app/design/frontend/'.$interface.'/'.$theme.'/template/'.$lowModule.'/'.$lowModule.'.phtml',
                        'app/code/local/'.$capNamespace.'/'.$capModule.'/Helper/Data.php',
                        'app/design/adminhtml/'.$interface.'/'.$theme.'/layout/'.$lowModule.'.xml'
                        );

    /* generatin admin module*/
	if ($_POST['generate-admin']) {

			$fromFiles[] = 'blank/app/etc/modules/Namespace_Moduleadmin.xml';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/Block/Module.php';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/Block/Module/Edit.php';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/Block/Module/Grid.php';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/Block/Module/Edit/Form.php';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/Block/Module/Edit/Tabs.php';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/Block/Module/Edit/Tab/Form.php';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/controllers/ModuleController.php';
			$fromFiles[] = 'blank/app/code/local/Namespace/Moduleadmin/Helper/Data.php';
		
			$toFiles[] = 'app/etc/modules/'.$capNamespace.'_'.$capModuleadmin.'.xml';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/Block/'.$capModule.'.php';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/Block/'.$capModule.'/Edit.php';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/Block/'.$capModule.'/Grid.php';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/Block/'.$capModule.'/Edit/Form.php';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/Block/'.$capModule.'/Edit/Tabs.php';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/Block/'.$capModule.'/Edit/Tab/Form.php';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/controllers/'.$capModule.'Controller.php';
			$toFiles[] = 'app/code/local/'.$capNamespace.'/'.$capModuleadmin.'/Helper/Data.php';
	}                        
                        
                        
     if ($_POST['create']) {
         if (!empty($module) && !empty($namespace)) {
             copyBlankoFiles($fromFiles, $toFiles, $shop);
             insertCustomVars($toFiles, $shop);
            
             $message = '<div id="message"><p><strong>New Module successfully created!</strong></p>
        		<p>Go to the folder where this file is located. You\'ll find a new folder called \'new\'.</p>
        		<p>Within are all required files for your new module. This folder has the same structure as your Magento Installation.
        		Just make sure you replace the \'interface\' and \'theme\' folder with your current design path. If you want to add custom
        		DB-fields go to /new/local/'.$capNamespace.'/'.$capModule.'/sql/module_setup/mysql4-install-0.1.0.php
        		and make your changes for line 12 to 14.</p><p>Copy /new/'.$capNamespace.'_'.$capModule.'.xml to /app/etc/modules/. If you
        		chose a Magento Install dir, all files can be found in their according directory.
        		Implement your module functionallity and you\'re done!</p>
        		<p><strong>List of created files:</strong></p>';
                 foreach ($toFiles as $file) {
                     $message .= '<p class="file">' . $file . '</p>';
                 }
        		$message .= '</div>';
         } else {
             $message = '<div id="message"><p>Please fill out out required fields.</p></div>';
         }
     }
     if ($_POST['uninstall']) {    
         if (uninstallModule($shop, 'app/code/local/'.$capNamespace.'/'.$capModule.'/') === true) {
             $message = '<div id="message"><p><strong>Module successfully uninstalled!</strong></p></div>';
         } else {
             $message = '<div id="message"><p><strong>Couldn\'t find module in Magento installation.</strong></p>
             			<p>After creating a module, you need to run Magento to install all new required tables
             			automatically. Also make sure you deactivate/refresh all Magento caches. Otherwise
             			no new modules will be recognized.</p></div>';
         }
     }
    
} else {
    $message = '<div id="message">To create a new module, insert Namespace and a Module name (e.g. Blog, Forum, etc.) as well as
    			your design above. If you want it to be installed right away into your Magento, enter your Magento install path.
    			This script will create a simple news module on which you can build your own module.</div>';
}

	include('header.php');
	//print $form; 
	print $message;
	include('footer.php');

	
	
	
	