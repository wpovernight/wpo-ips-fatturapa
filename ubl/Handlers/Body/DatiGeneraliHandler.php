<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiGeneraliHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		$divisa = $this->document->order->get_currency();
		$data   = $this->document->order_document->get_date()->date_i18n( 'Y-m-d' );
		$numero = ! empty( $this->document->order_document->get_number() ) ? $this->document->order_document->get_number()->get_formatted() : '';
		
		$datiGenerali = array(
			'name'  => 'DatiGenerali',
			'value' => array(
				array(
					'name'  => 'DatiGeneraliDocumento',
					'value' => array(
						array(
							'name'  => 'TipoDocumento',
							'value' => 'TD01',
						),
						array(
							'name'  => 'Divisa',
							'value' => $divisa,
						),
						array(
							'name'  => 'Data',
							'value' => $data,
						),
						array(
							'name'  => 'Numero',
							'value' => $numero,
						),
					),
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiGenerali', $datiGenerali, $data, $options, $this );

		return $data;
	}

}