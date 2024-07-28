## sleepy
Let your friends know if you're sleeping or not.  
让你的朋友们知道你是否睡似了

## 实现原理

项目后端使用PHP编写，主要接口有switch(切换状态)与query(查询状态)。  

通过带着secret(密钥)和status(状态代码，默认0为睡着，1为醒着)请求switch接口来切换当前的状态，switch接口收到请求后会先检测secret是否正确，如果secret不正确则直接返回错误，如果正确则将当前状态以文字形式(sleeping,awake)写入status.txt文件中。  

当用户访问前端网页时，网页中的JavaScript将会向后端query接口发送请求，query接口无需密钥，将直接返回当前状态代码，JavaScript再分析并更新网页内容，达到显示当前状态的目的。  

(提醒按时睡觉的功能还未开发完成。)
<br><br>

## 配置指南 <p style="font-size: 70%;">建议了解实现原理后再进行配置。</a>
在`assets/switch/secret.php`中，将自己想要的secret赋值给变量`secret`，例如`$secret = '12345678';`。

修改网页中的信息，使网页更符合自己的喜好。

若希望实现网页中的邮件提醒按时睡觉的功能，需要配置`remind/mail.php`，在该文件中定义如下变量：
| 变量 | 内容 |
| ---- | ---- |
| stmp | SMTP服务器地址 |
| port | SMTP服务器端口 |
| mail | 发件邮箱地址(用户名) |
| pwd | 发件邮箱密码 |

示例：
```php
$smtp = 'smtp.example.com'; //SMTP服务器地址
$port = '587'; //SMTP服务器端口
$mail = 'maomao@example.com'; //发件邮箱地址(用户名)
$pwd = '12345678'; //发件邮箱密码
```


此外，还需注意的是，根据Apache 2.0开源协议，您必须使用相同的许可证，并保留原始的版权声明：  
`©2024 毛毛(maao.cc) All Rights Reserved.`