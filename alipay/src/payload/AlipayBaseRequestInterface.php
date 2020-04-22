<?php


namespace VlinkedAliPay\payload;


interface AlipayBaseRequestInterface
{
    public function getApiVersion();

    public function getApiMethodName();

    public function getTerminalType();

    public function getTerminalInfo();

    public function getProdCode();

    public function getNotifyUrl();

    public function getReturnUrl();

    public function getApiParas();
}