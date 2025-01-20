<?php

namespace WPO\IPS\FatturaPA\Handlers\Header;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class CedentePrestatoreHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		$wooCountry    = get_option( 'woocommerce_default_country', '' );
		$idPaese       = wc_format_country_state_string( $wooCountry )['country'];
		$idCodice      = ! empty( $this->document->order_document ) ? $this->document->order_document->get_shop_vat_number() : '';
		$denominazione = ! empty( $this->document->order_document ) ? $this->document->order_document->get_shop_name()       : '';
		$indirizzo     = ! empty( $this->document->order_document ) ? $this->document->order_document->get_shop_address()    : get_option( 'woocommerce_store_address' );
		$cap           = get_option( 'woocommerce_store_postcode', '' );
		$comune        = get_option( 'woocommerce_store_city', '' );
		
		/**
		 * RF01 Ordinary
		 * RF02 Minimum taxpayers (Art. 1, section 96-117, Italian Law 244/07)
		 * RF04 Agriculture and connected activities and fishing (Arts. 34 and 34-bis, Italian Presidential Decree 633/72)
		 * RF05 Sale of salts and tobaccos (Art. 74, section 1, Italian Presidential Decree 633/72)
		 * RF06 Match sales (Art. 74, section 1, Italian Presidential Decree 633/72)
		 * RF07 Publishing (Art. 74, section 1, Italian Presidential Decree 633/72)
		 * RF08 Management of public telephone services (Art. 74, section 1, Italian Presidential Decree 633/72)
		 * RF09 Resale of public transport and parking documents (Art. 74, section 1, Italian Presidential Decree 633/72)
		 * RF10 Entertainment, gaming and other activities referred to by the tariff attached to Italian Presidential Decree 640/72 (Art. 74, section 6, Italian Presidential Decree 633/72)
		 * RF11 Travel and tourism agencies (Art. 74-ter, Italian Presidential Decree 633/72)
		 * RF12 Farmhouse accommodation/restaurants (Art. 5, section 2, Italian law 413/91)
		 * RF13 Door-to-door sales (Art. 25-bis, section 6, Italian Presidential Decree 600/73)
		 * RF14 Resale of used goods, artworks, antiques or collector's items (Art. 36, Italian Decree Law 41/95)
		 * RF15 Artwork, antiques or collector's items auction agencies (Art. 40-bis, Italian Decree Law 41/95)
		 * RF16 VAT paid in cash by P.A. (Art. 6, section 5, Italian Presidential Decree 633/72)
		 * RF17 VAT paid in cash by subjects with business turnover below Euro 200,000 (Art. 7, Italian Decree Law 185/2008)
		 * RF18 Other
		 * RF19 Flat rate (Art. 1, section 54-89, Italian Law 190/2014)
		 */
		$regimeFiscale = apply_filters( 'wpo_ips_fatturapa_RegimeFiscale', 'RF01', $this );
		
		list( $country, $provincia ) = explode( ':', $wooCountry );
		
		$cedentePrestatore = array(
			'name'  => 'CedentePrestatore',
			'value' => array(
				array(
					'name'  => 'DatiAnagrafici',
					'value' => array(
						array(
							'name' => 'IdFiscaleIVA',
							'value' => array(
								array(
									'name'  => 'IdPaese',
									'value' => $idPaese,
								),
								array(
									'name'  => 'IdCodice',
									'value' => $idCodice,
								),
							),
						),
						array(
							'name'  => 'Anagrafica',
							'value' => array(
								array(
									'name'  => 'Denominazione',
									'value' => wpo_ips_ubl_sanitize_string( $denominazione ),
								),
							),
						),
						array(
							'name'  => 'RegimeFiscale',
							'value' => $regimeFiscale,
						),
					),
				),
				array(
					'name'  => 'Sede',
					'value' => array(
						array(
							'name'  => 'Indirizzo',
							'value' => wpo_ips_ubl_sanitize_string( $indirizzo ),
						),
						array(
							'name'  => 'CAP',
							'value' => $cap,
						),
						array(
							'name'  => 'Comune',
							'value' => wpo_ips_ubl_sanitize_string( $comune ),
						),
						array(
							'name'  => 'Provincia',
							'value' => $provincia,
						),
						array(
							'name'  => 'Nazione',
							'value' => $idPaese,
						),
					),
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_CedentePrestatore', $cedentePrestatore, $data, $options, $this );

		return $data;
	}

}