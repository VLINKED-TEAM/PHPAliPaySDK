<?php

namespace VlinkedAliPay\service;

/* *
 * 功能：支付宝手机网站alipay.trade.close (统一收单交易关闭接口)业务参数封装
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */


use VlinkedAliPay\aop\AopClient;
use VlinkedAliPay\config\AliPayConfigInterFace;
use VlinkedAliPay\exception\AliPayException;
use VlinkedAliPay\payload\AlipayDataDataserviceBillDownloadurlQueryRequest;
use VlinkedAliPay\payload\AlipayTradeCloseRequest;
use VlinkedAliPay\payload\AlipayTradeFastpayRefundQueryRequest;
use VlinkedAliPay\payload\AlipayTradeQueryRequest;
use VlinkedAliPay\payload\AlipayTradeRefundRequest;
use VlinkedAliPay\payload\AlipayTradeWapPayRequest;
use VlinkedAliPay\payload\builder\AlipayDataDataserviceBillDownloadurlQueryContentBuilder;
use VlinkedAliPay\payload\builder\AlipayTradeCloseContentBuilder;
use VlinkedAliPay\payload\builder\AlipayTradeQueryContentBuilder;
use VlinkedAliPay\payload\builder\AlipayTradeRefundContentBuilder;
use VlinkedAliPay\payload\builder\AlipayTradeWapPayContentBuilder;

class AlipayTradeService
{

    //支付宝网关地址
    public $gateway_url = "https://openapi.alipay.com/gateway.do";

    //支付宝公钥
    public $alipay_public_key;

    //商户私钥
    public $private_key;

    //应用id
    public $appid;

    //编码格式
    public $charset = "UTF-8";

    public $token = NULL;

    //返回数据格式
    public $format = "json";

    //签名方式
    public $signtype = "RSA";

    /**
     * AlipayTradeService constructor.
     * @param $config AliPayConfigInterFace
     * @throws AliPayException
     */
    function __construct($config)
    {
        $this->gateway_url = $config->getGatewayUrl();
        $this->appid = $config->getAppId();
        $this->private_key = $config->getMerchantPrivateKey();
        $this->alipay_public_key = $config->getAlipayPublicKey();
        $this->charset = $config->getCharset();
        $this->signtype = $config->getSignType();

        if (empty($this->appid) || trim($this->appid) == "") {
            throw new AliPayException("appid should not be NULL!");
        }
        if (empty($this->private_key) || trim($this->private_key) == "") {
            throw new AliPayException("private_key should not be NULL!");
        }
        if (empty($this->alipay_public_key) || trim($this->alipay_public_key) == "") {
            throw new AliPayException("alipay_public_key should not be NULL!");
        }
        if (empty($this->charset) || trim($this->charset) == "") {
            throw new AliPayException("charset should not be NULL!");
        }
        if (empty($this->gateway_url) || trim($this->gateway_url) == "") {
            throw new AliPayException("gateway_url should not be NULL!");
        }

    }

    /**
     * @param $alipay_config
     * @throws AliPayException
     */
    function AlipayWapPayService($alipay_config)
    {
        $this->__construct($alipay_config);
    }

    /**
     * alipay.trade.wap.pay
     * @param $builder AlipayTradeWapPayContentBuilder 业务参数，使用buildmodel中的对象生成。
     * @param $return_url string 同步跳转地址，公网可访问
     * @param $notify_url string 异步通知地址，公网可以访问
     * @return  $response 支付宝返回的信息
     * @throws AliPayException
     */
    function wapPay($builder, $return_url, $notify_url)
    {

        $biz_content = $builder->getBizContent();
        //打印业务参数
        $this->writeLog($biz_content);

        $request = new AlipayTradeWapPayRequest();

        $request->setNotifyUrl($notify_url);
        $request->setReturnUrl($return_url);
        $request->setBizContent($biz_content);

        // 首先调用支付api
        $response = $this->aopclientRequestExecute($request, true);
        // $response = $response->alipay_trade_wap_pay_response;
        return $response;
    }

    /**
     * @param $request
     * @param bool $ispage
     * @return bool|mixed|\SimpleXMLElement|string|\VlinkedAliPay\aop\提交表单HTML文本
     * @throws AliPayException
     */
    function aopclientRequestExecute($request, $ispage = false)
    {

        $aop = new AopClient ();
        $aop->gatewayUrl = $this->gateway_url;
        $aop->appId = $this->appid;
        $aop->rsaPrivateKey = $this->private_key;
        $aop->alipayrsaPublicKey = $this->alipay_public_key;
        $aop->apiVersion = "1.0";
        $aop->postCharset = $this->charset;
        $aop->format = $this->format;
        $aop->signType = $this->signtype;
        // 开启页面信息输出
        $aop->debugInfo = true;
        if ($ispage) {
            $result = $aop->pageExecute($request, "post");
            echo $result;
        } else {
            $result = $aop->Execute($request);
        }

        //打开后，将报文写入log文件
        $this->writeLog("response: " . var_export($result, true));
        return $result;
    }

    /**
     * alipay.trade.query (统一收单线下交易查询)
     * @param $builder AlipayTradeQueryContentBuilder 业务参数，使用buildmodel中的对象生成。
     * @return $response 支付宝返回的信息
     * @throws AliPayException
     */
    function Query($builder)
    {
        $biz_content = $builder->getBizContent();
        //打印业务参数
        $this->writeLog($biz_content);
        $request = new AlipayTradeQueryRequest();
        $request->setBizContent($biz_content);

        // 首先调用支付api
        $response = $this->aopclientRequestExecute($request);
        $response = $response->alipay_trade_query_response;
        var_dump($response);
        return $response;
    }

    /**
     * alipay.trade.refund (统一收单交易退款接口)
     * @param $builder AlipayTradeRefundContentBuilder 业务参数，使用buildmodel中的对象生成。
     * @return $response 支付宝返回的信息
     * @throws AliPayException
     */
    function Refund($builder)
    {
        $biz_content = $builder->getBizContent();
        //打印业务参数
        $this->writeLog($biz_content);
        $request = new AlipayTradeRefundRequest();
        $request->setBizContent($biz_content);

        // 首先调用支付api
        $response = $this->aopclientRequestExecute($request);
        $response = $response->alipay_trade_refund_response;
        var_dump($response);
        return $response;
    }

    /**
     * alipay.trade.close (统一收单交易关闭接口)
     * @param $builder AlipayTradeCloseContentBuilder  业务参数，使用buildmodel中的对象生成。
     * @return $response 支付宝返回的信息
     * @throws AliPayException
     */
    function Close($builder)
    {
        $biz_content = $builder->getBizContent();
        //打印业务参数
        $this->writeLog($biz_content);
        $request = new AlipayTradeCloseRequest();
        $request->setBizContent($biz_content);

        // 首先调用支付api
        $response = $this->aopclientRequestExecute($request);
        $response = $response->alipay_trade_close_response;
        var_dump($response);
        return $response;
    }

    /**
     * 退款查询   alipay.trade.fastpay.refund.query (统一收单交易退款查询)
     * @param $builder AlipayTradeRefundContentBuilder  业务参数，使用buildmodel中的对象生成。
     * @return $response 支付宝返回的信息
     * @throws AliPayException
     */
    function refundQuery($builder)
    {
        $biz_content = $builder->getBizContent();
        //打印业务参数
        $this->writeLog($biz_content);
        $request = new AlipayTradeFastpayRefundQueryRequest();
        $request->setBizContent($biz_content);

        // 首先调用支付api
        $response = $this->aopclientRequestExecute($request);
        var_dump($response);
        return $response;
    }

    /**
     * alipay.data.dataservice.bill.downloadurl.query (查询对账单下载地址)
     * @param $builder AlipayDataDataserviceBillDownloadurlQueryContentBuilder  业务参数，使用buildmodel中的对象生成。
     * @return $response 支付宝返回的信息
     * @throws AliPayException
     */
    function downloadurlQuery($builder)
    {
        $biz_content = $builder->getBizContent();
        //打印业务参数
        $this->writeLog($biz_content);
        $request = new AlipayDataDataserviceBillDownloadurlQueryRequest();
        $request->setBizContent($biz_content);

        // 首先调用支付api
        $response = $this->aopclientRequestExecute($request);
        $response = $response->alipay_data_dataservice_bill_downloadurl_query_response;
        var_dump($response);
        return $response;
    }

    /**
     * 验签方法
     * @param $arr 验签支付宝返回的信息，使用支付宝公钥。
     * @return boolean
     */
    function check($arr)
    {
        $aop = new AopClient();
        $aop->alipayrsaPublicKey = $this->alipay_public_key;
        $result = $aop->rsaCheckV1($arr, $this->alipay_public_key, $this->signtype);
        return $result;
    }

    //请确保项目文件有可写权限，不然打印不了日志。
    function writeLog($text)
    {
        // $text=iconv("GBK", "UTF-8//IGNORE", $text);
        //$text = characet ( $text );
        file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "./../../log.txt", date("Y-m-d H:i:s") . "  " . $text . "\r\n", FILE_APPEND);
    }


    /** *利用google api生成二维码图片
     * $content：二维码内容参数
     * $size：生成二维码的尺寸，宽度和高度的值
     * $lev：可选参数，纠错等级
     * $margin：生成的二维码离边框的距离
     */
    function create_erweima($content, $size = '200', $lev = 'L', $margin = '0')
    {
        $content = urlencode($content);
        $image = '<img src="http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&amp;cht=qr&chld=' . $lev . '|' . $margin . '&amp;chl=' . $content . '"  widht="' . $size . '" height="' . $size . '" />';
        return $image;
    }
}

?>