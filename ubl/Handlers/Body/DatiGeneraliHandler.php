<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiGeneraliHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		/**
		 * TD01 invoice
		 * TD02 advance/down payment on invoice
		 * TD03 advance/down payment on fee
		 * TD04 credit note
		 * TD05 debit note
		 * TD06 fee
		 * TD16 reverse charge internal invoice integration
		 * TD17 integration/self invoicing for purchase services from abroad
		 * TD18 integration for purchase of intra UE goods
		 * TD19 integration/self invoicing for purchase of goods ex art.17 c.2 DPR 633/72	
		 * TD20 self invoicing for regularisation and integration of invoices (ex art.6 c.8 and 9-bis d.lgs 471/97 or art.46 c.5 D.L. 331/93)
		 * TD21 self invoicing for splaphoning
		 * TD22 extractions of goods from VAT Warehouse
		 * TD23 extractions of goods from VAT Warehouse with payment of VAT
		 * TD24 deferred invoice ex art.21, c.4, lett. a)
		 * TD25 deferred invoice ex art.21, c.4, third period lett. b)
		 * TD26 sale of depreciable assets and for internal transfers (ex art.36 DPR 633/72)
		 * TD27 self invoicing for self consumption or for free transfer without recourse
		 */
		$tipoDocumento   = apply_filters( 'wpo_ips_fatturapa_TipoDocumento', 'TD01', $this );
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