<?php

error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}


// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
	// 'debug' => true,
	'bind' => array(
 		'upload.presave' => array(
 			'Plugin.AutoResize.onUpLoadPreSave'
 		)
 	),
 	// global configure (optional)
 	'plugin' => array(
 		'PluginAutoResize' => array(
 			'enable'         => true,       // For control by volume driver
 			'maxWidth'       => 1024,       // Path to Water mark image
 			'maxHeight'      => 1024,       // Margin right pixel
 			'quality'        => 95,         // JPEG image save quality
 			'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP // Target image formats ( bit-field )
 		)
 	),
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          => '../../../../../uploads/',                 // path to files (REQUIRED)
			//'URL'           => dirname($_SERVER['PHP_SELF']) . '/../../../upload/', // windows URL to files  creer un alias ou host virtuel pour resoudre sa (REQUIRED)
			'URL'           => '/uploads/', // Linux URL to files (REQUIRED). Fonctionne avec le dossier du serveur surlequel le domaine pointe. pour symfony en fonction de la position du repertoire web de symfony sur le serveur ex: si symfony se trouve dans app/web URL = /app/web/uploads/
			'plugin' => array(
 				'PluginAutoResize' => array(
 					'enable'         => true,       // For control by volume driver
 					'maxWidth'       => 1024,       // Path to Water mark image
 					'maxHeight'      => 1024,       // Margin right pixel
 					'quality'        => 95,         // JPEG image save quality
 					'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP // Target image formats ( bit-field )
 				)
 			),
			//'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
			//'uploadAllow'   => array('image', 'text/plain'),// Mimetype `image` and `text/plain` allowed to upload
			//'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
			'uploadOverwrite' => false,
			'uploadMaxSize'   => '25M',	// vérifier aussi la variable post_max_size de php.ini .	
			'accessControl' => 'access',                     // disable and hide dot starting files (OPTIONAL)
			//'tmbPath' => '/../../../tmb/',
			//'tmbURL' => dirname($_SERVER['PHP_SELF']) . '/../../../tmb/',
			'attributes' => array(
				array( // hide readmes
                    'pattern' => '/_thumbs/',
                    'read' => false,
                    'write' => false,
                    'hidden' => true,
                    'locked' => true
                )
            ),
		)
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

