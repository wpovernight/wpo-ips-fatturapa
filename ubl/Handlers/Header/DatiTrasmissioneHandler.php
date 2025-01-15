<?php

namespace WPO\IPS\FatturaPA\Handlers\Header;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiTrasmissioneHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		// <DatiTrasmissione>
		// <IdTrasmittente>
		// 	<IdPaese>IT</IdPaese>
		// 	<IdCodice>01234567890</IdCodice>
		// </IdTrasmittente>
		// <ProgressivoInvio>00001</ProgressivoInvio>
		// <FormatoTrasmissione>FPA12</FormatoTrasmissione>
		// <CodiceDestinatario>AAAAAA</CodiceDestinatario>
		// </DatiTrasmissione>
		
		$datiTrasmissione = array(
			'name'  => 'DatiTrasmissione',
			'value' => array(
				array(
					'name'  => 'IdTrasmittente',
					'value' => 'test',
				),
				array(
					'name'  => 'ProgressivoInvio',
					'value' => 'test',
				),
				array(
					'name'  => 'FormatoTrasmissione',
					'value' => 'test',
				),
				array(
					'name'  => 'CodiceDestinatario',
					'value' => 'test',
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiTrasmissione', $datiTrasmissione, $data, $options, $this );

		return $data;
	}

}