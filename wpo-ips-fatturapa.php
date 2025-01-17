<?php
/**
 * Plugin Name:      PDF Invoices & Packing Slips for WooCommerce - FatturaPA
 * Requires Plugins: woocommerce-pdf-invoices-packing-slips
 * Plugin URI:       https://github.com/wpovernight/wpo-ips-fatturapa
 * Description:      FatturaPA add-on for PDF Invoices & Packing Slips for WooCommerce plugin.
 * Version:          1.0.0-beta-2
 * Update URI:       https://github.com/wpovernight/wpo-ips-fatturapa
 * Author:           WP Overnight
 * Author URI:       https://wpovernight.com
 * License:          GPLv3
 * License URI:      https://opensource.org/licenses/gpl-license.php
 * Text Domain:      wpo-ips-fatturapa
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'WPO_IPS_FatturaPA' ) ) {

	class WPO_IPS_FatturaPA {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public $version = '1.0.0-beta-2';
		
		/**
		 * Base plugin version
		 *
		 * @var string
		 */
		public $base_plugin_version = '3.9.5-beta-13';
		
		/**
		 * UBL format
		 *
		 * @var string
		 */
		public $ubl_format = 'fatturapa';
		
		/**
		 * Format name
		 *
		 * @var string
		 */
		public $format_name = 'FatturaPA';
		
		/**
		 * Root element
		 *
		 * @var string
		 */
		public $root_element = '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}FatturaElettronica';
		
		/**
		 * Plugin path
		 * 
		 * @var string
		 */
		public $plugin_path;
		
		/**
		 * Plugin instance
		 *
		 * @var WPO_IPS_FatturaPA
		 */
		private static $_instance;

		/**
		 * Plugin instance
		 * 
		 * @return WPO_IPS_FatturaPA
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->plugin_path   = plugin_dir_path( __FILE__ );
			$plugin_file         = basename( $this->plugin_path ) . '/wpo-ips-fatturapa.php';
			$github_updater_file = $this->plugin_path . 'github-updater/GitHubUpdater.php';
			$autoloader_file     = $this->plugin_path . 'vendor/autoload.php';
			
			if ( ! class_exists( '\\WPO\\GitHubUpdater\\GitHubUpdater' ) && file_exists( $github_updater_file ) ) {
				require_once $github_updater_file;
			}
			
			if ( class_exists( '\\WPO\\GitHubUpdater\\GitHubUpdater' ) ) {
				$gitHubUpdater = new \WPO\GitHubUpdater\GitHubUpdater( $plugin_file );
				$gitHubUpdater->setChangelog( 'CHANGELOG.md' );
				$gitHubUpdater->add();
			}

			if ( class_exists( 'WPO_WCPDF' ) && version_compare( WPO_WCPDF()->version, $this->base_plugin_version, '<' ) ) {
				add_action( 'admin_notices', array( $this, 'base_plugin_dependency_notice' ) );
				return;
			}
			
			if ( file_exists( $autoloader_file ) ) {
				require_once $autoloader_file;
			}
			
			add_action( 'init', array( $this, 'load_translations' ) );
			add_action( 'before_woocommerce_init', array( $this, 'custom_order_tables_compatibility' ) );
			
			add_filter( 'wpo_ips_ubl_is_country_format_extension_active', '__return_true' );
			add_filter( 'wpo_wcpdf_document_ubl_settings_formats', array( $this, 'add_format_to_ubl_settings' ), 10, 2 );
			add_filter( 'wpo_wc_ubl_document_root_element', array( $this, 'add_root_element' ), 10, 2 );
			add_filter( 'wpo_wc_ubl_document_additional_root_elements', array( $this, 'add_additional_root_elements' ), 10, 2 );
			add_filter( 'wpo_wc_ubl_document_format', array( $this, 'set_document_format' ), 10, 2 );
			add_filter( 'wpo_wc_ubl_document_namespaces', array( $this, 'set_document_namespaces' ), 10, 2 );
			add_filter( 'wpo_wcpdf_settings_fields_documents_invoice_ubl', array( $this, 'add_additional_settings_fields' ), 10, 5 );
			add_filter( 'wpo_wcpdf_document_settings_categories', array( $this, 'map_additional_settings_fields_to_categories' ), 10, 3 );
		}
		
		/**
		 * Base plugin dependency notice
		 * 
		 * @return void
		 */
		public function base_plugin_dependency_notice(): void {
			$error = sprintf( 
				/* translators: plugin version */
				__( 'PDF Invoices & Packing Slips for WooCommerce - FatturaPA requires PDF Invoices & Packing Slips for WooCommerce version %s or higher.', 'wpo-ips-fatturapa' ), 
				$this->base_plugin_version
			);

			$message = sprintf( 
				'<div class="notice notice-error"><p>%s</p></div>', 
				$error, 
			);

			echo $message;
		}
		
		/**
		 * Load translations
		 * 
		 * @return void
		 */
		public function load_translations(): void {
			load_plugin_textdomain( 'wpo-ips-fatturapa', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
		
		/**
		 * Add HPOS compatibility
		 * 
		 * @return void
		 */
		public function custom_order_tables_compatibility(): void {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}
		
		/**
		 * Add format to UBL settings
		 *
		 * @param array $formats
		 * @param \WPO\IPS\Documents\OrderDocument $document
		 * @return array
		 */
		public function add_format_to_ubl_settings( array $formats, \WPO\IPS\Documents\OrderDocument $document ): array {
			if ( $document && 'invoice' === $document->get_type() ) {
				$formats[ $this->ubl_format ] = $this->format_name;
			}
			
			return $formats;
		}
		
		/**
		 * Check if UBL document is FatturaPA
		 *
		 * @param \WPO\IPS\UBL\Documents\UblDocument $ubl_document
		 * @return bool
		 */
		private function is_fatturapa_ubl_document( \WPO\IPS\UBL\Documents\UblDocument $ubl_document ): bool {
			return (
				is_callable( array( $ubl_document->order_document, 'get_ubl_format' ) ) &&
				$this->ubl_format === $ubl_document->order_document->get_ubl_format()
			);
		}
		
		/**
		 * Add root element
		 *
		 * @param string $root_element
		 * @param \WPO\IPS\UBL\Documents\UblDocument $ubl_document
		 * @return string
		 */
		public function add_root_element( string $root_element, \WPO\IPS\UBL\Documents\UblDocument $ubl_document ): string {
			if ( $this->is_fatturapa_ubl_document( $ubl_document ) ) {
				$root_element = $this->root_element;
			}
			
			return $root_element;
		}
		
		/**
		 * Add additional root elements
		 *
		 * @param array $additional_root_elements
		 * @param \WPO\IPS\UBL\Documents\UblDocument $ubl_document
		 * @return array
		 */
		public function add_additional_root_elements( array $additional_root_elements, \WPO\IPS\UBL\Documents\UblDocument $ubl_document ): array {
			if ( $this->is_fatturapa_ubl_document( $ubl_document ) ) {
				$additional_root_elements['versione'] = 'FPA12';
			}
			
			return $additional_root_elements;
		}
		
		/**
		 * Set document format
		 *
		 * @param array $format
		 * @param \WPO\IPS\UBL\Documents\UblDocument $ubl_document
		 * @return array
		 */
		public function set_document_format( array $format, \WPO\IPS\UBL\Documents\UblDocument $ubl_document ): array {
			if ( $this->is_fatturapa_ubl_document( $ubl_document ) ) {
				$format = apply_filters( 'wpo_ips_fatturapa_document_format', array(
					'fatturaelettronicaheader' => array(
						'enabled' => true,
						'handler' => array(
							\WPO\IPS\FatturaPA\Handlers\Header\DatiTrasmissioneHandler::class,
							\WPO\IPS\FatturaPA\Handlers\Header\CedentePrestatoreHandler::class,
							\WPO\IPS\FatturaPA\Handlers\Header\CessionarioCommittenteHandler::class,
						),
						'options' => array(
							'root' => 'FatturaElettronicaHeader',
						),
					),
					'fatturaelettronicabody' => array(
						'enabled' => true,
						'handler' => array(
							\WPO\IPS\FatturaPA\Handlers\Body\DatiGeneraliHandler::class,
							\WPO\IPS\FatturaPA\Handlers\Body\DatiBeniServiziHandler::class,
							\WPO\IPS\FatturaPA\Handlers\Body\DatiPagamentoHandler::class,
						),
						'options' => array(
							'root' => 'FatturaElettronicaBody',
						),
					),
				), $ubl_document );
			}
			
			return $format;
		}
		
		/**
		 * Set document namespaces
		 *
		 * @param array $namespaces
		 * @param \WPO\IPS\UBL\Documents\UblDocument $ubl_document
		 * @return array
		 */
		public function set_document_namespaces( array $namespaces, \WPO\IPS\UBL\Documents\UblDocument $ubl_document ): array {
			if ( $this->is_fatturapa_ubl_document( $ubl_document ) ) {
				$namespaces = apply_filters( 'wpo_ips_fatturapa_document_namespaces', array(
					'ds'             => 'http://www.w3.org/2000/09/xmldsig#',
					'p'              => 'http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2',
					'xsi'            => 'http://www.w3.org/2001/XMLSchema-instance',
					'schemaLocation' => 'http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2 http://www.fatturapa.gov.it/export/fatturazione/sdi/fatturapa/v1.2/Schema_del_file_xml_FatturaPA_versione_1.2.xsd',
				), $ubl_document );
			}
			
			return $namespaces;
		}
		
		/**
		 * Add additional settings fields
		 *
		 * @param array $settings_fields
		 * @param string $page
		 * @param string $option_group
		 * @param string $option_name
		 * @param \WPO\IPS\Documents\OrderDocument $document
		 * @return array
		 */
		public function add_additional_settings_fields( array $settings_fields, string $page, string $option_group, string $option_name, \WPO\IPS\Documents\OrderDocument $document ): array {
			$settings_fields[] = array(
				'type'     => 'setting',
				'id'       => 'codice_destinatario',
				'title'    => __( 'Recipient Code:', 'wpo-ips-fatturapa' ),
				'callback' => 'text_input',
				'section'  => $document->type . '_ubl',
				'args'     => array(
					'option_name' => $option_name,
					'id'          => 'codice_destinatario',
					'description' => sprintf(
						/* translators: 0000000 code */
						__( 'The unique code identifying the recipient in the SDI system. Enter %s if the recipient uses a PEC email.', 'wpo-ips-fatturapa' ),
						'<code>0000000</code>'
					),
				)
			);
			
			$settings_fields[] = array(
				'type'     => 'setting',
				'id'       => 'pec_destinatario',
				'title'    => __( 'Certified Email Address:', 'wpo-ips-fatturapa' ),
				'callback' => 'email_input',
				'section'  => $document->type . '_ubl',
				'args'     => array(
					'option_name' => $option_name,
					'id'          => 'pec_destinatario',
					'description' => sprintf(
						/* translators: 0000000 code */
						__( 'The PEC email address of the recipient. This field is required if the Recipient Code is set to %s.', 'wpo-ips-fatturapa' ),
						'<code>0000000</code>'
					),
				)
			);
			
			return $settings_fields;
		}
		
		/**
		 * Map additional settings fields to categories
		 *
		 * @param array $settings_categories
		 * @param string $output_format
		 * @param \WPO\IPS\Documents\OrderDocument $document
		 * @return array
		 */
		public function map_additional_settings_fields_to_categories( array $settings_categories, string $output_format, \WPO\IPS\Documents\OrderDocument $document ): array {
			if ( 'ubl' === $output_format ) {
				$settings_categories['general']['members'][] = 'codice_destinatario';
				$settings_categories['general']['members'][] = 'pec_destinatario';
			}
			
			return $settings_categories;
		}

	}
	
}

/**
 * Plugin instance
 * 
 * @return WPO_IPS_FatturaPA
 */
function WPO_IPS_FatturaPA() {
	return WPO_IPS_FatturaPA::instance();
}
add_action( 'plugins_loaded', 'WPO_IPS_FatturaPA', 99 );