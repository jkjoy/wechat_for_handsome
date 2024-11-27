<?php
if (file_exists(__DIR__ . '/install.lock')) {
    die("已经安装过了，如要重新安装请删除install.lock");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbname = $_POST['dbname'];
    $app_id = $_POST['app_id'];
    $secret = $_POST['secret'];
    $token = $_POST['token'];
    $aes_key = $_POST['aes_key'];
    $url_dir = $_POST['url_dir'];
    $amapSecret = $_POST['amapSecret'];
    $config = "<?php
\$sqlite_conf = array(
    'db'      => '$dbname', 
    );
try{
\$db=new PDO('sqlite:' . __DIR__ . '/' . \$sqlite_conf['db']);
}catch(PDOException \$e){
    die('数据库连接失败:' . \$e->getMessage());
}
\$amapSecret = $amapSecret;
\$config = [
    'app_id' => '$app_id',
    'secret' => '$secret',
    'token' => '$token',
 	'aes_key' => '$aes_key',
    'response_type' => 'array',
   'log' => [
        'default' => 'prod', // 默认使用的 channel，生产环境可以改为下面的 prod
        'channels' => [
            // 测试环境
            'dev' => [
                'driver' => 'single',
                'path' => __DIR__.'/tmp/wechat.log',
                'level' => 'debug',
            ],
            // 生产环境
            'prod' => [
                'driver' => 'single',
                'path' =>__DIR__.'/tmp/wechat.log',
                'level' => 'info',
            ],
        ],
    ],
];";
    $sql = "DROP TABLE IF EXISTS `cross`;
CREATE TABLE `cross`  (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `openid` TEXT NOT NULL DEFAULT '',
  `url` TEXT NULL DEFAULT NULL,
  `timecode` TEXT NULL DEFAULT NULL,
  `cid` INTEGER NULL DEFAULT NULL,
  `mid` INTEGER NULL DEFAULT NULL,
  `msg_type` TEXT NULL DEFAULT NULL,
  `content` TEXT NULL
);";
    try {
        $db = new PDO("sqlite:" . __DIR__ . "/" . $dbname);
        if ($db->exec($sql)) {
            file_put_contents(__DIR__ . '/config.php', $config);
            file_put_contents(__DIR__ . '/install.lock', '');
            die("1");
        }
    } catch (PDOException $e) {
        die('数据库连接失败:' . $e->getMessage());
    }
    die("参数错误");
}
?>

<html lang="zh-cmn-Hans">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <title>安装</title>
    <link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/2.1.3/weui.css">
    <script src="./zepto.min.js"></script>
</head>

<body ontouchstart="">
    <div class="page">
        <form class="weui-form" id="form">
            <div class="weui-form__text-area">
                <h2 class="weui-form__title">安装</h2>
                <div class="weui-form__desc">
                    wechat_for_handsome
                </div>
            </div>
            <div class="weui-form__control-area">
                <div class="weui-cells__group weui-cells__group_form">

                    <div class="weui-cells weui-cells_form">

                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">数据库文件名</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="dbname" class="weui-input" placeholder="数据库文件名" value="database.sqlite">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">app_id</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="app_id" class="weui-input" placeholder="公众号appid" value="">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">secret</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="secret" class="weui-input" placeholder="公众号secret" value="">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">token</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="token" class="weui-input" placeholder="公众号验证token" value="">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">aes_key</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="aes_key" class="weui-input" placeholder="公众号aes_key" value="">
                            </div>
                        </div>
                        <div class="weui-cell">
                            <div class="weui-cell__hd">
                                <label class="weui-label">高德地图apiKey</label>
                            </div>
                            <div class="weui-cell__bd">
                                <input id="js_input" name="amapSecret" class="weui-input" placeholder="高德地图apiKey" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="weui-form__opr-area">
                <a href="javascript:;" class="weui-btn weui-btn_primary" id="bind">安装</a>
            </div>

            <div class="weui-form__extra-area">
                <div class="weui-footer">
                    <p class="weui-footer__links">
                        <a href="https://www.ifking.cn" class="weui-footer__link">我若为王</a>
                    </p>
                    <p class="weui-footer__text">
                        Copyright © 2022 iLay
                    </p>
                </div>
            </div>
        </form>
        <div class="js_dialog" id="Dialog" style="opacity: 0; display: none;">
            <div class="weui-mask"></div>
            <div class="weui-dialog">
                <div class="weui-dialog__bd" id="msg">
                    提示
                </div>
                <div class="weui-dialog__ft">
                    <a href="javascript:$('#Dialog').fadeOut(200);" class="weui-dialog__btn weui-dialog__btn_primary">确定</a>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        $(function() {
            var $toast = $('#js_toast');
            var $Dialog = $('#Dialog');
            var $msg = $('#msg');
            $('#bind').on('click', function() {
                $('#bind').addClass('weui-btn_loading');
                $.post('<?php echo ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
                        echo $_SERVER["HTTP_HOST"] . dirname($_SERVER['SCRIPT_NAME']); ?>/install.php', $('#form').serialize(), function(response) {
                    if (response == '1') {
                        $msg.html('安装成功');
                    } else {
                        $msg.html(response);
                    }
                    $Dialog.fadeIn(200);
                    $('#bind').removeClass('weui-btn_loading');
                })
            });

            function onBridgeReady() {
                WeixinJSBridge.call('hideOptionMenu');
            }

            if (typeof WeixinJSBridge == "undefined") {
                if (document.addEventListener) {
                    document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                } else if (document.attachEvent) {
                    document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                    document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                }
            } else {
                onBridgeReady();
            }
        });
    </script>
</body>

</html>