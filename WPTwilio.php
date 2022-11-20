<?php

/*

Plugin Name: WPTwilio
Description: WPTwilio integrates with Twilio to provide single- and bulk-sms sending with automatic opt-in and opt-out handling.
Version: 0.9.9
Author: Richard Martin

*/

require_once 'vendor/autoload.php'; // Loads the library
use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;

class wptwilio {
    public $pluginName = "WPTwilio";

    //Declare Plugin Pages
    public function displayWelcomePage() {
        include_once "src/wptwilio-admin-welcome.php";
    }

    public function displaySettingsPage() {
        include_once "src/wptwilio-admin-settings.php";
    }

    public function displaySMSPage() {
        include_once "src/wptwilio-admin-sms.php";
    }

    public function displayGroupPage() {
        include_once "src/wptwilio-admin-groups.php";
    }

    // public function displayNoPrivilagesPage() {
    //     include_once "src/wptwilio-no-privilages.php";
    // }

    //Assign Plugin Pages to Menus
    public function addwptwilioAdminOption() { 
        add_menu_page(
            $this->pluginName,
            $this->pluginName,
            "manage_options",
            $this->pluginName,
            [$this, "displayWelcomePage"],
            "dashicons-megaphone"
        );
    }

    public function registerwptwilioSender() {
            add_submenu_page(
                $this->pluginName,
                __("WPTwilio SMS Console", $this->pluginName . "-sms"),
                __("SMS Console", $this->pluginName . "-sms"),
                "manage_options",
                $this->pluginName . "-sms",
                [$this, "displaySMSPage"],
            );
    }

    public function registerwptwilioGroups() {
        add_submenu_page(
            $this->pluginName,
            __("WPTwilio Group Manager", $this->pluginName . "-groups"),
            __("Group Manager", $this->pluginName . "-groups"),
            "manage_options",
            $this->pluginName . "-groups",
            [$this, "displayGroupPage"],
        );
    }

    public function registerwptwilioSettings() {
        add_submenu_page(
            $this->pluginName,
            __("WPTwilio Settings", $this->pluginName . "-settings"),
            __("Settings", $this->pluginName . "-settings"),
            "manage_options",
            $this->pluginName . "-settings",
            [$this, "displaySettingsPage"],
        );
    }

    //Handle changes in Admin Settings
    public function wptwilioAdminSettingsSave() {
        register_setting(
            $this->pluginName,
            $this->pluginName,
            [$this, "pluginOptionsValidate"]
        );
        add_settings_section (
            "wptwilio_main",
            "Integration Settings",
            [$this, "wptwilioSectionText"],
            "wptwilio-admin-settings"
        );
        add_settings_field(
            "api_sid",
            "API SID",
            [$this, "wptwilioSettingSID"],
            "wptwilio-admin-settings",
            "wptwilio_main"
        );
        add_settings_field(
            "api_mssid",
            "Messaging Service SID",
            [$this, "wptwilioSettingMSSID"],
            "wptwilio-admin-settings",
            "wptwilio_main"
        );
        add_settings_field(
            "api_token",
            "API Auth Token",
            [$this, "wptwilioSettingToken"],
            "wptwilio-admin-settings",
            "wptwilio_main"
        );
        add_settings_section (
            "wptwilio_messaging",
            "Messaging Settings",
            [$this, "wptwilioSectionText"],
            "wptwilio-admin-settings"
        );
        add_settings_field(
            "default_response",
            "Default Response",
            [$this, "wptwilioSettingDefaultResponse"],
            "wptwilio-admin-settings",
            "wptwilio_messaging"
        );
        // add_settings_section (
        //     "wptwilio_management",
        //     "Management Settings",
        //     [$this, "wptwilioSectionText"],
        //     "wptwilio-admin-settings"
        // );
        // add_settings_field(
        //     "privilages",
        //     "Minimum Role Required",
        //     [$this, "wptwilioSettingPrivilages"],
        //     "wptwilio-admin-settings",
        //     "wptwilio_management"
        // );
        // add_settings_field(
        //     "unsafe_delete",
        //     "Enable Unsafe Group Deletion",
        //     [$this, "wptwilioSettingUnsafeDeletion"],
        //     "wptwilio-admin-settings",
        //     "wptwilio_management"
        // );
    }

    //Render Input Fields
    public function wptwilioSectionText() {
        echo '';
    }

    public function wptwilioSettingSID() {
        $options = get_option($this->pluginName);
        echo "
            <input
                id='$this->pluginName[api_sid]'
                name='$this->pluginName[api_sid]'
                size='40'
                type='text'
                value='{$options['api_sid']}'
                placeholder='Enter your API SID here'
                style='border:none; background-color: #eee'
            />";
    }

    public function wptwilioSettingToken() {
        $options = get_option($this->pluginName);
        echo "
            <input
                id='$this->pluginName[api_token]'
                name='$this->pluginName[api_token]'
                size='40'
                type='password'
                value='{$options['api_token']}'
                placeholder='Enter your API Auth Token here'
                style='border:none; background-color: #eee'
            />";
    }

    public function wptwilioSettingMSSID() {
        $options = get_option($this->pluginName);
        echo "
            <input
                id='$this->pluginName[api_mssid]'
                name='$this->pluginName[api_mssid]'
                size='40'
                type='text'
                value='{$options['api_mssid']}'
                placeholder='Enter your Messaging Service SID here'
                style='border:none; background-color: #eee'
            />";
    }

    public function wptwilioSettingDefaultResponse() {
        $options = get_option($this->pluginName);
        $dr = $options['default_response'];
        echo "
            <textarea
                id='$this->pluginName[default_response]'
                name='$this->pluginName[default_response]'
                cols='39'
                rows='4'
                value={$options['default_response']}'
                placeholder='Enter a default messaging response here'
                style='border:none; background-color: #eee'
                >$dr</textarea>";
    }

    // public function wptwilioSettingPrivilages() {
    //     global $wp_roles;
    //     $role_names = $wp_roles->get_names();

    //     $options = get_option($this->pluginName);
    //     $privilages = $options['privilages'];

    //     echo "<select 
    //             name='$this->pluginName[privilages]'>
    //             id='$this->pluginName[privilages]'
    //             class='privSelector'
    //             ";
    //     foreach($role_names as $role) {
    //         $select = $role == $privilages ? 'selected' : '';
    //         echo "<option value=$role $select>$role</option>";
    //     };
    //     echo "</select>";
    // }

    // public function wptwilioSettingUnsafeDeletion() {
    //     $options = get_option($this->pluginName);
    //     $unsafe_delete = $options['unsafe_delete'];
    //     $checked = $unsafe_delete;
    //     echo "
    //     <input type='checkbox' name='$this->pluginName[unsafe_delete]' value='1' " . $checked . " />
    //     ";
    // }

    //Sanitize all input fields
    public function pluginOptionsValidate($input) {
        $newinput["api_sid"] = trim($input["api_sid"]);
        $newinput["api_mssid"] = trim($input["api_mssid"]);
        $newinput["api_token"] = trim($input["api_token"]);
        $newinput["default_response"] = trim($input["default_response"]);
        $newinput["privilages"] = trim($input["privilages"]);
        return $newinput;
    }
    

}

//Establish Endpoint Triggers

//Receive SMS
function receive_sms() {
    $number = $_POST['From'];
    $body = $_POST['Body'];
    global $wpdb;
    $table_name = $wpdb->prefix . "wptwilio";

    function insertUser( $num, $body ) {
        global $wpdb;
        
        $users = $wpdb->prefix . "wptwilio_users";
        $members = $wpdb->prefix . "wptwilio_members";
        $wpdb->insert($users, array('phone' => $num, 'date_created' => date('y-m-d h:i:s')));

        $current_user_id = $wpdb->get_col("SELECT user_id FROM $users WHERE phone = $num")[0];
        $wpdb->insert($members, array('user_id' => $current_user_id, 'group_id' => 1));

    }

    function registerGroup($num, $msg) {
        global $wpdb;
        $users = $wpdb->prefix . "wptwilio_users";
        $members = $wpdb->prefix . "wptwilio_members";
        $groups = $wpdb->prefix . "wptwilio_groups";

        $current_user_id = $wpdb->get_col("SELECT user_id FROM $users WHERE phone = $num")[0];
        $group_id = $wpdb->get_col("SELECT group_id FROM $groups WHERE keyword = '$msg'")[0];

        if($wpdb->insert($members, array("user_id" => $current_user_id, "group_id" => $group_id))) {
            return "You signed up for the $msg messaging list!";
        } else if($msg == 'help') {
            $group_list = $wpdb->get_col("SELECT group_name FROM $groups");
            $response = "You can join a group by texting in with its keyword. Here's a list of the available groups:\n";
            foreach($group_list as $gr) {
                $response = $response . '  - ' . $gr . "\n";
            }
            return $response;
        } else {
            $api_details = get_option("WPTwilio");
            if (is_array($api_details) and count($api_details) != 0) {
                $DEFAULT_RESPONSE = $api_details["default_response"];
            }
            return $DEFAULT_RESPONSE;
        };
    };

    insertUser($number, $body);
    $msg_response = registerGroup($number, $body);

    //Insert into Log
    $logs = $wpdb->prefix . "wptwilio_logs";
    $wpdb->insert($logs, array( "group_id" => 1, "message_content" => $body, "message_type" => "incoming", "message_status" => "received", "message_from" => $number, "date_sent" => date('y-m-d h:i:s')));
    $wpdb->insert($logs, array( "group_id" => 1, "message_content" => $msg_response, "message_type" => "response", "message_status" => "sent", "date_sent" => date('y-m-d h:i:s')));

    echo header('content-type: text/xml');

        echo <<<RESPOND
        <?xml version="1.0" encoding="UTF-8"?>
        <Response>
            <Message>
                $msg_response
            </Message>
        </Response>
        RESPOND;
        die();

}

//Add Group
function add_group() {
    global $wpdb;
    $groups = $wpdb->prefix . "wptwilio_groups";

    if(!isset($_POST["add_group"])) {
        return;
    }

    $name = (isset($_POST["group_name"])) ? $_POST["group_name"] : "";
    $keyword = (isset($_POST["keyword"])) ? $_POST["keyword"] : "";

    $wpdb->insert($groups, array("group_name" => $name, "keyword" => $keyword));
}

//Delete Group
function delete_group() {
    if (!isset($_POST["delete_group"])) {
        return;
    }

    global $wpdb;
    $groups = $wpdb->prefix . "wptwilio_groups";
    $members = $wpdb->prefix . "wptwilio_members";
    $logs = $wpdb->prefix . "wptwilio_logs";

    $group_id = (isset($_POST["group_id"])) ? $_POST["group_id"] : "";

    $wpdb->delete($logs, array('group_id' => $group_id));
    $wpdb->delete($members, array('group_id' => $group_id));
    $wpdb->delete($groups, array('group_id' => $group_id));
}

//Send Scheduled SMS
function send_scheduled_sms() {
    global $wpdb;
    
    $wptwilio_users = $wpdb->prefix . "wptwilio_users";
    $wptwilio_groups = $wpdb->prefix . "wptwilio_groups";
    $wptwilio_members = $wpdb->prefix . "wptwilio_members";
    $wptwilio_schedule = $wpdb->prefix . "wptwilio_schedule";
    $wptwilio_logs = $wpdb->prefix . "wptwilio_logs";

    $now = new DateTime("now", new DateTimeZone('America/New_York'));
    $schedule = $wpdb->get_results("SELECT * FROM $wptwilio_schedule WHERE message_status = 'pending' ORDER BY send_date");

    // if(strtotime($schedule[0]->send_date) <= strtotime($now)) {
        foreach($schedule as $msg) {
            if(strtotime($msg->send_date) <= strtotime($now->format('y-m-d h:i:s'))) {
                
                //Insert into Log
                $logs = $wpdb->prefix . "wptwilio_logs";
                $wpdb->insert($logs, array("group_id" => $msg->group_id, "message_content" => $msg->message_content, "message_type" => "scheduled", "message_status" => "sent", "date_sent" => $msg->send_date, "message_from" => $msg->send_to));
                $wpdb->delete($wptwilio_schedule, array("schedule_id" => $msg->schedule_id));

            }
        }       
    // }
    
}

//Delete Scheduled SMS
function delete_scheduled_sms() {
    if (!isset($_POST["delete_scheduled_sms"])) {
        return;
    }

    global $wpdb;
    $schedule = $wpdb->prefix . "wptwilio_schedule";

    $schedule_id = (isset($_POST["schedule_id"])) ? $_POST["schedule_id"] : "";

    $wpdb->delete($schedule, array('schedule_id' => $schedule_id));
}

//Send SMS
function send_sms() {

    if (!isset($_POST["send_sms_message"])) {
        return;
    }

    global $wpdb;
    $wptwilio_users = $wpdb->prefix . "wptwilio_users";
    $wptwilio_groups = $wpdb->prefix . "wptwilio_groups";
    $wptwilio_members = $wpdb->prefix . "wptwilio_members";
    $wptwilio_schedule = $wpdb->prefix . "wptwilio_schedule";
    $wptwilio_logs = $wpdb->prefix . "wptwilio_logs";

    $uid = $wptwilio_users . ".user_id";
    $gid = $wptwilio_groups . ".group_id";
    $muid = $wptwilio_members . ".user_id";
    $mgid = $wptwilio_members . ".group_id";
    $sgid = $wptwilio_schedule . ".group_id";

    $to        = (isset($_POST["numbers"])) ? $_POST["numbers"] : "";
    $sender_id = (isset($_POST["sender"]))  ? $_POST["sender"]  : "";
    $message   = (isset($_POST["message"])) ? $_POST["message"] : "";
    $date = (isset($_POST["date"])) ? $_POST["date"] : "";
    $time = (isset($_POST["time"])) ? $_POST["time"] : "";
    $doSchedule = (isset($_POST["doSchedule"])) ? $_POST["doSchedule"] : "";

    $msg_group = strtoLower((isset($_POST["msg_group"])) ? $_POST["msg_group"] : "");
    $dateTime = substr($date, 2) . " " . $time;
    $sendAt = new DateTime($datetime, new DateTimeZone('America/New_York'));
    $logTime = new DateTime($datetime, new DateTimeZone('America/New_York'));
    $sendAt->setTimeZone(new DateTimeZone('UTC'));

    $setAtDate = $sendAt->format('y-m-d');
    $setAtTime = $sendAt->format('h:i:s');
    $timeframe = $setAtDate . "T" . $setAtTime . "Z";

    $group_id = $wpdb->get_col("SELECT group_id FROM $wptwilio_groups WHERE group_name = '$msg_group'")[0];

    //gets our api details from the database.
    $api_details = get_option("WPTwilio");
    if (is_array($api_details) and count($api_details) != 0) {
        $TWILIO_SID = $api_details["api_sid"];
        $TWILIO_TOKEN = $api_details["api_token"];
        $TWILIO_MSSID = $api_details["api_mssid"];
    }

    $TWILIO = new Client($TWILIO_SID, $TWILIO_TOKEN);

    if(strtolower($doSchedule) == "later") {
        $to_arr = $wpdb->get_col("
            SELECT phone from $wptwilio_users
            INNER JOIN $wptwilio_members
            ON $uid = $muid
            INNER JOIN $wptwilio_groups
            ON $mgid = $gid
            WHERE $mgid = $group_id;
        ");

        $dt = new DateTime($dateTime, new DateTimeZone('America/New_York'));
        $dateTimeEST = $dt->format('y-m-d h:i:s');
        $dateTimeISO = $dt->format(DateTime::ATOM);

        //Schedule Messages
        foreach($to_arr as $phonum) {
            $success = false;
            try {
                $msg = $TWILIO->messages->create(
                    $phonum, [
                               "messagingServiceSid" => $TWILIO_MSSID,
                               "body" => $message,
                               "sendAt" => $dateTimeISO,
                               "scheduleType" => "fixed"
                            //    "statusCallback" => "https://webhook.site/xxxxx"
                           ]
                );
                $wpdb->insert($wptwilio_schedule, array("group_id" => $group_id, "message_content" => $message, "send_date" => date($dateTimeEST), "message_status" => "pending", "send_to" => $phonum));
                $success = true;
                print($msg->TWILIO_SID);
            } catch (Exception $e) {
                if(!$success){
                    $wpdb->insert($wptwilio_logs, array("group_id" => $group_id, "message_content" => $message, "message_type" => "scheduled", "message_status" => "failed", "message_from" => $phonum, "date_sent" => date($dateTimeEST)));
                } else {
                    
                }
            }
        }
    } else {
        $dt = new DateTime("now", new DateTimeZone('America/New_York'));
        $dateTimeEST = $dt->format('y-m-d h:i:s');
        $to_arr = $wpdb->get_col("
            SELECT phone FROM $wptwilio_users 
            INNER JOIN $wptwilio_members
            ON $uid = $muid
            INNER JOIN $wptwilio_groups
            ON $mgid = $gid
            WHERE $mgid = $group_id;
            ");

        // Send Messages
        foreach ($to_arr as $phonum) {
            try {
                $msg = $TWILIO->messages->create(
                $phonum, [
                    "body" => $message,
                    "messagingServiceSid" => $TWILIO_MSSID
                    ]
                );
            //Insert into Log
            $wpdb->insert($wptwilio_logs, array("group_id" => $group_id, "message_content" => $message, "message_type" => "outgoing", "message_status" => "sent", "message_from" => $phonum, "date_sent" => $dateTimeEST));
                print($msg->TWILIO_SID);
            } catch (Exception $e) {

            }
        }
    }

}

function createTables() {
    global $wpdb;

    //User Table
    /* Includes:

    * USER_ID
    * PHONE
      DATE_CREATED

    */
    $wpt_users = $wpdb->prefix . "wptwilio_users";
    $charset_collate = $wpdb->get_charset_collate();
    $createUsers = "CREATE TABLE IF NOT EXISTS $wpt_users (
            user_id mediumInt(9) AUTO_INCREMENT NOT NULL,
            phone VARCHAR(20) NOT NULL,
            date_created DATETIME NOT NULL,
            PRIMARY KEY  (user_id, phone),
            UNIQUE KEY phone (phone)
            ) $charset_collate;";

    //Groups Table
    /* Includes:

    * GROUP_ID
      GROUP_NAME
      KEYWORD

    */
    $wpt_groups = $wpdb->prefix . "wptwilio_groups";
    $createGroups = "CREATE TABLE IF NOT EXISTS $wpt_groups (
        group_id mediumInt(9) AUTO_INCREMENT NOT NULL,
        group_name VARCHAR(20) NOT NULL,
        keyword VARCHAR(20) NOT NULL,
        PRIMARY KEY  (group_id),
        UNIQUE KEY keyword (keyword)
        ) $charset_collate;";

    //Membership Table
    /* Includes:
    
    *+ USER_ID
    *+ GROUP_ID

    */
    $wpt_members = $wpdb->prefix . "wptwilio_members";
    $createMembers = "CREATE TABLE IF NOT EXISTS $wpt_members (
        user_id mediumInt(9) NOT NULL,
        group_id mediumInt(9) NOT NULL,
        PRIMARY KEY  (user_id, group_id),
        CONSTRAINT FOREIGN KEY (user_id) REFERENCES $wpt_users (user_id),
        CONSTRAINT FOREIGN KEY (group_id) REFERENCES $wpt_groups (group_id)
        ) $charset_collate;";

    //Logs Table
    /* Includes:

    * MESSAGE_ID
      GROUP ID
      MESSAGE_CONTENT
      MESSAGE_TYPE
      MESSAGE_STATUS
      MESSAGE_FROM
      DATE_SENT

    */
    $wpt_logs = $wpdb->prefix . "wptwilio_logs";
    $createLogs = "CREATE TABLE IF NOT EXISTS $wpt_logs (
        message_id mediumInt(9) AUTO_INCREMENT NOT NULL,
        group_id mediumInt(9),
        message_content VARCHAR(255) NOT NULL,
        message_status VARCHAR(10),
        message_type VARCHAR(20),
        message_from VARCHAR(20),
        date_sent DATETIME NOT NULL,
        PRIMARY KEY  (message_id, group_id),
        CONSTRAINT FOREIGN KEY (group_id) REFERENCES $wpt_groups (group_id)
        ) $charset_collate;";

    //Scheduled Sender Table
    $wpt_schedule = $wpdb->prefix . "wptwilio_schedule";
    $createSchedule = "CREATE TABLE IF NOT EXISTS $wpt_schedule (
        schedule_id mediumInt(9) AUTO_INCREMENT NOT NULL,
        group_id mediumInt(9) NOT NULL,
        message_content VARCHAR(255) NOT NULL,
        message_status VARCHAR(10),
        send_to VARCHAR(20),
        send_date DATETIME NOT NULL,
        PRIMARY KEY  (schedule_id, group_id),
        CONSTRAINT FOREIGN KEY (group_id) REFERENCES $wpt_groups (group_id)
        ) $charset_collate;";

    require_once( ABSPATH .'wp-admin/includes/upgrade.php' );    
    dbDelta( $createUsers );
    dbDelta( $createGroups );
    dbDelta( $createMembers );
    dbDelta( $createLogs );
    dbDelta( $createSchedule );

    $wpdb->insert($wpt_groups, array("group_id" => 1, "group_name" => "default", "keyword" => "join"));
}

function wptwilio_activate() {
    createTables();
}

function wptwilio_deactivate() {
    
}

//Plugin initialization
$wptwilioInstance = new wptwilio();

//Add Welcome Page
add_action("admin_menu", [$wptwilioInstance, "addwptwilioAdminOption"]);
//Add Sender
add_action('admin_menu', [$wptwilioInstance, 'registerwptwilioSender']);
//Add Group Page
add_action('admin_menu', [$wptwilioInstance, 'registerwptwilioGroups']);
//Add Settings
add_action("admin_menu", [$wptwilioInstance, "registerwptwilioSettings"]);
//Save and Update Settings
add_action("admin_init", [$wptwilioInstance, 'wptwilioAdminSettingsSave']);

//Bind send function
add_action( 'admin_init', "send_sms" );
//Bind add group function
add_action( 'admin_init', "add_group");
//Bind delete group function
add_action( 'admin_init', "delete_group");
add_action( 'admin_init', "send_scheduled_sms");
add_action( 'admin_init', "delete_scheduled_sms");
//Bind scheduled sender
// add_filter('cron_schedules', 'my_cron_schedules');
// $args = array(false);
// function schedule_my_cron(){
//     wp_schedule_event(time(), '1min', 'send_scheduled_sms', $args);
// }
// if(!wp_next_scheduled('send_scheduled_sms',$args)){
//     add_action('init', 'schedule_my_cron');
// }

//register essentials on plugin activation
register_activation_hook(__FILE__, 'wptwilio_activate');

register_deactivation_hook(__FILE__, 'wptwilio_deactivate');

//Initialize the REST API endpoint for incoming messages
add_action( 'rest_api_init', function () {
    register_rest_route( 'wptwilio/v1', '/sms', array(
        'methods' => 'POST',
        'callback' => 'receive_sms',
    ));
});
