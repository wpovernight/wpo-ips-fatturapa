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