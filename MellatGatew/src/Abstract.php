<?php
namespace Almas\Mellat;

use Illuminate\Support\Facades\Request;


abstract class Abstract
{
	protected $transactionId = null;
	protected $transaction = null;
	protected $cardNumber = '';
	protected $config;
	protected $refId;
	protected $amount;
	protected $callbackUrl;
	protected $trackingCode;
	
	function cardNumber()
	{
		return $this->cardNumber;
	}
	function trackingCode()
	{
		return $this->trackingCode;
	}
	function transactionId()
	{
		return $this->transactionId;
	}
	function refId()
	{
		return $this->refId;
	}
	function price($price)
	{
		return $this->set($price);
	}
	function getPrice()
	{
		return $this->amount;
	}
	
	function verify($transaction)
	{
		$this->transaction = $transaction;
		$this->transactionId = $transaction->id;
		$this->amount = intval($transaction->price);
		$this->refId = $transaction->ref_id;
	}
	
	protected function newTrans()
	{

		$this->transactionId = $this->getTable()->insert([
			'id' => $uid,
			'port' => $this->getPortName(),
			'price' => $this->amount,
			'status' => Enum::TRANSACTION_INIT,
			'ip' => Request::getClientIp(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]) ? $uid : null;

		return $this->transactionId;
	}
	
	protected function transactionSucceed()
	{
		return $this->getTable()->whereId($this->transactionId)->update([
			'status' => Enum::TRANSACTION_SUCCEED,
			'tracking_code' => $this->trackingCode,
			'card_number' => $this->cardNumber,
			'payment_date' => Carbon::now(),
			'updated_at' => Carbon::now(),
		]);
	}

	protected function transactionFailed()
	{
		return $this->getTable()->whereId($this->transactionId)->update([
			'status' => Enum::TRANSACTION_FAILED,
			'updated_at' => Carbon::now(),
		]);
	}

	protected function transactionSetRefId()
	{
		return $this->getTable()->whereId($this->transactionId)->update([
			'ref_id' => $this->refId,
			'updated_at' => Carbon::now(),
		]);

	}
	
}


?>