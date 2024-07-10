<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once(dirname(wc_gateway_payphone()->file) . '/includes/exceptions/wc-payphone-exception.php');

class WC_Gateway_PayPhone_Response {

    public $order_id;
    public $transaction_id;
    public $token;
    public $url;
    public $contador;

    public function __construct($order_id, $transaction_id, $token, $url) {
        $this->order_id = $order_id;
        $this->transaction_id = $transaction_id;
        $this->token = $token;
        $this->url = $url;
        $this->contador = 0;
    }

    public function confirm() {
        global $woocommerce;
        $order = new WC_Order($this->order_id);           
        
        $result = $this->confirm_call($this->contador);
        
        if ($result == null) {
            $order->update_status('cancelled', __('No valid response was obtained', 'payphone'));
            wc_add_notice(__('Payment error:', 'payphone') . __("Url not found, payment with PayPhone will be automatically reversed, contact the administrator", 'payphone'), 'error');
        } else {
            if ($order->has_status(array('processing', 'completed'))) {
                //wc_gateway_ppec_log('Aborting, Order #' . $order_id . ' is already complete.');
                return $result;
            }

            if ($result->statusCode == 2) {
                $order->update_status('cancelled', __($result->message, 'payphone'));
                wc_add_notice(__('Payment error:', 'payphone') . $result->message, 'error');                
            }

            if ($result->statusCode == 3) {
                $order->payment_complete();
               // wc_add_notice(__('Estado Pago:', 'payphone') . $result->transactionStatus, 'success');
                //wc_add_notice(__('Resultado:', 'payphone') . (json_encode($result)), 'success');
                //wc_add_notice(__('Orden:', 'payphone') . "<pre>".json_encode($order->get_items(),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT )."</pre>", 'success');
                $productsTable="";
                //Recupera solo la lista de productos del carrito de compras
                $items = $order->get_items();   
                //Recorremos los productos lo guardamos en el arreglo lineItems en el formato requerido
                foreach($items as $item){   
                    //Recupera detalles del producto    
                    $product = wc_get_product( $item['product_id'] );
                    //Recupera detalles del producto  en el carrito
                    $item_data = $item->get_data();
                    $productos="<tr><td>".substr(trim(strip_tags( $item_data['name'] )), 0, 50)."</td>"
                    ."<td>X".$item_data['quantity']."</td>"
                    ."<td>".round(round(($item_data['total']+$item_data['total_tax']),2),2)."</td></tr>"
                    ;
                    $productsTable .= $productos;  
                }    
                $styleCabezera="style=''";
                $styleCuerpo="vertical-align: middle;text-align: center;padding: 1px;margin: 5px;";
                $tablaPedido="<table class='table'><thead class='thead-dark'>"
                ."<tr><th ".$styleCabezera.">PRODUCTO</th><th ".$styleCabezera.">CANTIDAD</th><th ".$styleCabezera.">PRECIO</th></tr></thead>".$productsTable."</table>";
                $resultado= "<div id='detalle_pago'>"
                    ."<h3>GRACIAS POR TU COMPRA </h3>"
                    ."<label style='font-size: 20px;'>PAGO: <strong style='font-size: 25px;color: green;text-shadow: 2px 2px #caf389;'>". $result->transactionStatus."</strong></label><br><br>"
                    ."<table class='table'><thead class='thead-dark'>"
                    ."<tr><th ".$styleCabezera.">NÚMERO DEL PEDIDO</th><th ".$styleCabezera.">FECHA</th><th ".$styleCabezera.">TOTAL</th></tr></thead>"
                    ."<tr style='border-bottom: 2px solid #000000;'><td style='".$styleCuerpo."'><strong>".$result->clientTransactionId."</strong></td>"         
                    ."<td style='".$styleCuerpo." border: 1px solid #000000'><strong>".date("d/m/Y", strtotime($result->date))."</strong></td>"
                    ."<td style='".$styleCuerpo."'>".$result->currency." <strong style='font-size: 30px;'>".round(($result->amount/100),2)."</strong></td></tr></table><br>"                
                    ."<h4>DETALLE DEL PAGO</h4>"
                    ."<table class='detalle_pagos'>"
                    ."<tr><td>MÉTODO DE PAGO: </td><td><strong>".$result->cardBrand."</strong></td><tr>"
                    ."<tr><td>NÚMERO DEL TRANSACCION: </td><td><strong>".$result->transactionId."</strong></td><tr>"                 
                    ."<tr><td>NOMBRES: </td><td><strong>".$result->optionalParameter4."</strong></td><tr>"
                    ."<tr><td>CORREO: </td><td><strong>".$result->optionalParameter2."</strong></td><tr>"
                    ."<tr><td>REFERENCIA: </td><td><strong>".$result->reference  ."</strong></td><tr></table><br>"
                    ."<h4>DETALLE DEL PEDIDO</h4>"
                    .$tablaPedido
                ."</div>"                
                //."<br>".$result->storeName background: rgb(245, 126, 45);
                ;                    
                wc_add_notice(__('', 'payphone') . ($resultado), 'success');                
                
//                 wc_add_notice(__('Payment result:', 'payphone') . $result->transactionStatus."<br>".$result->storeName, 'success');
                $woocommerce->cart->empty_cart();                                
            }

            return $result;
        }
        return $result;
    }

    private function confirm_call($cont) {
        $payphone_args = $this->get_confirm_args();
        $json = json_encode($payphone_args);
        $headers = array(
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($json)
        );
        
        $args = array(
                  'body' => $json,
                  'timeout' => '5',
                  'redirection' => '5',
                  'httpversion' => '1.0',
                  'blocking' => true,
                  'headers' => $headers
        );
        $response = wp_remote_post( $this->url . "/api/button/V2/Confirm", $args );
        $info = wp_remote_retrieve_response_code( $response );
        if (is_array($response)){
            reset($response);
            $tipo = get_class(current($response));
        }else{
            $tipo = get_class($response);
        }
        if (strcmp($tipo, 'WP_Error') !== 0)
        {
            $obj_response = json_decode($response['body']);
            if ($info == 200 && $obj_response != null) {
                return json_decode($response['body']);
            }

            $cont = $cont + 1;
            if ($cont <= 1) {
                return $this->confirm_call($cont);
            }
            
            if ($obj_response == null)
            {
                throw new WC_PayPhone_Exception("Url not found", $info['http_code'], $obj_response);
            }

            if ($obj_response->message) {
                throw new WC_PayPhone_Exception($obj_response->message, $info['http_code'], $obj_response);
                throw new Exception(json_decode($obj_response));
            }
        }
        else
        {
            throw new Exception(__('The request could not be completed', 'payphone'));
        }
        //throw new Exception(__('The request could not be completed', 'payphone'));
    }

    private function get_confirm_args() {
        $args = new stdClass();

        $args->id = $this->transaction_id;
        $args->clientTxId = $this->order_id;
        return $args;
    }
}