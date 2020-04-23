<?php


namespace VlinkedAliPay\payload\enums;

/**
 * @URL https://opendocs.alipay.com/open/203/107090/
 * Class PayChannelEnums
 * @package VlinkedAliPay\payload\enums
 */
class PayChannelEnums
{

    /**
     * 信用卡
     */
    const CREDIT_CARD = "creditCard";
    /**
     * 红包
     */
    const COUPON = "coupon";

    /**
     * 借记卡快捷
     */
    const DEBIT_CARD_EXPRESS = "debitCardExpress";

    /**
     * 商户预存卡
     */
    const M_CARD = "mcard";
    /**
     * 个人预存卡
     */
    const P_CARD = "pcard";

    /**
     *	优惠（包含实时优惠+商户优惠）
     */
    const PROMOTION = "promotion";


    /**
     * 还有一些没包含
     */








}