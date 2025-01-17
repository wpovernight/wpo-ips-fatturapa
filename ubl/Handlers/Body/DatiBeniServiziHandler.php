<?php

namespace WPO\IPS\FatturaPA\Handlers\Body;

use WPO\IPS\UBL\Handlers\UblHandler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class DatiBeniServiziHandler extends UblHandler {

	public function handle( $data, $options = array() ) {
		$items          = $this->document->order->get_items( array( 'line_item', 'fee', 'shipping' ) );
		$orderTaxData   = $this->document->order_tax_data;
		$dettaglioLinee = array();
		$itemTaxData    = array();
		
		// Build the tax totals array
		foreach ( $items as $item_id => $item ) {
			$taxDataContainer  = ( $item['type'] == 'line_item' ) ? 'line_tax_data' : 'taxes';
			$taxDataKey        = ( $item['type'] == 'line_item' ) ? 'subtotal'      : 'total';
			$lineTaxData       = $item[ $taxDataContainer ];
			$itemTaxPercentage = 0;

			foreach ( $lineTaxData[ $taxDataKey ] as $tax_id => $tax ) {
				if ( empty( $tax ) ) {
					$tax = 0;
				}

				if ( ! is_numeric( $tax ) ) {
					continue;
				}

				if ( ! empty( $orderTaxData[ $tax_id ] ) ) {
					$itemTaxData[ $tax_id ] = $orderTaxData[ $tax_id ];
					$itemTaxPercentage      = $itemTaxData[ $tax_id ]['percentage'];
					break;
				}
			}
			
			$dettaglioLinee[] = array(
				'name'  => 'DettaglioLinee',
				'value' => array(
					array(
						'name'  => 'NumeroLinea',
						'value' => $item_id,
					),
					array(
						'name'  => 'Descrizione',
						'value' => wpo_ips_ubl_sanitize_string( $item->get_name() ),
					),
					array(
						'name'  => 'Quantita',
						'value' => $item->get_quantity(),
					),
					array(
						'name'  => 'PrezzoUnitario',
						'value' => round( $this->get_item_unit_price( $item ), 2 ),
					),
					array(
						'name'  => 'PrezzoTotale',
						'value' => round( $item->get_total(), 2 ),
					),
					array(
						'name'  => 'AliquotaIVA',
						'value' => round( $itemTaxPercentage, 2 ),
					),
				),
			);
		}
		
		$datiRiepilogo = array();
		
		foreach ( $itemTaxData as $taxData ) {
			$datiRiepilogo[] = array(
				'name'  => 'DatiRiepilogo',
				'value' => array(
					array(
						'name'  => 'AliquotaIVA',
						'value' => round( $taxData['percentage'], 2 ),
					),
					array(
						'name'  => 'ImponibileImporto',
						'value' => round( $taxData['total_ex'], 2 ),
					),
					array(
						'name'  => 'Imposta',
						'value' => round( $taxData['total_tax'], 2 ),
					),
					array(
						'name'  => 'EsigibilitaIVA',
						'value' => 'I',
					),
				),
			);
		}
		
		$datiBeniServizi = array(
			'name'  => 'DatiBeniServizi',
			'value' => array_merge( $dettaglioLinee, $datiRiepilogo ),
		);

		$data[] = apply_filters( 'wpo_ips_fatturapa_handle_DatiBeniServizi', $datiBeniServizi, $data, $options, $this );

		return $data;
	}
	
	/**
	 * Get the unit price of an item
	 *
	 * @param WC_Order_Item $item
	 * @return int|float
	 */
	private function get_item_unit_price( $item ) {
		if ( is_a( $item, 'WC_Order_Item_Product' ) ) {
			return $item->get_subtotal() / $item->get_quantity();
		} elseif ( is_a( $item, 'WC_Order_Item_Shipping' ) || is_a( $item, 'WC_Order_Item_Fee' ) ) {
			return $item->get_total();
		} else {
			return 0;
		}
	}

}