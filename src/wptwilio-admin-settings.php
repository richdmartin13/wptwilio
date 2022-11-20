<head>
    <link rel="stylesheet" href='<?php echo '' . plugin_dir_url(__FILE__) . '/styles/style.css'?>'/>
</head>
<body>
<div style="display: flex; flex-flow: column nowrap; align-items: center; justify-content: center; width: 100%; margin-top: 120px;">
    <div style="display: flex; flex-flow row nowrap; align-items: flex-end; justify-content: space-between; width: 60vw;">
    <?php
        $sender_head = '' . plugin_dir_url( __FILE__ ) . 'images/settings_head.png';
        echo "<img src=$sender_head style='width: 20%;'/>"
    ?>
    <h1 style="font-size: 48px; font-weight: 800;">Settings</h1>
    </div>
    <div class="container settings">
        <div style="margin-top: 40px; width: 100%; border-radius: 10px; background-color: #fff; text-align: center;display: flex; flex-flow: column nowrap; align-items: center; justify-content: center">
        <form method="post" action='options.php' style="color: #fff; display: flex; flex-flow: column nowrap; align-items: center; justify-content: center; padding-top: 40px; padding-bottom: 40px;">
                <?php
                settings_fields($this->pluginName);
                do_settings_sections('wptwilio-admin-settings');
                submit_button();
                ?>
            </form>
        </div>
    </div>
</div>
</body>