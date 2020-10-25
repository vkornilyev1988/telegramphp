<?php
namespace Koks;

class WalletOne
{
	//DEFAULT Configuration
	/*Success URL Адрес страницы, на которые будет отправлен покупатель после успешной оплаты.*/
	public $SUCCESS_URL = "http://example.com/";
	/*FAIL URL Адрес страницы, на которые будет отправлен покупатель после неуспешной оплаты.*/
	public $FAIL_URL = "http://example.com/";
	/*Идентификатор валюты (ISO 4217)*/
	public $CURRENCY_ID = 398;
	//--END DEFAULT Configuration
	private $FormParams = array();
	private $__callback = array();
	private $__Merchant_Id;
	private $__Merchant_SecretKey;
	public function __construct($merchant_id, $secret_key)
	{
		$this->__Merchant_Id = $merchant_id;
		$this->__Merchant_SecretKey = $secret_key;
	}
	public function Pay($payId,$amount)
	{
		$this->SetParam('WMI_MERCHANT_ID',$this->__Merchant_Id);
		$this->SetParam('WMI_PAYMENT_AMOUNT',$amount);
		$this->SetParam('WMI_CURRENCY_ID',$this->CURRENCY_ID);
		$this->SetParam('WMI_PAYMENT_NO',$payId);
		$this->SetParam('WMI_SUCCESS_URL',$this->SUCCESS_URL);
		$this->SetParam('WMI_FAIL_URL',$this->FAIL_URL);
		$this->SetParam('WMI_AutoLocation',0);
		$this->sortParams();
		$Signature = $this->GenerateSignature();
		$this->SetParam('WMI_SIGNATURE',$Signature);
	}
	public function SetParam($name,$value)
	{
		$this->FormParams[$name] = $value;
	}
	private function sortParams()
	{
		uksort($this->FormParams, "strcasecmp");
	}
	private function GenerateSignature()
	{
		$Signature = implode($this->FormParams);
		$Signature = $Signature.$this->__Merchant_SecretKey;
		$Signature = md5($Signature);
		$Signature = pack("H*",$Signature);
		$Signature = base64_encode($Signature);
		return $Signature;
	}
    public function getParams()
    {
        return $this->FormParams;
    }

	public function result()
	{
		foreach($_POST as $name => $value){
			if($name !== "WMI_SIGNATURE") $this->SetParam($name,$value);
		}
		$this->sortParams();
		$Signature = $this->GenerateSignature();
        if(!isset($_POST["WMI_SIGNATURE"])) $_POST["WMI_SIGNATURE"] = "";
		if($Signature == $_POST["WMI_SIGNATURE"]){
			if($this->FormParams["WMI_ORDER_STATE"] == 'Accepted'){
				$this->__callback["status"] = 1;
				$this->__callback["text"] = 'WMI_RESULT=OK';
			}else{
				$this->__callback["status"] = 2;
			}
		}else{
			$this->__callback["status"] = 2;
		}
        if(isset($this->FormParams["WMI_PAYMENT_NO"])) $this->__callback["id"] = $this->FormParams["WMI_PAYMENT_NO"];
		$this->__callback["Signature"] = $this->GenerateSignature();
		return $this->__callback;
	}
	public function callback()
	{
		$this->__callback["id"] = $this->FormParams["WMI_PAYMENT_NO"];
		$this->__callback["Signature"] = $this->GenerateSignature();
		return $this->__callback;
	}
}
