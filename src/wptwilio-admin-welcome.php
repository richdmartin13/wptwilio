<head>
<link rel="stylesheet" href='<?php echo '' . plugin_dir_url(__FILE__) . '/styles/style.css'?>'/>
<style>
    select, select option {text-transform: capitalize};
    table, th, td {padding: 10px !important; border: 1px solid rgba(0,0,0,0.2); border-collapse: collapse;};
    tr:nth-child(even) {background-color: #ddd}
</style>
</head>
<body>
<div style="display: flex; flex-flow: column nowrap; align-items: center; justify-content: center; width: 100%; margin-top: 120px;">
    <div style="display: flex; flex-flow row nowrap; align-items: flex-end; justify-content: space-between; width: 60vw;">
    <?php
        $sender_head = '' . plugin_dir_url( __FILE__ ) . 'images/sender_head.png';
        echo "<img src=$sender_head style='width: 20%;'/>"
    ?>
    <h1 style="font-size: 48px; font-weight: 800;">WPTwilio</h1>
    </div>
    <div class="container">
        <div style="margin-top: 40px; width: 100%; border-radius: 10px; background-color: #fff; text-align: start; display: flex; flex-flow: column nowrap; align-items: center; justify-content: center; padding-bottom: 40px;">
            <h1>WPTwilio 0.9.9</h1>

            <h3>Getting Started</h3>
            <p style="max-width: 80%">Before your website can begin accepting messages, you'll need to take a few minutes to set everything up. The Settings menu allows you to edit integration settings, as well as a default response if there isn't a keyword match.</p>
            <p style="max-width: 80%">Make sure that your API details are correct, and that you have the proper messaging service SID. We'll have an in-depth guide on that later.</p>

            <h3>Sending Your First Message</h3>
            <p style="max-width: 80%">Click on "Compose Message" in the top bar to bring up the sender.</p>
            <p style="max-width: 80%">WPTwilio automatically collects incoming phone numbers and places them into the default list. To send a message to your default list, just select "Default" from the dropdown menu in the SMS Console, type your message, and send! If you don't want to schedule your message (more on that below), don't check the "schedule" option.</p>
            <p style="max-width: 80%">You'll immediately notice that your message is visible in the log, with information about which group you sent it to, the timestamp, and more.</p>

            <h3>Scheduling Messages</h3>
            <p style="max-width: 80%;">Scheduling a message is easy, but due to some restrictions, you'll have to play by some rules for now.</p>
            <p style="max-width: 80%;">You can schedule any message by selecting "Later," choosing a date and time, choosing your group, and hitting send. However, you are only able to schedule any messages at a minimum of <strong>fifteen minutes from your current time</strong>, or a maximum of <strong>seven days from your current time</strong>. These are restrictions set forth by Twilio, and a workaround is in progress. WPTwilio will do its best work to prevent you from attempting to send invalid scheduled messages.</p>
            <p style="max-width: 80%;">Messages that have been successfully scheduled will show up in the top table, with information about your message. Please note that at this time, <strong>no messages can be canceled</strong>. If for any reason your message is rejected, it will appear in the message logs below with a "failed" status. Otherwise, the message logs and the scheduling logs will reflect that the message sends at the scheduled time.</p>

            <h3>Getting Groups Together</h3>
            <p style="max-width: 80%">If you want to add more groups, you can do so in the Group Manager. Just add a group name and a join keyword and you're good to go, but note that each can only be up to 20 characters.</p>
            <p style="max-width: 80%">Groups you add will be visible in the SMS console, but you won't be able to send messages just yet. To add participants, users must text the keyword that you set (case sensitive) to your twilio phone number.</p>
            <p style="max-width: 80%">You can create and remove groups as you please, but do note: deleting a group is irreversible, and all associated memberships and messages will be deleted as well.</p>

            <h3>Have Problems?</h3>
            <p style="max-width: 80%;">Get in touch with me and let me know by sending an email to <a href="mailto://richdmartin@me.com">richdmartin@me.com</a> or by texting or calling <a href="tel://18652749603">+1 (865) 274-9603</a>.</p>
        
            <h3>Changelog</h3>
            <div style="background-color: #eee; margin: 10px; padding: 10px; border-radius: 5px; width: 80%">
                <h3>Recent Changes as of 0.9.9</h3>
                <p style="max-width: 80%">Big overhauls to how the sender works:</p>
                <ul>
                    <li>To send a message, you'll now have to click "Compose Message" on the top section of the console.</li>
                    <li>A new modal menu will show, which has been minimized to reduce clutter, except when needed (below).</li>
                    <li>By default, messages will be queued for as soon as you press the send button. To send a message later, you will now have to select "Later" from the drop down and choose your date and time.</li>
                    <li>If you don't want to send a message after all, you can just click "cancel."</li>
                </ul>
                <h3>Recent Changes as of 0.9.8</h3>
                <p style="max-width: 80%">Some more small quality of life fixes:</p>
                <ul>
                    <li>Moved the changelog to the bottom of the welcome screen</li>
                    <li>Stripped slashes from message content that is visible in logs and the schedule</li>
                    <li>Increased the minimum interval to 20 minutes to allow a grace period for preparing a scheduled message.</li>
                </ul>
                <h3>Recent Changes as of 0.9.7</h3>
                <p style="max-width: 80%">Some small quality of life fixes:</p>
                <ul>
                    <li>Added indicators for empty menus, so they don't look like weird blobs on the screen.</li>
                    <li>Fixed some mobile formatting issues, including headers and weird x-scrolling.</li>
                    <li>Removed a shading bug on some menu fields, making them look bigger and confusing the clickable area on desktop versions.</li>
                </ul>
                <h3>Recent Changes as of 0.9.6</h3>
                <p style="max-width: 80%">Minor Bug Fixes</p>
                <h3>Recent Changes as of 0.9.5</h3>
                <p style="max-width: 80%">There have been a few changes that you'll want to pay attention to:</p>
                <ul>
                    <li>WPTwilio is becoming mobile-responsive! Tables and pages now display better and with more readability on mobile devices. Some headers, forms, and other parts need more work.</li>
                    <li>Scheduling an SMS has slightly changed: You now need to check the "schedule" box to send a scheduled message. Otherwise, the message will send immediately.</li>
                    <li>WPTwilio now has general rulesets for scheduling, so your messages are less likely to fail. By default, the scheduling system will show you the minimum 15 minute period, beginning from when you loaded the page.</li>
                    <li>Messages in the message log now have status coloring, so you can more quickly see whether a message sent successfully or not.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>