<?php

return [

    //应用ID,您的APPID。
    'app_id' => "2017090808621871",

    //商户私钥，您的原始格式RSA私钥
    'merchant_private_key' => "MIICWwIBAAKBgQChYwgtrZVoaGfRQumyivAd+3Hls2hwuWUrNDAezQzaYUuzk3KLw7tWFI3kc9ftkWNNGh4RPgCacOhlHpMTHYOK7CyUcK4f9Vxe0sOVi9QNZxE72lOnY15z/RcMyn5GdOzbm5Uctk+4s+Y9BtPmjTbmdUylGDVSFNQVvoCF1zAhdwIDAQABAoGAGXUnuxzgrqGZjtcBSlrEWdZqNxO/69gy70fCN3mdB23FpUQjE40CpieD4n+rlhwTD/yItauUKMiawCi8ho1nTGgAsTuz2vZCgUMXtkUfVEzoLoPMjNS73aSNcHNaZUaqsFu2SvISfbqGAJwPVBHdXB4X/lmI+2sJIWdzCnrpqxECQQDqeOwhRAccW/x6z8iNzrOYIemPZ1GoHBJairDc5zYGRIUzbxgidLDaITLkeMEv2vufZOAV6XR6oYKg5a+/QYvbAkEAsDRLoyLQCqPIRzA8/90AW8ZgtIkrDsrdo0Ot+RvT70cLBK8jnw/6miKqTMB5FWlwOQEQ/lnO8TOBOO6i1c+hlQJAaAhbDK94UDboIT2MCI9L1BZH4N7AtkwuQIoEyx6zM7i3OQjztmX8Cexs/j4tySPqPOCvJEm+6qI6qVT/02YrvQJALy4RgqDzq6WnHsifzLLUQb6gbja8PL29EtYwYx88ZDlVje+HTjPtGhBmIR74TO9qiRjr/+70JklwNFpRZ4as8QJAGXS9PNcT+3aAjUWGDo88bObLRsp+Y4+rJCoF1evkbfPaIATKxb6bO7iADM25J3yum9hrqkTzY00xvKf79s83/w==",

    //异步通知地址
    'notify_url' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],

    //同步跳转
    'return_url' => "",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type' => "RSA",

    //支付宝网关 https://openapi.alipay.com/gateway.do
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB"
];