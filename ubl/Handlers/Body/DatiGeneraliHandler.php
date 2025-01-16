<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiGeneraliHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		$tipoDocumento   = 'TD01';
		$divisaDocumento = $this->document->order->get_currency();
		$dataDocumento   = $this->document->order_document->get_date()->date_i18n( 'Y-m-d' );
		$numeroDocumento = ! empty( $this->document->order_document->get_number() ) ? $this->document->order_document->get_number()->get_formatted() : '';
		
		$datiGenerali = array(
			'name'  => 'DatiGenerali',
			'value' => array(
				array(
					'name'  => 'DatiGeneraliDocumento',
					'value' => array(
						array(
							'name'  => 'TipoDocumento',
							'value' => $tipoDocumento,
						),
						array(
							'name'  => 'Divisa',
							'value' => $divisaDocumento,
						),
						array(
							'name'  => 'Data',
							'value' => $dataDocumento,
						),
						array(
							'name'  => 'Numero',
							'value' => $numeroDocumento,
						),
					),
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiGenerali', $datiGenerali, $data, $options, $this );

		return $data;
	}

}