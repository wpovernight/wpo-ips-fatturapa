<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiGeneraliHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		// <DatiGenerali>
		// 	<DatiGeneraliDocumento>
		// 		<TipoDocumento>TD01</TipoDocumento>
		// 		<Divisa>EUR</Divisa>
		// 		<Data>2017-01-18</Data>
		// 		<Numero>123</Numero>
		// 	</DatiGeneraliDocumento>
		// 	<DatiRicezione>
		// 		<RiferimentoNumeroLinea>1</RiferimentoNumeroLinea>
		// 		<IdDocumento>789</IdDocumento>
		// 		<NumItem>5</NumItem>
		// 		<CodiceCUP>123abc</CodiceCUP>
		// 		<CodiceCIG>456def</CodiceCIG>
		// 	</DatiRicezione>
		// 	<DatiTrasporto>
		// 		<DatiAnagraficiVettore>				
		// 			<IdFiscaleIVA>
		// 			<IdPaese>IT</IdPaese>
		// 			<IdCodice>24681012141</IdCodice>
		// 			</IdFiscaleIVA>
		// 			<Anagrafica>
		// 			<Denominazione>Trasporto spa</Denominazione>
		// 			</Anagrafica>
		// 		</DatiAnagraficiVettore>
		// 		<DataOraConsegna>2017-01-10T16:46:12.000+02:00</DataOraConsegna>
		// 	</DatiTrasporto>
		// </DatiGenerali>
		
		$datiGenerali = array(
			'name'  => 'DatiGenerali',
			'value' => array(
				array(
					'name'  => 'DatiGeneraliDocumento',
					'value' => 'test',
				),
				array(
					'name'  => 'DatiRicezione',
					'value' => 'test',
				),
				array(
					'name'  => 'DatiTrasporto',
					'value' => 'test',
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiGenerali', $datiGenerali, $data, $options, $this );

		return $data;
	}

}