<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiPagamentoHandler extends UblHandler {

	public function handle( $data, $options = array() ) {	
		$datiPagamento = array(
			'name'  => 'DatiPagamento',
			'value' => array(
				array(
					'name'  => 'CondizioniPagamento',
					'value' => 'TP01',
				),
				array(
					'name'  => 'DettaglioPagamento',
					'value' => array(
						array(
							'name'  => 'ModalitaPagamento',
							'value' => $this->get_modalita_pagamento_code(),
						),
						array(
							'name'  => 'DataScadenzaPagamento',
							'value' => $this->get_date_paid(),
						),
						array(
							'name'  => 'ImportoPagamento',
							'value' => $this->document->order->get_total(),
						),
					),
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiPagamento', $datiPagamento, $data, $options, $this );

		return $data;
	}
	
	/**
	 * Get the ModalitaPagamento code based on the payment method
	 *
	 * @return string
	 */
	private function get_modalita_pagamento_code(): string {
		$paymentMethod = $this->document->order->get_payment_method();
		
		// Map WooCommerce payment methods to ModalitaPagamento codes
		$mapping = apply_filters( 'wpo_ips_fatturapa_modalita_pagamento_code_mapping', array(
			'cod'    => 'MP01',
			'bacs'   => 'MP05',
			'cheque' => 'MP07',
			'paypal' => 'MP08',
			'stripe' => 'MP08',
		), $this );
	
		// Default to MP19 (Other) if no match is found
		return isset( $mapping[ $paymentMethod ] ) ? $mapping[ $paymentMethod ] : 'MP19';
	}
	
	/**
	 * Get the date paid
	 *
	 * @return string
	 */
	private function get_date_paid(): string {
		$order_date_paid = $this->document->order->get_date_paid();
		
		if ( ! $order_date_paid ) {
			$order_date_paid = $this->document->order_document->get_date();
		}
		
		return $order_date_paid->date_i18n( 'Y-m-d' );
	}

}