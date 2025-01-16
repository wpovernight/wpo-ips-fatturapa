<?php

namespace WPO\IPS\FatturaPA\Handlers\Header;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class CessionarioCommittenteHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		$codiceFiscale   = wpo_wcpdf_get_order_customer_vat_number( $this->document->order );
		$denominazione   = $this->document->order->get_formatted_billing_full_name();
		$billing_company = $this->document->order->get_billing_company();
		$indirizzo       = $this->document->order->get_billing_address_1() . ' ' . $this->document->order->get_billing_address_2();
		$cap             = $this->document->order->get_billing_postcode();
		$comune          = $this->document->order->get_billing_city();
		$provincia       = $this->document->order->get_billing_state();
		$nazione         = $this->document->order->get_billing_country();

		if ( ! empty( $billing_company ) ) {
			$denominazione = $billing_company;
		}
		
		$cessionarioCommittente = array(
			'name'  => 'CessionarioCommittente',
			'value' => array(
				array(
					'name'  => 'DatiAnagrafici',
					'value' => array(
						array(
							'name' => 'CodiceFiscale',
							'value' => $codiceFiscale,
						),
						array(
							'name' => 'Anagrafica',
							'value' => array(
								array(
									'name' => 'Denominazione',
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
							'name' => 'Indirizzo',
							'value' => wpo_ips_ubl_sanitize_string( $indirizzo ),
						),
						array(
							'name' => 'CAP',
							'value' => $cap,
						),
						array(
							'name' => 'Comune',
							'value' => wpo_ips_ubl_sanitize_string( $comune ),
						),
						array(
							'name' => 'Provincia',
							'value' => $provincia,
						),
						array(
							'name' => 'Nazione',
							'value' => $nazione,
						),
					),
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_CedentePrestatore', $cessionarioCommittente, $data, $options, $this );

		return $data;

		return $data;
	}

}