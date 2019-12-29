<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2019082866515382",
		//编码格式
		'charset' => "UTF-8",
		//签名方式
		'sign_type'=>"RSA2",
		//scope auth_user获取用户信息; auth_base静默授权
		'scope' => 'auth_user',
		//跳转地址
		'redirect_url' => 'http://'.$_SERVER['HTTP_HOST'].'/home/login/alicallback',
        //支付宝授权地址 沙箱环境
//        'oauth_url' => 'https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm',
        //支付宝授权地址 正式环境
         'oauth_url' => 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm',
        //支付宝网关 沙箱环境
//        'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
        //支付宝网关 正式环境
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAvJ5M7OZQO5hIm4jmC4URoBradFkb0IOwAbEmEBQuQArQsIB8oejLDkBcGPROvLfDXK/1lRtNxmILOOnC3rSzWHGLOASRSFX84sO/UypLzhwXflGoVyx2y0BDX+6vXvJHHFc5lbXTEcKlvbL4jiDvjlxZmTLgyVTV8mhmDE0Pie1E722B3cvCgjdqhWXOqglpCWxo2nj78n4KcDDRsyhufrCmDPt57MBpA5nvbaetHU0CukWX8u8vI8ad7yjhVmkiSvbvl5UZyuZZxbDQHY3vd7qNxsvwBNUXsJ9NyxLLEyJJ36OI9IeaXdAeiltFdB8aa9x+LCCEZVm7FsIEBCQJCQIDAQABAoIBAFOZOfEZ11/CjBPbplJexUQYAtDkmc5eP4lQcdVYjHM4F+zS1eqRGkaTwf4RckB0ljMjjg5rTppp5B1yhjtdDcxabuECLT5JVk9PgSIkMfsFOmhzWtBgVbqaHgKL8NB9Q4VNJ6myL/3ELt+YTk/4SxTm25NqGHVDk5vgJ7K2CPgRHdC4gWwhWMKV2iQgxpd08LmJnazexWmOu0V4TX0dtTAE1KlnHUs7FK4inE5A/hm+xjEbdntZ/6gzQIHRTFBpEOs5ZxDu6w1W4Ndk3hPVCUfCtEHNQbkua0zjBQcythxSCOOj/csm1l0CRJHrZcUSjoeL7bFqrlDEdx4yHomaYt0CgYEA4NLcMLN7JyZcBfOgXAxCLPJ36jx2XY8bZvFTOliyVNLhkHaIpBBkvwQI0gkOX76IGEsfRErOOkM/1hjNev+0FBDwvyrWXTf5+9Xy3U+8Fc0+Cf9icf+4gPel019NyfRSTW2CXm9FLMF2JjNLFI1tQo4gwa7+T9nWTBKq1zuB5XMCgYEA1sYrAs82u2MPlm9tM90SGiU6x2AlTizq9t4dXVltrk9Urd7lWWw23Ej86AwX0GZeDCVnLb5aBcm8cEbMgi63yynm3TcmyV5aQieRaSJP1d6FL9htENrDMzM2U3SYDcam1b/dKup3RKgclJAjc2k3gmBOpKMQEeDYgm0FBZormJMCgYEA31TXYpGITtWuKENhEs8ilZ+vO0IgmsPkBpjHgnhFfjmV1HnLNp8KVS3ezTYtzzJn4yoMvzxILFxNd5Jf3EVST3SckmotU6CddWuMvAfO4SFm9Wt6EaBjWcmoMpbDoVBQyZV1IfYKk3ECuuPNO3daB/lD1OLOwee1FxCcIP5+BL0CgYAf0Vnb37DfD+zmP3mxHkYpQ2yyzsaYD0V77ynwg2ghU7Va0NOvbL2v4sw84FXC1PeH2x6vAy05AEr/Yy22947Y9UszaJFBcc3zUQNUzPWA3KdkkC6QOaiDdEnPEU3ZdJ2Quwzb58JllHYveC8YUPUdRoFAGLwvudy5bcc5rj5ZKwKBgALck2HlpztZe9KjpMc1WGheRVMrgFyv8UR3WGDrWWVg6uI1CT4f2I0G3qr4G8amrz05Ro5KJS+rEzYJVvLZVjWPPXoVO1xIgQamNEJBYsBnSP+24PnFZ2oIOYdFyVmVV4ECyWUZvNKxqYIEHF08suIR6c/3r14A5t+KRPM0IrDj",

);