<?php
require_once(APPPATH.'controllers/abstract/admin_fb.php');

/** 
 * This is admin controller for Shipping.
 * 
 * @package shop  
 * @author Michael Kovalskiy
 * @version 2012
 * @access public
 */
class Shipping extends Admin_fb 
{
    protected $process_form_html_id = "shipping"; 
	
	// +++++++++++++ INNER METHODS +++++++++++++++ //
	
	// +++++++++++++ INNER METHODS +++++++++++++++ //
	
	// +++++++++++++ ACTION METHODS +++++++++++++++ //

    /**
     * Generate "UkrPost" postal order from blank
     * @param int $customer_id
     */
    public function ukrposhtaPrint($orders_customer_info_id)
    {
        $blankPath = './store/blank/UkrPost.jpg';
        $fontPath = './fonts/arial.ttf';
        $this->load->model('orders_model');

        if( !file_exists($blankPath) ) echo 'Blank not exists: '.$blankPath;
        elseif( !file_exists($fontPath) ) echo 'Font not exists: '.$fontPath;
        elseif( !($customer = $this->orders_model->getOrderCustomerInfo($orders_customer_info_id) ) ) echo 'Customer not exists: '.$orders_customer_info_id;
        else
        {
            $image = imagecreatefromjpeg($blankPath);

            $font['color'] = imagecolorallocate($image, 0, 0, 0);
            $font['path'] = $fontPath;
            $font['size'] = 31;

            $text['x'] = 300;
            $text['y'] = 988;
            $text['str'] = $customer['surname'].' '.$customer['name'];
            imagettftext($image, $font['size'], 0, $text['x'], $text['y'], $font['color'], $font['path'], $text['str']);

            $text['x'] = 300;
            $text['y'] = 1090;
            $text['str'] = $customer['phone'];
            imagettftext($image, $font['size'], 0, $text['x'], $text['y'], $font['color'], $font['path'], $text['str']);

            $font['size'] = 27;
            $text['x'] = 255;
            $text['y'] = 1138;
            $text['str'] = "вул. {$customer['street']} {$customer['house_number']}";
            if($customer['apartment_number']) $text['str'] .= " кв. {$customer['apartment_number']}";
            imagettftext($image, $font['size'], 0, $text['x'], $text['y'], $font['color'], $font['path'], $text['str']);

            $text['x'] = 55;
            $text['y'] = 1188;
            $text['str'] = "Населений пункт: {$customer['place']}";
            imagettftext($image, $font['size'], 0, $text['x'], $text['y'], $font['color'], $font['path'], $text['str']);

            $text['x'] = 55;
            $text['y'] = 1232;
            $text['str'] = ($customer['rayon']) ? "{$customer['rayon']} район, " : "";
            $text['str'] .= "{$customer['region']} обл., {$customer['zip_code']}";
            imagettftext($image, $font['size'], 0, $text['x'], $text['y'], $font['color'], $font['path'], $text['str']);


            header('Content-type: image/jpeg');
            imagejpeg($image);
            imagedestroy($image);
        }
    }

    /**
     * Send shipping in for to NovaPoshta
     * @param bool $orderId
     */
    public function novaposhtaSend($orderId=false)
    {
        if(!$orderId) die('orderId is required');

        $this->load->model('orders_model');
        $this->load->model('products_model');
        $order = $this->orders_model->getOneById($orderId);
        if(!$order) die('Order not found');

        if($this->input->post())
        {
            $post = $this->input->post();

            //simple validate
            if(!$post['PayerType'] || !$post['CargoType'] || !$post['Weight'] || !$post['BackwardDeliveryExists'])
            {
                die('Invalid params');
            }

            $settings = $this->settings_model;

            require_once(APPPATH.'libraries/NovaPoshta/NovaPoshtaApi2.php');
            $np = new LisDev\Delivery\NovaPoshtaApi2($settings['novaposhta_apiKey'],'ua',TRUE,'curl');

            $sender = array();
            $sender['Sender'] = $settings['novaposhta_Sender'];//Sender Ref
            $sender['CitySender'] = $settings['novaposhta_CitySender'];//City Ref
            $sender['SenderAddress'] = $settings['novaposhta_SenderAddress'];//Department Ref !!!

            $contactSender = $np->getCounterpartyContactPersons($sender['Sender']);
            $sender['ContactSender'] = $contactSender['data'][0]['Ref'];
            $sender['SendersPhone'] = $contactSender['data'][0]['Phones'];


            $customer = $this->orders_model->getOrderCustomerInfo($order['orders_customer_info_id']);
            if(!$customer) die('Customer info not found');

            $phone = preg_replace('/[^0-9]/','',$customer['phone']);
            if(!preg_match('/^380/',$phone)) $phone = '380'.$phone;

            //TODO: check if there is "city_ref"
            $recipient = array(
                'FirstName' => $customer['name'],
                'LastName' => $customer['surname'],
                'Phone' => $phone,//TODO: validate phone on enter in form
                'CityRecipient' => $customer['city_ref'],
                'RecipientAddress' => $customer['department_ref'],
            );

            $params = array(
                'DateTime' => str_replace('/','.',$post['DateTime']),
                'ServiceType' => 'WarehouseWarehouse',
                'PaymentMethod' => 'Cash',
                'PayerType' => $post['PayerType'],
                'Cost' => $order['total'],
                'SeatsAmount' => '1',
                'Description' => $post['Description'],
                'CargoType' => $post['CargoType'],
                'Weight' => $post['Weight'],
            );

            if($post['BackwardDeliveryExists'] == 'yes')
            {
                $params['BackwardDeliveryData'][0] = array(
                    'PayerType' => $post['BackwardDeliveryPayerType'],
                    'CargoType' => 'Money',
                    'RedeliveryString' => $post['RedeliveryString']
                );
            }

            try {
                $result = $np->newInternetDocument($sender, $recipient, $params);
            }
            catch(Exception $exception){
                die('Exception: '.$exception->getMessage());
            }

            if($result['success'])
            {
                $docNumber = $result['data'][0]['IntDocNumber'];
                $this->orders_model->updateOrderCustomerInfo($order['orders_customer_info_id'], array('doc_number'=>$docNumber));

                redirect($this->_getBaseURL()."orders/edit/id/desc/0/".$orderId);
            }
            else dump($result);
        }
        else
        {
            $this->formbuilder_model->setFormMode('edit');
            $this->formbuilder_model->setFormData(array(
                'orderId' => $orderId,
                'DateTime' => date('d.m.Y'),
                'RedeliveryString' => $order['total'],
                'Weight' => $this->orders_model->getWeight($orderId)
            ));

            $data['tpl_page'] = 'shipping/novaposhta-send';
            $data['order_id'] = $orderId;

            parent::_OnOutput($data);
        }
    }

    /**
     * Show NovaPoshtaSenderId
     */
    public function novaposhtaSenderId()
    {
        $settings = $this->settings_model;

        if(empty($settings['novaposhta_apiKey'])) die('Fill API key at first');

        require_once(APPPATH.'libraries/NovaPoshta/NovaPoshtaApi2.php');
        $np = new LisDev\Delivery\NovaPoshtaApi2($settings['novaposhta_apiKey'],'ua',TRUE,'curl');

        $senders = $np->getCounterparties('Sender');

        dump($senders['data']);
    }

    /**
     * Send E-Invoice Number (novaposhta, ukrposhta)
     * @param int $orderId
     * @param string $transport (email|sms)
     */
    public function sendEN($orderId, $transport='email')
    {
        if(!$orderId) die('orderId is required');

        $this->load->model('orders_model');
        $order = $this->orders_model->getOneById($orderId);
        if(!$order) die('Order not found');

        $customer = $this->orders_model->getOrderCustomerInfo($order['orders_customer_info_id']);
        $this->orders_model->updateOrderCustomerInfo($order['orders_customer_info_id'], array('doc_number_sent'=>1));

        if($customer['shipping_type']=='ukrposhta') $shipping_company = 'Укрпоштою';
        elseif($customer['shipping_type']=='novaposhta') $shipping_company = 'Новою поштою';
        else $shipping_company = '';

        $tplVars = array('doc_number' => $customer['doc_number'], 'shipping_company' => $shipping_company);

        // === Mail Customer === //
        if($transport === 'email') {
            $this->load->model('auto_responders_model');
            $this->auto_responders_model->send(6, $customer['email'], $tplVars);
        }else{//sms
            $tpl = $this->settings_model['turbosms_text_invoice_number'];
            $this->load->library('Turbosms');
            $this->turbosms->send($customer['phone'], $tpl, $tplVars);
        }

        redirect($this->_getBaseURL().'orders/edit/id/desc/0/'.$order['id']);
    }

    /**
     * Send shipment to Ukrposhta
     * @param int $orderId
     */
    public function ukrposhtaSend($orderId)
    {
        if(!$orderId) die('orderId is required');

        $this->load->model('orders_model');
        $this->load->model('products_model');
        $order = $this->orders_model->getOneById($orderId);
        if(!$order) die('Order not found');

        if($this->input->post())
        {
            $post = $this->input->post();

            $settings = $this->settings_model;

            $customer = $this->orders_model->getOrderCustomerInfo($order['orders_customer_info_id']);
            if (!$customer) die('Customer info not found');

            $this->load->library('ukrposhta');
            $this->ukrposhta->setup($settings);

            try {
                $address = $this->ukrposhta->addAddress($customer);
                if (!isset($address['id'])) {
                    throw new Exception('Add address error: ' . $address['message']);
                }

                $client = $this->ukrposhta->addClient($customer, $address['id']);
                if (!isset($client['uuid'])) {
                    throw new Exception( 'Add client error: ' . $client['message']);
                }

                $shipment = $this->ukrposhta->addShipment($client['uuid'], $post);
                if (!isset($shipment['uuid'])) {
                    throw new Exception( 'Add shipment error: ' . $shipment['message']);
                }
            }
            catch (Exception $exception) {
                echo $exception->getMessage();
                exit;
            }

            $docNumber = $shipment['barcode'];
            $this->orders_model->updateOrderCustomerInfo($order['orders_customer_info_id'], array('doc_number'=>$docNumber));

            redirect($this->_getBaseURL()."orders/edit/id/desc/0/".$orderId);
        }
        else
        {
            $this->formbuilder_model->setFormMode('edit');
            $this->formbuilder_model->setFormData(array(
                'orderId' => $orderId,
                'weight' => $this->orders_model->getWeight($orderId)*1000,
                'declaredPrice' => $order['total'],
                'postPay' => $order['total'],
                'description' => 'ZNO-BOOKS_'.$orderId
            ));

            $data['tpl_page'] = 'shipping/ukrposhta-send';
            $data['order_id'] = $orderId;

            parent::_OnOutput($data);
        }
    }

    /**
     * Show Sticker for Ukrposhta as PDF
     * @param string $barcode
     */
    public function ukrposhtaSticker($barcode)
    {
        $uuid = $this->ukrposhtaUuidByBarcode($barcode);

        header('Content-type: application/pdf');
        echo $this->ukrposhta->getSticker($uuid);
    }

    /**
     * Delete shipment by API and remove its barcode from db
     * @param int $orderId
     */
    public function ukrposhtaDeleteShipment($orderId)
    {
        if(!$orderId) die('orderId is required');

        $this->load->model('orders_model');
        $order = $this->orders_model->getOneById($orderId);
        if(!$order) die('Order not found');

        $customer = $this->orders_model->getOrderCustomerInfo($order['orders_customer_info_id']);
        if (!$customer) die('Customer info not found');

        //delete shipment by API
        $uuid = $this->ukrposhtaUuidByBarcode($customer['doc_number']);
        $this->ukrposhta->deleteShipment($uuid);

        //remove shipment barcode (EN) from database
        $this->orders_model->updateOrderCustomerInfo($order['orders_customer_info_id'], array('doc_number'=>''));

        redirect($this->_getBaseURL()."orders/edit/id/desc/0/".$orderId);
    }

    /**
     * Return uuid by barcode
     * @param string $barcode
     * @return string
     */
    private function ukrposhtaUuidByBarcode($barcode)
    {
        if(!$barcode) die('barcode is required');

        $settings = $this->settings_model;
        $this->load->library('ukrposhta');
        $this->ukrposhta->setup($settings);

        $shipment = $this->ukrposhta->getShipmentByBarcode($barcode);
        if(!isset($shipment['uuid']))
        {
            die('Error: '.$shipment['message']);
        }

        return $shipment['uuid'];
    }
}