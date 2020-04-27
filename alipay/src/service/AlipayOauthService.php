<?php


namespace VlinkedAliPay\service;


use VlinkedAliPay\aop\AopClient;
use VlinkedAliPay\config\AliPayConfigInterFace;
use VlinkedAliPay\exception\AliPayException;
use VlinkedAliPay\payload\AlipayBaseRequestInterface;
use VlinkedAliPay\payload\AlipaySystemOauthTokenRequest;
use VlinkedAliPay\payload\enums\AuthScopeEnums;

/**
 * 支付宝授权处理服务
 * @package VlinkedAliPay\service
 */
class AlipayOauthService
{


    const OPEN_AUTH_HOST = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm";

    /**
     * @var AliPayConfigInterFace
     */
    private $alipayConfig;


    private $callbackUrl;
    /**
     * AlipayOauthService constructor.
     * @param AliPayConfigInterFace $alipayConfig
     */
    public function __construct($alipayConfig)
    {
        $this->alipayConfig = $alipayConfig;
    }

    /**
     * @param $callbackUrl string 回调地址
     * @param string|AuthScopeEnums
     */
    public function toLogin($callbackUrl,$scope){
       $param=[
           "app_id"=>$this->alipayConfig->getAppId(),
           "scope"=>$scope,
           "redirect_uri"=>$callbackUrl
       ];
       $fullUrl = self::OPEN_AUTH_HOST."?".http_build_query($param);
       header("Location: $fullUrl");
    }

    /***
     * @param $auth_code string
     * @return object
     * @throws AliPayException
     */
    public function code2info($auth_code){
        $request = new AlipaySystemOauthTokenRequest ();
        $request->setGrantType("authorization_code");
        $request->setCode($auth_code);
       return $this->aopClientExe($request);
    }


    /**
     * @param $request AlipayBaseRequestInterface
     * @return object
     * @throws AliPayException
     */
    private function aopClientExe($request){
        $aop = new AopClient();
        $aop->gatewayUrl = $this->alipayConfig->getGatewayUrl();
        $aop->appId = $this->alipayConfig->getAppId();
        $aop->rsaPrivateKey = $this->alipayConfig->getMerchantPrivateKey();
        $aop->alipayrsaPublicKey = $this->alipayConfig->getAlipayPublicKey();
        $aop->apiVersion = "1.0";
        $aop->postCharset = $this->alipayConfig->getCharset();
        $aop->format = "json";
        $aop->signType = $this->alipayConfig->getSignType();
        return $aop->Execute($request);
    }



}