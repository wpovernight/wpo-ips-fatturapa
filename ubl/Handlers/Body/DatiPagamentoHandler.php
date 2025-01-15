<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiPagamentoHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		// <DatiPagamento>
		// 	<CondizioniPagamento>TP01</CondizioniPagamento>
		// 	<DettaglioPagamento>
		// 		<ModalitaPagamento>MP01</ModalitaPagamento>
		// 		<DataScadenzaPagamento>2017-03-30</DataScadenzaPagamento>
		// 		<ImportoPagamento>30.50</ImportoPagamento>
		// 	</DettaglioPagamento>
		// </DatiPagamento>
	
		$datiPagamento = array(
			'name'  => 'DatiPagamento',
			'value' => array(
				array(
					'name'  => 'CondizioniPagamento',
					'value' => 'test',
				),
				array(
					'name'  => 'DettaglioPagamento',
					'value' => 'test',
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiPagamento', $datiPagamento, $data, $options, $this );

		return $data;
	}

}