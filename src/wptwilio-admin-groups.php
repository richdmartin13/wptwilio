<head>
    <link rel="stylesheet" href='<?php echo '' . plugin_dir_url(__FILE__) . '/styles/style.css'?>'/>
<style>
    select, select option {text-transform: capitalize};
    table, th, td {padding: 10px !important; border: 1px solid rgba(0,0,0,0.2); border-collapse: collapse;};
    tbody:nth-child(even) {background-color: #ddd}
</style>
<script src="jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="page">
    <div style="display: flex; flex-flow row nowrap; align-items: center; justify-content: space-between; width: 70vw; margin-bottom: -12px; z-index: 1">
        <?php
            $sender_head = '' . plugin_dir_url( __FILE__ ) . 'images/groups_head.png';
            echo "<img src=$sender_head style='width: 20%;'/>"
        ?>
        <h1 style="font-size: 48px; font-weight: 800; margin-bottom: -20px;">Group Manager</h1>
    </div>
    <div class="container">
        <form method="post" name="cleanup_options" action="" style="display: flex; flex-flow: row wrap; align-items: flex-end; justify-content: space-evenly; max-width: 80%">
            <input type="text" name="group_name" placeholder="Group Name" style="border: none; margin: 0 20px; padding: 5px 10px;"/>
            <input type="text" name="keyword" placeholder="Join Keyword" style="border: none; margin: 0 20px; padding: 5px 10px;"/>
            <input class="button-primary" type="submit" value="Add" name="add_group" style="margin: 0 10px; background-color: #fff; border: none; color: #3b98bf"/>
        </form>

        <!-- Group Table Mk1 -->
        <table style="width: 100%; margin-top: 40px; border-radius: 10px; background-color: #fff; padding: 10px; text-align: center;" class="tableContainer">
        <tr style="padding-bottom: 10px;" class="tableHeaderRow">
            <th style="padding: 10px; ">Group ID</th>
            <th style="padding: 10px; ">Group Name</th>
            <th style="padding: 10px; ">Keyword</th>
            <th style="padding: 10px; ">Participants</th>
            <th style="padding: 10px; ">Options</th>
        </tr>
        <tr class="tableHeaderRow">
            <th style="padding-bottom: 10px;"><hr></td>
            <th style="padding-bottom: 10px"><hr></td>
            <th style="padding-bottom: 10px"><hr></td>
            <th style="padding-bottom: 10px"><hr></td>
            <th style="padding-bottom: 10px"><hr></td>
        </tr>
        <?php
        global $wpdb;

        $groups = $wpdb->prefix . "wptwilio_groups";
        $members = $wpdb->prefix . "wptwilio_members";

        $group_list = $wpdb->get_results("SELECT * FROM $groups");
        
        foreach($group_list as $group) {
            $id = $group->group_id;
            $participants = $wpdb->get_col("SELECT user_id, group_id FROM $members WHERE group_id = $id");
            $numpart = count($participants);
            $display = ($group->group_id > 1) ? "inline-block" : "none";
            ?>
            <tr style="text-transform: capitalize;" id=<?php echo $group->group_name?>>
                
            <th class="tableData"><span class="mobileTableHeaders">Group ID</span><span><?php echo $group->group_id?></span></td>
            <td class="tableData"><span class="mobileTableHeaders">Group Name</span><span><?php echo $group->group_name?></span></td>
            <td class="tableData"><span class="mobileTableHeaders" style="text-transform: none;">Keyword</span><span style="text-transform: none;"><?php echo $group->keyword?></span></td>
            <td class="tableData"><span class="mobileTableHeaders">Participants</span><span><?php echo $numpart?></span></td>
            <td class="tableData"><span class="mobileTableHeaders">Options</span>
                <form method="post" name="cleanup_options" action="" style='<?php echo "display: " . $display?>'>
                    <input type="text" name="group_id" value=<?php echo $group->group_id?> style="display: none;">
                    <input class="button-primary" type="submit" value="Delete" name="delete_group" style="margin: 0 10px;"/>
                </form>
                </td>
            </tr>
            <?php 
        };
        ?>
        </table>
        <p style="color: #fff"><strong>Note:</strong> Deleting a group will also delete all associated messages and memberships.</p>
    </div>
</div>

</body>