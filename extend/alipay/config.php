<?php

// halt($_SERVER);

$config = array(

    //应用ID,您的APPID。

    'app_id' => "2018051160131311",



    //商户私钥，您的原始格式RSA私钥

    'merchant_private_key' => "MIIEpAIBAAKCAQEA3AVkJAGL6OBA1anbz2B+r8qiy/bqNR6eD7BhGyfqGxafMZtR4YvPwFMgqzhrVnrzCqVIjWrHUZ8Z3ant1yxqmZQ2DD848C/4Vk+YdQeJLJ9C5FEPlRwOpQJXlL5PSv+EvO/Quk2L3fXnUgAwszXJrUKJzGdefqn7U2Ae09Xc8VLpsRyH5U2zFy8WVe3aRGQdM+6bminMteB/8jKhuLQVwrXGmmOVCxzxqje7NYOV4hMCxbZbD62bLWaOPnuPCOy7FAd4Yjj3rZWtGQxE98OeR//UtujQEBvjV7eZ+uN/8PFSYdctAXmGMYqJRkpNfALs9pwckXKiXFOdSnWnDtG4FwIDAQABAoIBAAUUVsiCBKHes4B7SGjUOHiEfBerXqLVTx7bmpJO7JetgQVbKZvEZ4pwINvzFH9NnVjMkg05vtpCiw1UA/qoutlK06iauQzKfrg3ZDSndOJD1v6AZJSzrh7fG/iCIECB/6WA/9edPLJmH+4DtRweDMbkN1wxsrvH3taA7JVFZbFohqjqnRoKr2cjz0CHHxMi+PZW8LXMtQ1M8S6/0tq3zDgGyQ4ehQ9tBM3UnnosOkU6p7BmU3xbZ6cPaaw7v9zVpxqNol0lvU1yHSvekVKYzPdEQYnA7+CvXclnkyQu2iG9VGFwomYjcEEmFbJGsMErPB1niOHhsVhMLPoQ8O7I8eECgYEA8WEKveUZiiUMBYX+GS/kQGQ6nPzh8dIj3y0CeIhKJ/lrDog4XnwO5Ws9n80hcmy2JnI39Q35lwGcHj9BAGyXdx/6BE+iqbcUQKY+2+A+ibPJhPUMZ8wsv1mziYHXkmSQ6P4M7xjdXYSOGYIjEcjjfjV4AZxjtAyeMEVIXDo3htECgYEA6Vko92efBO+QIi7LcYMmAB8TE6OjwYIb+acRxZuTkBQ/MxiASl1L7605Vfx7yC6mTrI+SnsJzJg8sV4/cpgED0wUvjgh0ZHCzLnpIHokioAKLu5Hvh0lX8I6HbYhBlIX+T+/cLUICswPwGnncIOUTVBYAFKHXPhQMpru24sFWmcCgYEAsiPrWJAvFBwZTi5AhHgq3TbbZybe3ag33yqJq0jyaaQS1YvFBj5KhBhsR3aNm7QNjjw3ffyNWlvOyyflohmCg2EvFGVtaBF8ueW3eHB71wa/0AdF24j88U+TSAVyglED/WAZTZAtNypv8PVxSlvXQTrW0WGI18WELZ3ENISmnDECgYBdG8xy24/iAkz6tr1Kr8/r4HpWLcdboTTUast2k9tPdCS7mJ0oDw4LtozYOISTnbmSUSD1yx5f2qbtBvQ+H1YPXIYyWGd2Hbkiw1O4N8R41xo5G67YtvyAtxlFXbTTttIGDF+w8ysgU8g9OIS2qmn0Z4iVqFiGyjYF2zhPtasPJQKBgQCI4hQRPFEvJe4bUnE8Gyne5FE8AyuVpIHmVMN9YYdUfvn5t/QYYSuSIGw4D6fvR7Al5WqzwjLOIgFjfBlJymVCUiXMpxoB4PtlBZRCdFGVFQGiy6cFQCyo36XpnEc2YdqZeU2dzDXGnuLH2P20FxdPXE51A2ZWGWj06khD1TtjXw==",  //异步通知地址

    'notify_url' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],



    //同步跳转

    'return_url' => "",



    //编码格式

    'charset' => "UTF-8",



    //签名方式

    'sign_type' => "RSA2",



    //支付宝网关 https://openapi.alipay.com/gateway.do

    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",



    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。

    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoH9Bvu0AOA3+qWATP9aGd4+3i8V+QQ9GzhhsJpw4jcaXzrpLn07a6tP0IhOMrvF9EqMydaIkhFFokfrVsH/jY2JnxTipS6qiRkauR58W63Hu7kg74sS3M08cpDpBNiSQAxJHwUGRnWVu5hQwcqe+R2c5wYhBQ0mzWYOPJz/AQetRgLbXxL1ilu9DYtrDjNu+4cgO2vRzBZ6lzbJ5Cf+Ea6HGJDI1j/NNC43Eq0au5EISnwG8J9c+HprOuViBLBI5743dLTK/WPZJLrTgUjaSLbuGc6ef52Bdifx4ssr3oqxpYr+gDLmzjInRau0llZpadd3yR+u+YtcKCG/WQMhrZQIDAQAB",


);



$GLOBALS['config'] = $config;