<?php
/**
 * Initialization file for the Spark extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:Spark
 * Support					https://www.mediawiki.org/wiki/Extension_talk:Spark
 * Source code:			 	http://svn.wikimedia.org/viewvc/mediawiki/trunk/extensions/Spark
 *
 * @file Spark.php
 * @ingroup Spark
 *
 * @licence GNU GPL v3+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * This documentation group collects source code files belonging to Spark.
 *
 * @defgroup Spark Spark
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

// We also want to support versions below 1.17
if ( version_compare( $wgVersion, '1.15', '<' ) ) {
	die( '<b>Error:</b> Spark requires MediaWiki 1.15 or above.' );
}

define( 'Spark_VERSION', '0.3.0 alpha' );

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Spark',
	'version' => Spark_VERSION,
	'author' => array(
		'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Spark',
	'descriptionmsg' => 'spark-desc'
);

// $wgExtensionAssetsPath does possibly not exist.
$egSparkScriptPath = ( (!isset($wgExtensionAssetsPath) || $wgExtensionAssetsPath === false) ? $wgScriptPath . '/extensions' : $wgExtensionAssetsPath ) . '/Spark';

$wgMessagesDirs['Spark'] = __DIR__ . '/i18n';

$wgAutoloadClasses['SparkHooks'] = dirname( __FILE__ ) . '/Spark.hooks.php';
$wgAutoloadClasses['SparkTag'] = dirname( __FILE__ ) . '/Spark.class.php';

// We have resource loader
$wgResourceModules['ext.spark'] = array(
	'localBasePath' => dirname( __FILE__ ),
	'remoteBasePath' => $egSparkScriptPath,
	'styles' => array(),
	'scripts' => array( 'rdf-spark/jquery.spark.js' ),
	'dependencies' => array(),
	'messages' => array()
);


$wgHooks['ParserFirstCallInit'][] = 'SparkHooks::onParserFirstCallInit';

require_once 'Spark.settings.php';
