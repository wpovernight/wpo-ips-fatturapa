<?php

namespace WPO\IPS\FatturaPA\Handlers\Header;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class CedentePrestatoreHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		// <CedentePrestatore>
		// <DatiAnagrafici>
		// 	<IdFiscaleIVA>
		// 	<IdPaese>IT</IdPaese>
		// 	<IdCodice>01234567890</IdCodice>
		// 	</IdFiscaleIVA>
		// 	<Anagrafica>
		// 	<Denominazione>ALPHA SRL</Denominazione>
		// 	</Anagrafica>
		// 	<RegimeFiscale>RF19</RegimeFiscale>
		// </DatiAnagrafici>
		// <Sede>
		// 	<Indirizzo>VIALE ROMA 543</Indirizzo>
		// 	<CAP>07100</CAP>
		// 	<Comune>SASSARI</Comune>
		// 	<Provincia>SS</Provincia>
		// 	<Nazione>IT</Nazione>
		// </Sede>
		// </CedentePrestatore>
		
		$cedentePrestatore = array(
			'name'  => 'CedentePrestatore',
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

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_CedentePrestatore', $cedentePrestatore, $data, $options, $this );

		return $data;
	}

}