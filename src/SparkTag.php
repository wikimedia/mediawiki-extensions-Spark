<?php

use MediaWiki\Html\Html;

/**
 * Class to render spark tags.
 *
 * @since 0.1
 *
 * @ingroup Spark
 *
 * @license GPL-3.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class SparkTag {

	/**
	 * List of spark parameters.
	 *
	 * @since 0.1
	 *
	 * @var array
	 */
	protected $parameters;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @var string or null
	 */
	protected $contents;

	/**
	 * @param array $args
	 * @param string $contents
	 */
	public function __construct( array $args, string $contents ) {
		$this->parameters = $this->getSparkParameters( $args );
		$this->contents = $contents;
	}

	/**
	 * Renrder the spark div.
	 *
	 * @since 0.1
	 *
	 * @param Parser $parser
	 * @param PPFrame $frame
	 *
	 * @return array|string
	 */
	public function render( Parser $parser, $frame ) {
		global $wgResourceModules;

		wfDebugLog( 'myextension', 'Parameters alright? ' . print_r( $this->parameters, true ) );
		if ( array_key_exists( 'data-spark-query', $this->parameters ) ) {
			$query = htmlspecialchars( $this->parameters['data-spark-query'] );

			// Before that, shall we allow internal parse, at least for the query?
			// We replace variables, templates etc.
			$query = $parser->replaceVariables( $query, $frame );
			// $query = $parser->recursiveTagParse( $query );

			// Replace special characters
			$query = str_replace( [ '&lt;', '&gt;' ], [ '<', '>' ], $query );

			unset( $this->parameters['data-spark-query'] );

			// Depending on the format, we possibly need to add modules
			if ( array_key_exists( 'data-spark-format', $this->parameters ) ) {
				$format = htmlspecialchars( $this->parameters['data-spark-format'] );
				// Remove everything before "spark.XXX"
				$format = substr( $format, strpos( $format, "spark." ) );
				// Remove .js at the end
				$format = str_replace( [ '.js' ], [ '' ], $format );
				$module = 'ext.' . $format;

				// $wgResourceModules might not exist
				if ( isset( $wgResourceModules ) && array_key_exists( $module, $wgResourceModules ) ) {
					$parser->getOutput()->addModules( [ $module ] );
				}
			}

			// phpcs:ignore Generic.Files.LineLength.TooLong
			$html = '<div class="spark" data-spark-query="' . $query . '" ' . Html::expandAttributes( $this->parameters ) . ' >' .
			( $this->contents === null ? '' : htmlspecialchars( $this->contents ) ) .
					'</div>';

			// In MW 1.17 there seems to be the problem that ? after an empty space is replaced by a
			// non-breaking space (&#160;) Therefore we remove all spaces before ? which should
			// still make the SPARQL query work
			$html = preg_replace( '/[ \t]+(\?)/', '$1', $html );

			return [ $parser->insertStripItem( $html ), 'noparse' => true, 'isHTML' => true ];
		} else {
			return Html::element( 'i', [], wfMessage( 'spark-missing-query' )->text() );
		}
	}

	/**
	 * Get the spark parameters from a list of key value pairs.
	 *
	 * @since 0.1
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected function getSparkParameters( array $args ) {
		$parameters = [];

		// For lower versions of MW, special chars were not allowed in tags, therefore, we simply
		// add them, then.
		foreach ( $args as $name => $value ) {
			if ( str_starts_with( $name, 'data-spark-' ) ) {
				$parameters[$name] = $value;
			} else {
				$parameters['data-spark-' . $name] = $value;
			}
		}

		return $parameters;
	}

}
