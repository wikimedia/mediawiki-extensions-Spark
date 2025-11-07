<?php

/**
 * Static class for hooks handled by the Spark extension.
 *
 * @since 0.1
 *
 * @ingroup Spark
 *
 * @license GPL-3.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class SparkHooks {

	/**
	 * Register the spark tag extension when the parser initializes.
	 *
	 * @since 0.1
	 *
	 * @param Parser &$parser
	 */
	public static function onParserFirstCallInit( Parser &$parser ) {
		$parser->setHook( 'spark', [ __CLASS__, 'onSparkRender' ] );
	}

	/**
	 * @since 0.1
	 *
	 * @param mixed $input
	 * @param array $args
	 * @param Parser $parser
	 * @param PPFrame|null $frame
	 * @return array|string
	 */
	public static function onSparkRender( $input, array $args, Parser $parser, $frame = null ) {
		$parser->getOutput()->addModules( [ 'ext.spark' ] );

		$tag = new SparkTag( $args, $input );

		// PPFrame maybe not existing
		return $tag->render( $parser, $frame );
	}

}
