<?php

namespace VlinkedAliPay\config;

abstract class AliPayConfigInterFace
{
//    private $gatewayUrl;
//    private $app_id;
//    private $merchant_private_key;
//    private $alipay_public_key;
//    private $charset;
//    private $sign_type;

    /**
     * @return mixed
     */
    public function getGatewayUrl()
    {
        return "https://openapi.alipay.com/gateway.do";
    }


    /**
     * @return mixed
     */
    abstract public function getAppId();


    /**
     * @return mixed
     */
    abstract public function getMerchantPrivateKey();


    /**
     * @return mixed
     */
    abstract public function getAlipayPublicKey();


    /**
     * @return mixed
     */
    public function getCharset()
    {
        return "UTF-8";
    }


    /**
     * @return mixed
     */
    public function getSignType()
    {
        return "RSA";
    }


}