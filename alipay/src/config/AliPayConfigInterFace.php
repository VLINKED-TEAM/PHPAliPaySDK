<?php

namespace VlinkedAliPay\config;


/**@url https://openhome.alipay.com/platform/keyManage.htm 对应配置
 * 支付宝应用配置信息
 * @package VlinkedAliPay\config
 */
abstract class AliPayConfigInterFace
{

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
     * 应用AppId
     * 查看地址 @url https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
     * @return mixed
     */
    abstract public function getAppId();


    /**
     * 在生成签名的时候记录的 丢失无法找回
     * 【应用私钥】
     * @return mixed
     */
    abstract public function getMerchantPrivateKey();


    /**
     * 【应用公钥】
     * @url https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的商户应用公钥。
     * @return mixed
     */
    abstract public function getMerchantPublicKey();

    /**
     * 【支付宝公钥】
     * 查看地址 @url https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
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