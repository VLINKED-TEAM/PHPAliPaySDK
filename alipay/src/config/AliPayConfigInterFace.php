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
     * 异步通知地址
     * @return mixed
     */
    abstract public function getNotifyUrl();

    /**
     * 页面支付后跳转地址
     * @return mixed
     */
    abstract public function getReturnUrl();


    /**
     * 网关地址
     * @return mixed
     */
    public function getGatewayUrl()
    {
        return "https://openapi.alipay.com/gateway.do";
    }


    /**
     * appid
     * @return mixed
     */
    abstract public function getAppId();


    /**
     * 商户应用私钥
     * @return mixed
     */
    abstract public function getMerchantPrivateKey();


    /**
     * 支付宝公钥
     * @return mixed
     */
    abstract public function getAlipayPublicKey();


    /**
     * 字符编码
     * @return mixed
     */
    public function getCharset()
    {
        return "UTF-8";
    }


    /**
     *
     * 签名方式
     * @return mixed
     */
    public function getSignType()
    {
        return "RSA";
    }


}