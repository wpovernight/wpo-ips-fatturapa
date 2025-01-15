<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiBeniServiziHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		// <DatiBeniServizi>
		// 	<DettaglioLinee>
		// 		<NumeroLinea>1</NumeroLinea>
		// 		<Descrizione>LA DESCRIZIONE DELLA FORNITURA PUO' SUPERARE I CENTO CARATTERI CHE RAPPRESENTAVANO IL PRECEDENTE LIMITE DIMENSIONALE. TALE LIMITE NELLA NUOVA VERSIONE E' STATO PORTATO A MILLE CARATTERI</Descrizione>
		// 		<Quantita>5.00</Quantita>
		// 		<PrezzoUnitario>1.00</PrezzoUnitario>
		// 		<PrezzoTotale>5.00</PrezzoTotale>
		// 		<AliquotaIVA>22.00</AliquotaIVA>
		// 	</DettaglioLinee>
		// 	<DettaglioLinee>
		// 		<NumeroLinea>2</NumeroLinea>
		// 		<Descrizione>FORNITURE VARIE PER UFFICIO</Descrizione>
		// 		<Quantita>10.00</Quantita>
		// 		<PrezzoUnitario>2.00</PrezzoUnitario>
		// 		<PrezzoTotale>20.00</PrezzoTotale>
		// 		<AliquotaIVA>22.00</AliquotaIVA>
		// 	</DettaglioLinee>
		// 	<DatiRiepilogo>
		// 		<AliquotaIVA>22.00</AliquotaIVA>
		// 		<ImponibileImporto>25.00</ImponibileImporto>
		// 		<Imposta>5.50</Imposta>
		// 		<EsigibilitaIVA>D</EsigibilitaIVA>
		// 	</DatiRiepilogo>
		// </DatiBeniServizi>
		
		$datiBeniServizi = array(
			'name'  => 'DatiBeniServizi',
			'value' => array(
				array(
					'name'  => 'DettaglioLinee',
					'value' => 'test',
				),
				array(
					'name'  => 'DettaglioLinee',
					'value' => 'test',
				),
				array(
					'name'  => 'DatiRiepilogo',
					'value' => 'test',
				),
			),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiBeniServizi', $datiBeniServizi, $data, $options, $this );

		return $data;
	}

}