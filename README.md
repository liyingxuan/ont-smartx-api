## Ontology Web Smart Contract WEB Editor Project Management Server.

#### 1. Language and framework
PHP >=7.0.0  
Laravel v5.5.*  
[laravel-admin v1.5.x-dev](http://laravel-admin.org/)    

#### 2. Install and run
[Deploy the server environment](http://www.jianshu.com/p/1f17a69f6dcf)

Reboot server.

Go in project path :
```bash
$ cd [project path]
$ chmod -R 777 storage/

$ composer install
$ npm install

$ cp .env.example .env
$ php artisan key:generate 
$ vim .env
# 配置数据库相关配置、APP_URL、MY_API_HTTP_HEAD

$ php artisan migrate:install
$ php artisan migrate

$ php artisan passport:install
# 将生成的两个CLIENT_Secret保存到.env中的PASSPORT_CLIENT_SECRET

$ vim /etc/selinux/config
# 找到：
SELINUX=enforcing;
# 改成
SELINUX=enabled;

# 重启Linux服务器
```  
  
!! If you have problems you need to roll back to rebuild the database:    
!! 如果出现问题需要回滚重建数据库：  
```bash
$ php artisan migrate:refresh
$ php artisan migrate

# 删除./ont-sc-ide-project-ser/storage/oauth-private.key和oauth-public.key两个文件

$ php artisan passport:install
# 将生成的两个CLIENT_Secret保存到.env中
```


#### 3. 注意事项  
###### 如果无法使用token proxy  
> ./ont-sc-ide-project-ser/app/Http/Proxy/TokenProxy.php 中需要配置了env中的：  
MY_API_HTTP_HEAD=http://你的主机域名或ip/
才能正确使用，而且有可能不能为localhost。  
建议本地建虚拟主机，例如：http://ont.dev。  


###### 如果前端获取的token无法验证; Bearer token无法验证  
> 
```$xslt
/**
 * 设置JWT用户认证所需的http Authorization头信息：
 */
axios.interceptors.request.use(function (config) {
  if(jwtToken.getToken()) {
    // Bearer后需要一个空格！！！
    config.headers['Authorization'] = 'Bearer' + ' ' + jwtToken.getToken()
  }
  return config
}, function (error) {
  return Promise.reject(error)
})

```

###### Postman调试  
在Headers中增加：  
Key: Authorization  
Value: Bearer [登录之后生成的token，前面有一个空格]  

例子：  
Key: Authorization  
Value: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNkNGRkNWZkYzQ3MjgzN2IyNGFiOGJiYzhhWQiOiIyIiwianRpIjoiY2Q...

###### 测试网
需要在数据表插入数据：
insert into test_wallets values('1','{"address":"TA4fDRbGVYEZVicLz2WL96UMMJkYe1VLqc","label":"test_wallet","lock":false,"algorithm":"ECDSA","parameters":{"curve":"secp256r1"},"key":"6PYTrDdUqUfKsdmRhmGakthdx93ht3Qj8jvnF9MbDwstQypeVcKtrdRhhM"}',123456,'2018-04-20 14:46:55','2018-04-20 14:46:55');

提供用户免费试用ong提交。

