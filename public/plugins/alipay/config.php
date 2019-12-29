<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016101600701887",

		//商户私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEAuksP5qiexeH3hjfOiJMH9rU77/hepg+LhIWk3KhqiqY6+6RVz2ii4XrENDDB91BzGRyFJqm012sZepPHwZnDVb3YkL/k+ySLIJdO3Eyk/SRuwObcHCg8cZXllGTODj33LUIQWMiatxk1cIEilfbsfWqlnu5vUnQqgDAyKWZ8K+yAMfgQElCvH5s6eZ9h2WtSvlqFzWH3BU938p+hm6Ec7YuiQo+qUX0vXoznbIQApXDsmF24joAsf8GkAhdCdqWSGQM4N4wiwpI7XiscJWjlQTvagV2BpPKQaqtRUgDHI8HR923j6wgbJrZRQYR65HPuiNLL38Su3MgHeos+pmfkoQIDAQABAoIBAQCxNJ2Xh2YgTWYqogMwHsxAfPzas6M0yyynjojX7MwLvzv0CsolVR865JCmJIsdOcWCaYKu2FdRYmsGEnS9UApjEQdkCWVDD+vXwJYfMDjxIyrHC9LsZcm5MiEEGy92lLL+tgzep1OkP7J5phzEEfG7CysoFx6FjEKxVSciAn6zN84cdHai0+ccTAt4Wepeg7rlp9aFgYb6Q0nA38KXfPos0YzSehgjscysegeYldTgUUoIOLhD75y8R6XNAp6MXR3vA3SIWMeSE2kPmcfQkFGhxnw47U1OruEKZiW8VeAb2ZEGY00xfzLGrLdGdHz/zFSuQBN11B2qHZVfEW3LxTgRAoGBAPVu6QxTNwRk01HzRw0X/bOPCXWip91NPZUOYcmoAtA5bIoy2whUgKuh3LuKlxgFxZ3weSl8TaSRokXadvGem60TlGLGyPFR1IpNQxIDVnSBlb/4IHkjr0P3jVunOMNBMnrlSs2gUSl/YQyphIb0TqvfT+GeAKDdqVVpX1yvvDD3AoGBAMJQVBs445Wa2iRlZ7YLmYGlJVpKylgGHf4Co9XJVue5rLI8dGE2hNU3pHnMBg0MQL3nPJ3vGG1eo7AAyxdOwEexJSou6+WwKojCtOySsEC+PFgzr252JEVdY4HJnVamFrhUZf36X4ox1vGQP2ixeYYpoahZBuDAA5xynZzz6kknAoGABzw5yo85c/u07xmI4q3uYLi6wqkE4dVBF3/RCizVyGWo+Xn+UwSKtoSTCURQp/ijlsBcEgkEEPHqIr0J/J18YWHOYgsSKWsMJPeaHpHSnqSjUYFzE58lr49Ar48CcV8eqdjQl7c+LUcACWuF6KSTHSX8KN6LjjJ6p3Xvxb+4gIkCgYEAjR0dF2/nBzyf8xKZkME3x8kRKVNrQyWeHlv4c0d2j25uFjqFIhwft2BjV/hs5Ijjc4Y8pU1/5d127liFJPYR+X6SSOIuem3HVe5gyfV1fm8pD7zpSEUP7jf2DclHNCgGE/Nm5l0viQLM4D0rb3KDmIUfW0zcC9gmRzWNdPBGUr0CgYAhLwyq45QGkZgf5dvk7IqYXv3KTAaIvNs6Iw+v1DaaHeewhfS/xwRzS175bj4waUyIWkYilzbgdo3QIZak2QCXzoXiWVm5fvkn4SMmXwUPGR41VTVPtD7tmo2AdVebC7AAl1rQoqVV/1hYJeM6AuRp7ymUbGOL0y2iR/ZQX/0vTg==",

		//异步通知地址
		'notify_url' => "http://www.pyg.com/home/order/notify",

		//同步跳转
		'return_url' => "http://www.pyg.com/home/order/callback",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
        //正式环境网关
		//'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
        //沙箱环境网关
        'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjDiTqBWCBXxwd2KLRNdFNBJ2U87iT94TOOKq2E7JlKnPVV/Ck+B1nIR0UQz8f2F4S8uU2BA/y3VH7KKTPuzxlaJAj3H216MpTlvsJ3q4hzKzlEQMI1TV7xBh+DR2cxsrAPSpIcLqx5uAhk6154DW/0s55gdzW6BsL7Y4WuK1sNCGgPImTc24aQvv0ekfQWEm4kPNIRJ85+ELhvzXM0JtvZYIsA77cyInKiB9CqjmqvBvDVDg7tazkYihv8oYffV3g/OGUVVpDavC7FmbBgWB/ZaYrsxRjAbDsYYZXSTbohitRUuKLRK9axS4+z7G9GGuHWBh7TP4EH87/F5d0YVhowIDAQAB",
);