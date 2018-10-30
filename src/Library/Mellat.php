<?php

namespace Mellat;
use Almas\Mellat\Library\Exception;
use Illuminate\Support\Facades\DB;

Class Mellat{
	
	private $WsdlUrl = 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl';
	protected $refrenseid;
	
	protected function Request($transId,$Amount,$CallbackUrl){
		
		$fields = array(
			'terminalId' => $this->config->get('Config.Mellat.Terminal'),
			'userName' => $this->config->get('Config.Mellat.UserName'),
			'userPassword' => $this->config->get('Config.Mellat.PassWord'),
			'orderId' => $transId,
			'amount' => $Amount,
			'localDate' => $dateTime->format('Ymd'),
			'localTime' => $dateTime->format('His'),
			'additionalData' => '',
			'callBackUrl' => $CallbackUrl,
			'payerId' => 0,
		);	
		
		try {
			$soap = new \SoapClient($this->WsdlUrl);
            $response = $soap->bpPayRequest($fields);

		} catch (\SoapFault $e) {
			
			print_r($e->getMessage());
		}
		
		$result = explode(',', $response->return);
		if ($result[0] != '0') {
			
			$msg=new Exception($result[0]);
			print_r($msg);
		}
		
		$this->refrenseid = $response[1];
		$this->RefId();
		
	}
	
	
	protected function RefId()
	{
		$db=DB::table('transactions')->whereId($transId)->update([
			'refrenseid' => $this->refrenseid,
		]);
		
		return \View::make('Mellat::MellatGateWay')->with(compact('refrenseid'));

	}
	
}



?>