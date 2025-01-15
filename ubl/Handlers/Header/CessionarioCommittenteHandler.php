<?php

namespace WPO\IPS\FatturaPA\Handlers\Header;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class CessionarioCommittenteHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		// <CessionarioCommittente>
		// <DatiAnagrafici>
		// 	<CodiceFiscale>09876543210</CodiceFiscale>
		// 	<Anagrafica>
		// 	<Denominazione>AMMINISTRAZIONE BETA</Denominazione>
		// 	</Anagrafica>
		// </DatiAnagrafici>
		// <Sede>
		// 	<Indirizzo>VIA TORINO 38-B</Indirizzo>
		// 	<CAP>00145</CAP>
		// 	<Comune>ROMA</Comune>
		// 	<Provincia>RM</Provincia>
		// 	<Nazione>IT</Nazione>
		// </Sede>
		// </CessionarioCommittente>
		
		$cessionarioCommittente = array(
			'name'  => 'CessionarioCommittente',
			'value' => array(
				array(
					'name'  => 'DatiAnagrafici',
					'value' => 'test',
				),
				array(
					'name'  => 'Sede',
					'value' => 'test',
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_CedentePrestatore', $cessionarioCommittente, $data, $options, $this );

		return $data;

		return $data;
	}

}