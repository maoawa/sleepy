**暂停开发通知 (2024.1.11)**

自从中考结束，开发出sleepy并且分享给大家的一段时间后，我就逐渐体会到使用这种使用接口来更新状态的方式并没有想象中的那么方便，甚至因为我比较懒，在上学后就基本没再更新过。我心里其实一直有个想法是将家里的智能家居的状态也分享出来，大家可以更全面地“视奸”我。上周重新捡起了这个项目，验证了可行性，写出了一个简单的demo。但是由于代码质量太差，又需要重新开发。我希望使用虚拟的智能家居设备作为开关来更新睡眠的状态，于是决定开一个新项目(但是没有完全新)。

新项目: [`maoawa/sleepy-smart-home`](https://github.com/maoawa/sleepy-smart-home)

此外，各位也可以看看大佬`wyf9`的sleepy重置版: [`wyf9/sleepy`](https://github.com/wyf9/sleepy) 使用Python重写了后端，比PHP好使多了()

感谢所有朋友们一直以来的支持

---
## sleepy
Let your friends know if you're sleeping or not.  
让你的朋友们知道你是否睡似了

## 实现原理

项目后端使用PHP编写，主要接口有switch(切换状态)与query(查询状态)。  

通过带着secret(密钥)和status(状态代码，默认0为睡着，1为醒着)请求switch接口来切换当前的状态，switch接口收到请求后会先检测secret是否正确，如果secret不正确则直接返回错误，如果正确则将当前状态以文字形式(sleeping, awake)写入status.txt文件中。  

当用户访问前端网页时，网页中的JavaScript将会向后端query接口发送请求，query接口无需密钥，将直接返回当前状态代码，JavaScript再分析并更新网页内容，达到显示当前状态的目的。  

(提醒按时睡觉的功能还未开发完成。)
<br><br>

## <span id="configGuide">配置指南</span> <p style="font-size: 70%;">建议了解实现原理后再进行配置。</a>
在`switch/secret.php`中，将自己的密钥赋值给变量`secret`，例如`$secret = '12345678';`。

修改网页中的信息，使网页更符合自己的喜好。

如果修改过服务器默认文档，请将`index.php`及`index.html`加入默认文档列表中。

保存状态的文件位置默认为根目录下的`status.txt`。如果想要更改储存状态的文件位置，请修改`switch/index.php`文件中的第三行：`$status_file = 'path/to/file.dat';`。注意目录层级关系，同时请确保PHP有权限修改该文件。

若希望实现网页中的邮件提醒按时睡觉的功能，需要配置`remind/mail.php`，在该文件中给如下变量赋值：
| 变量 | 内容 |
| ---- | ---- |
| stmp | SMTP服务器地址 |
| port | SMTP服务器端口 |
| mail | 发件邮箱地址(用户名) |
| pwd | 发件邮箱密码 |
| mailto | 收件邮箱地址 |

示例：
```php
$smtp = 'smtp.example.com'; //SMTP服务器地址
$port = '587'; //SMTP服务器端口
$mail = 'mail@example.com'; //发件邮箱地址(用户名)
$pwd = '12345678'; //发件邮箱密码
$mailto = 'maomao@example.com' //收件邮箱地址
```
建议不使用此功能时删除相关代码，以最大程度优化性能。

此外，还需注意的是，根据Apache 2.0开源协议，您必须在修改后的版本中使用相同的许可证，并保留原始的版权声明：  
`©2024 毛毛(maao.cc) All Rights Reserved.`

## <span id="updateStatus">更新状态</span>
配置完毕后，使用`GET`方式并带`secret`与`status`参数请求`switch/`接口即可更新状态。如果状态更新成功，将会返回`ok`。发生错误时，可能返回的信息如下：
|返回内容|错误内容|可能的解决方式|
|----|----|----|
|`Invalid secret.`|无效密钥|请确保请求参数与`assets/switch/secret.php`中的密钥一致。参见[配置指南](#configGuide)。|
|`Error writing to file.`|写入状态时发生错误|请确保PHP有足够权限修改储存状态的文件。|
|`Bad request.`|请求参数错误|请检查请求方式与参数是否正确，参见[请求示例](#requestEg)。|

### <span id="requestEg">请求示例</span>
以`GET`方式请求`https://sleepy.example.com/switch/?secret=12345678&status=1`，返回`ok`，`status.txt`内容更新为`awake`。

## 扩展：使用Siri发送请求
许多朋友了解到这个项目都是通过毛毛在Bilibili上发布的视频([BV1fE421A7PE](https://www.bilibili.com/video/BV1fE421A7PE))。在视频中，我通过唤醒Siri并说出 *I'm going to sleep.* 和 *I'm up.* 来更新状态。这种更新方式的实现原理是通过让Siri执行对应的快捷指令，在快捷指令中向switch接口发送GET请求。

在`快捷指令`App中新建一个快捷指令，设置一个名称，这个名称就是执行该快捷指令需要对Siri说的内容，例如`晚安`或`早上好`。在快捷指令中加入`获取URL内容`操作，在`URL`输入框中，输入合成后的更新请求URL，例如`https://sleepy.example.com/switch/?secret=12345678&status=0`，参见[更新状态](#updateStatus)。
![示例：更新状态为睡着](https://api.maao.cc/static/sleepy/readme/awake.jpg "示例：更新状态为睡着")![示例：更新状态为醒着](https://api.maao.cc/static/sleepy/readme/sleeping.jpg "示例：更新状态为醒着")