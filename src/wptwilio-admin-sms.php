<head>
    <link rel="stylesheet" href='<?php echo '' . plugin_dir_url(__FILE__) . '/styles/style.css'?>'/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<style>
    select, select option {text-transform: capitalize};
    table, th, td {padding: 10px !important; border: 1px solid rgba(0,0,0,0.2); border-collapse: collapse;};
</style>
</head>
<body>
<div class="page">
    <div style="display: flex; flex-flow row nowrap; align-items: flex-end; justify-content: space-between; width: 70vw;">
        <?php
            global $wpdb;
            $sender_head = '' . plugin_dir_url( __FILE__ ) . 'images/sender_head.png';
            echo "<img src=$sender_head style='width: 20%;'/>"
        ?>
        <h1 style="font-size: 48px; font-weight: 800;">SMS Console</h1>
    </div>
    <div class="container">

    <!-- <button id="compose">Compose Message</button> -->
    <span id="compose">Compose a Message</span>

    <div style="margin-top: 40px; width: 100%; border-radius: 10px; background-color: #fff; text-align: start; display: flex; flex-flow: column nowrap; align-items: center; justify-content: center; padding-bottom: 40px;">
            <div style="display: flex; flex-flow: row nowrap; align-items: flex-end; justify-content: space-evenly; width: 100%; margin-top: 20px;">
                <?php
                    global $wpdb;
                    $logs = $wpdb->prefix . "wptwilio_logs";
                    $schedule = $wpdb->prefix . "wptwilio_schedule";

                    $sent = $wpdb->get_col("SELECT message_id FROM $logs WHERE message_status = 'Sent'");
                    $scheduled = $wpdb->get_col("SELECT schedule_id FROM $schedule WHERE message_status = 'Pending'");
                    $received = $wpdb->get_col("SELECT message_id FROM $logs WHERE message_status = 'Received'");
                    $failed = $wpdb->get_col("SELECT message_id FROM $logs WHERE message_status = 'Failed'");
                ?>

                <span style="display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end;">
                    <span style="width: 50px; height: <?php echo count($sent) * 10?>px; background-color: #1FCD0C; margin: 10px; display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end; border-radius: 4px; color: #fff; max-height: 200px;"><?php echo count($sent)?></span>
                    <p style="margin: 0;">Sent</p>
                </span>

                <span style="display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end;">
                    <span style="width: 50px; height: <?php echo count($scheduled) * 10?>px; background-color: #212121; margin: 10px; display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end; border-radius: 4px; color: #fff; max-height: 200px;"><?php echo count($scheduled)?></span>
                    <p style="margin: 0;">Scheduled</p>
                </span>
                
                <span style="display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end;">
                    <span style="width: 50px; height: <?php echo count($received) * 10?>px; background-color: #212121; margin: 10px; display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end; border-radius: 4px; color: #fff; max-height: 200px;"><?php echo count($received)?></span>
                    <p style="margin: 0;">Received</p>
                </span>
                
                <span style="display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end;">
                    <span style="width: 50px; height: <?php echo count($failed) * 10?>px; background-color: #FF0000; margin: 10px; display: flex; flex-flow: column nowrap; align-items: center; justify-content: flex-end; border-radius: 4px; color: #fff; max-height: 200px;"><?php echo count($failed)?></span>
                    <p style="margin: 0;">Failed</p>
                </span>
                
            </div>
        </div>
    
    <p style="color: #fff"><strong>Note:</strong> All times are in America/New York Time (EST).</p>
    </div>

    <div style="display: flex; flex-flow row nowrap; align-items: flex-end; justify-content: space-between; width: 70vw; margin-top: 40px;">
        <br>
        <h1 style="font-size: 48px; font-weight: 800;">Scheduled</h1>
    </div>
    <div class="container">
        <!-- Start messaging form -->
    
        <!-- End messaging form -->

        <?php 
            $schedule = $wpdb->prefix . "wptwilio_schedule";
            $schedule_logs = $wpdb->get_results("SELECT * FROM $schedule ORDER BY send_date ASC");

            if($schedule_logs) {
        ?>
        <table style="width: 100%; margin-top: 40px; border-radius: 10px; background-color: #fff; padding: 10px; text-align: center" class="tableContainer">
        <tr style="padding-bottom: 10px;" class="tableHeaderRow">
                <th style="padding: 10px; max-width: 64px">Message ID</th>
                <th style="padding: 10px; ">Group Name</th>
                <th style="padding: 10px; ">Content</th>
                <th style="padding: 10px; ">Status</th>
                <th style="padding: 10px; ">To</th>
                <th style="padding: 10px; ">Scheduled</th>
                <!-- <th style="padding: 10px; ">Options</th> -->
            </tr>
            <tr class="tableHeaderRow">
                <th style="padding-bottom: 10px; max-width: 64px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <!-- <th style="padding-bottom: 10px"><hr></td> -->
            </tr>
            <?php
            global $wpdb;
            $groups = $wpdb->prefix . "wptwilio_groups";
            
            foreach($schedule_logs as $msg) { 
                $group_name = $wpdb->get_col("SELECT group_name FROM $groups WHERE group_id = $msg->group_id")[0];
                ?>
                <tr style="text-transform: capitalize;">
                    <th class="tableData"><span class="mobileTableHeaders">Message ID</span><span><?php echo $msg->schedule_id?></span></th>
                    <td class="tableData"><span class="mobileTableHeaders">Group Name</span><span><?php echo $group_name ?></span></td>
                    <td class="tableData messageContent"><span class="mobileTableHeaders">Content</span><span class="messageContent"><?php echo stripslashes($msg->message_content) ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">Status</span><span><?php echo $msg->message_status ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">To</span><span><?php echo $msg->send_to ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">Scheduled</span><span><?php echo $msg->send_date ?></span></td>
                    <!-- <td style="padding: 10px;"> -->
                    <!-- <form method="post" name="cleanup_options" action="">
                        <input type="text" name="schedule_id" value=<?php //echo $msg->schedule_id?> style="display: none;">
                        <input class="button-primary" type="submit" value="Delete" name="delete_scheduled_sms" style="margin: 0 10px;"/>
                    </form> -->
                    <!-- </td> -->
                </tr> 
                <?php
            };
            } else {
                ?>

                <div style="width: 100%; margin-top: 40px; border-radius: 10px; background-color: #fff; text-align: center" class="tableContainer">
                    <div style="margin: 50px;">
                    <?php
                        $no_messages = '' . plugin_dir_url( __FILE__ ) . 'images/empty.png';
                        echo "<img src=$no_messages class='noMessage'/>"
                    ?>
                    <h2>No Scheduled Messages</h2>
                    <p>Schedule a message to see something here.</p>
                    </div>
                </div>

                <?php
            }
        ?>
        </table>
    </div>

    <div style="display: flex; flex-flow row nowrap; align-items: flex-end; justify-content: space-between; width: 70vw; margin-top: 40px;">
        <br>
        <h1 style="font-size: 48px; font-weight: 800;">Message Logs</h1>
    </div>
    <div class="container">
    <?php 
        $logs = $wpdb->prefix . "wptwilio_logs";
        $msg_logs = $wpdb->get_results("SELECT * FROM $logs ORDER BY message_id DESC");

        if($msg_logs) {
    ?>
    <table style="width: 100%; border-radius: 10px; background-color: #fff; padding: 10px; text-align: center; box-shadow: 0px 6px 12px rgba(0,0,0,0.1)" class="tableContainer">
        <tr style="padding-bottom: 10px;" class="tableHeaderRow">
                <th style="padding: 10px; max-width: 64px">Message ID</th>
                <th style="padding: 10px; ">Group Name</th>
                <th style="padding: 10px; ">Content</th>
                <th style="padding: 10px; ">Type</th>
                <th style="padding: 10px; ">Status</th>
                <th style="padding: 10px; ">To/From</th>
                <th style="padding: 10px; ">Date</th>
            </tr>
            <tr class="tableHeaderRow">
                <th style="padding-bottom: 10px; max-width: 64px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
                <th style="padding-bottom: 10px"><hr></td>
            </tr>
            <?php
            global $wpdb;
            $groups = $wpdb->prefix . "wptwilio_groups";
            
            foreach($msg_logs as $msg) { 
                $group_name = $wpdb->get_col("SELECT group_name FROM $groups WHERE group_id = $msg->group_id")[0];
                ?>
                <tr style="text-transform: capitalize;">
                    <th class="tableData"><span class="mobileTableHeaders">Message ID</span><span><?php echo $msg->message_id ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">Group Name</span><span><?php echo $group_name ?></span></td>
                    <td class="tableData messageContent"><span class="mobileTableHeaders">Content</span><span class="messageContent"><?php echo stripslashes($msg->message_content) ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">Type</span><span><?php echo $msg->message_type ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">Status</span><span style="color: <?php 
                     $color;

                     switch($msg->message_status) {
                        case 'failed':
                            $color = '#FF0000';
                            break;
                        case 'sent':
                            $color = '#1FCD0C';
                            break;
                        default:
                            $color = '#000';
                     }
                     echo $color;
                     ?>"><?php echo $msg->message_status ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">To/From</span><span><?php echo $msg->message_from ?></span></td>
                    <td class="tableData"><span class="mobileTableHeaders">Date</span><span><?php echo $msg->date_sent ?></span></td>
                </tr> 
                <?php
            };
        } else {
            ?>

            <div style="width: 100%; margin-top: 40px; border-radius: 10px; background-color: #fff; text-align: center" class="tableContainer">
                <div style="margin: 50px;">
                <?php
                    $no_messages = '' . plugin_dir_url( __FILE__ ) . 'images/empty.png';
                    echo "<img src=$no_messages class='noMessage'/>"
                ?>
                <h2>Nothing Here Yet</h2>
                <p>Send or receive a message to see something here.</p>
                </div>
            </div>

            <?php
        }
        ?>
    </table>
        </div>

</div>

<div id="sendModal" class="sendModal smHidden">
        <div class="wptcard" id="sms_sender">
        <!-- <button id="closeSM">x</button> -->
            <!-- Start messaging form -->
    <form method="post" name="cleanup_options" action="" style="display: flex; flex-flow: row wrap; align-items: flex-end; justify-content: space-evenly;">
            
            <textarea name="message" id="messageBox" rows=3 placeholder="Message" class="msgContainer2" maxLength=512></textarea>
                
                <div class="msgAttributes">
                    <div class="attributeGroup">
                        <input type="date" name="date" id="date" min='<?php 
                            $est_date = new DateTime("now", new DateTimeZone('America/New_York'));
                            echo '20' . $est_date->format('y-m-d');
                        ?>' value='<?php 
                        echo '20' . $est_date->format('y-m-d');
                        ?>' 
                        max='<?php 
                            $week = new DateTime("now", new DateTimeZone('America/New_York'));
                            $week->add(new DateInterval('P' . '7' . 'D'));
                            echo '20' . $week->format('y-m-d');
                        ?>' 
                        />
                        <input type="time" name="time" id="time" value='<?php 
                            $fifteenMin = $est_date->add(new DateInterval('PT' . '20' . 'M'));
                            echo $fifteenMin->format("h:i");
                        ?>' min='<?php echo $est_date->format('h:i')?>'/>
                    </div>
    
                    <div class="attributeGroup">
                        <select name="msg_group" style="margin: 10px 5px; margin-bottom: 0;" required>
                            <option value="" disabled selected>Select a Group</option>
                            <?php
                                global $wpdb;
                                $groups = $wpdb->prefix . "wptwilio_groups";
                                $groupList = $wpdb->get_col("SELECT group_name FROM $groups");
                                foreach($groupList as $g) {
                                    echo "<option value='$g'>$g</option>";
                                };
                            ?>
                        </select>
                        <select name="doSchedule" id="doSchedule" style="margin: 10px 5px; margin-bottom: 0;">
                            <option value="now">Now</option>
                            <option value="later">Later</option>
                        </select>
                    </div>

                    <div class="attributeGroup">
                        <input class="submitButton" type="submit" value="Send" name="send_sms_message" style="margin: 10px 5px; margin-bottom: 0; background-color: #eee; color: #212121; border: none;"/>
                        <input class="submitButton" id="closeSM" type="submit" value="Cancel" style="margin: 10px 5px; margin-bottom: 0; background-color: #eee; color: #212121; border: none;"></input>
                    </div>
    
                </div>
            </form>
            <!-- End messaging form -->
        </div>
</div>

<script>
    $('#compose').click(function() {
        $('#sendModal').removeClass('smHidden');
        $('#sendModal').addClass('smVisible');
        // $('#messageBox').focus();
        $('#compose').prop('disabled', true);
        // $('#sms_sender').scrollTop = $('#sms_sender').scrollHeight;
    });

    $('#closeSM').click(function() {
        $('#sendModal').removeClass('smVisible');
        $('#sendModal').addClass('smHidden');
        $('#compose').prop('disabled', false);
        // window.scrollTo(0,0);
    })


    $('#doSchedule').change(function() {
        checker();
    })

    var checker = (function checker1() {
        if($('#doSchedule option:selected').text() == 'Now') {
            $('#date').prop('disabled', true);
            $('#time').prop('disabled', true);
            $('#date').prop('class', "scheduleHidden");
            $('#time').prop('class', "scheduleHidden");
        } else {
            $('#date').prop('disabled', false);
            $('#time').prop('disabled', false);
            $('#date').prop('class', "scheduleVisible");
            $('#time').prop('class', "scheduleVisible");
        }

        return checker1;
    })();
</script>

</body>
