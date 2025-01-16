<?php

namespace WPO\IPS\FatturaPA\Handlers\Header;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiTrasmissioneHandler extends UblHandler {

	public function handle( $data, $options = array() ) {		
		$shopVatNumber      = ! empty( $this->document->order_document ) ? $this->document->order_document->get_shop_vat_number() : '';
		$shopCountryCode    = wc_format_country_state_string( get_option( 'woocommerce_default_country', '' ) )['country'];
		$codiceDestinatario = $this->document->order_document->get_setting( 'codice_destinatario', false, 'ubl' ) ?: '0000000';
		$pecDestinatario    = '0000000' === $codiceDestinatario ? $this->document->order_document->get_setting( 'pec_destinatario', false, 'ubl' ) : '';
		
		$datiTrasmissione = array(
			'name'  => 'DatiTrasmissione',
			'value' => array(
				array(
					'name'  => 'IdTrasmittente',
					'value' => array(
						array(
							'name'  => 'IdPaese',
							'value' => $shopCountryCode,
						),
						array(
							'name'  => 'IdCodice',
							'value' => $shopVatNumber,
						),
					),
				),
				array(
					'name'  => 'ProgressivoInvio',
					'value' => ! empty( $this->document->order_document->get_number() ) ? $this->document->order_document->get_number()->get_formatted() : '',
				),
				array(
					'name'  => 'FormatoTrasmissione',
					'value' => 'FPR12',
				),
				array(
					'name'  => 'CodiceDestinatario',
					'value' => $codiceDestinatario,
				),
			),
		);
		
		if ( ! empty( $pecDestinatario ) ) {
			$datiTrasmissione['value'][] = array(
				'name'  => 'PECDestinatario',
				'value' => $pecDestinatario,
			);
		}

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiTrasmissione', $datiTrasmissione, $data, $options, $this );

		return $data;
	}

}