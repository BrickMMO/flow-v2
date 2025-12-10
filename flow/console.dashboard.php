<?php

security_check();

define('APP_NAME', 'Flow');
define('PAGE_TITLE', 'Calendar');
define('PAGE_SELECTED_SECTION', 'flow');
define('PAGE_SELECTED_SUB_PAGE', '/console/dashboard');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

// Get month and year from URL or default to current
$month = isset($_GET['month']) && is_numeric($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) && is_numeric($_GET['year']) ? intval($_GET['year']) : date('Y');

// Validate month and year
if ($month < 1 || $month > 12) $month = date('n');
if ($year < 2000 || $year > 2100) $year = date('Y');

// Calculate first and last day of month
$first_day = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day);
$day_of_week = date('w', $first_day); // 0 (Sunday) to 6 (Saturday)

// Calculate previous and next month
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}

$next_month = $month + 1;
$next_year = $year;
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}

// Get events for this month
$month_start = date('Y-m-01 00:00:00', $first_day);
$month_end = date('Y-m-t 23:59:59', $first_day);
$month_name = date('F Y', $first_day);

$query = 'SELECT COUNT(*) AS entry_count, 
    COALESCE(SUM(hours), 0) AS hours_count
    FROM entries
    WHERE user_id = "'.$_user['id'].'"';
$result = mysqli_query($connect, $query);
$record = mysqli_fetch_assoc($result);
$entry_count = $record['entry_count'];
$hours_count = $record['hours_count'];

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/flow.png"
        height="50"
        style="vertical-align: top"
    />
    Flow
</h1>

<hr> 

<p>
    Total Timesheet Entries: <span class="w3-tag w3-blue"><?=$entry_count?></span> | 
    Total Hours: <span class="w3-tag w3-blue"><?=$hours_count?></span> 
</p>

<hr> 

<h2>Calendar View</h2>

<hr>

<!-- Calendar Navigation -->
<div class="w3-bar w3-margin-bottom" style="display: flex; align-items: center;">
    <a href="<?=ENV_DOMAIN?>/console/dashboard/month/<?=$prev_month?>/year/<?=$prev_year?>" class="w3-button w3-white w3-border">
        <i class="fa-solid fa-chevron-left"></i> Previous
    </a>
    
    <div style="flex: 1; text-align: center;">
        <h2 style="margin: 0;"><?=$month_name?></h2>
    </div>
    
    <a href="<?=ENV_DOMAIN?>/console/dashboard/month/<?=$next_month?>/year/<?=$next_year?>" class="w3-button w3-white w3-border">
        Next <i class="fa-solid fa-chevron-right"></i>
    </a>
</div>

<div class="w3-card w3-white">

    <header class="w3-container w3-purple">
        <h2><?=$month_name?></h2>
    </header>
    
    <table class="w3-table" style="table-layout: fixed; min-width: 800px;">
        <thead>
            <tr class="w3-light-grey">
                <th style="width: 14.28%; text-align: center;">Sunday</th>
                <th style="width: 14.28%; text-align: center;">Monday</th>
                <th style="width: 14.28%; text-align: center;">Tuesday</th>
                <th style="width: 14.28%; text-align: center;">Wednesday</th>
                <th style="width: 14.28%; text-align: center;">Thursday</th>
                <th style="width: 14.28%; text-align: center;">Friday</th>
                <th style="width: 14.28%; text-align: center;">Saturday</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $day_counter = 1;
            $calendar_started = false;
            
            // Calendar rows (max 6 weeks)
            for ($week = 0; $week < 6; $week++):
                if ($day_counter > $days_in_month) break;
            ?>
                <tr>
                    <?php for ($dow = 0; $dow < 7; $dow++): ?>
                        <?php
                        $is_today = ($day_counter == date('j') && $month == date('n') && $year == date('Y'));
                        $cell_class = $is_today ? 'w3-light-grey' : '';
                        ?>
                        <td class="<?=$cell_class?>" style="vertical-align: top !important; height: 100px; padding: 5px; border: 1px solid #ddd;">
                            <?php
                            // Check if we should start printing days
                            if ($week == 0 && $dow < $day_of_week) {
                                // Empty cell before month starts
                            } elseif ($day_counter <= $days_in_month) {
                                // Print day number
                                echo '<div style="font-weight: bold; margin-bottom: 3px;">'.$day_counter.'</div>';

                                // Print events for this day
                                $query = 'SELECT COALESCE(SUM(hours), 0) AS hours
                                    FROM entries
                                    WHERE user_id = "'.$_user['id'].'"
                                    AND date = "'.date('Y-m-d', mktime(0, 0, 0, $month, $day_counter, $year)).'"';
                                $result = mysqli_query($connect, $query);

                                $record = mysqli_fetch_assoc($result);

                                echo '<div class="w3-center w3-xxlarge">
                                        <a href="'.ENV_DOMAIN.'/console/timesheet/date/'.date('Y-m-d', mktime(0, 0, 0, $month, $day_counter, $year)).'">
                                            '.$record['hours'].'
                                        </a>
                                    </div>';

                                $day_counter++;
                            } else {
                                // Empty cell after month ends
                                echo '&nbsp;';
                            }
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
    
</div>

<hr>

<a
    href="<?=ENV_DOMAIN?>/console/recent"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-pen-to-square fa-padding-right"></i> View Recent Timesheet Entries
</a>

<a
    href="<?=ENV_DOMAIN?>/console/add"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-pen-to-square fa-padding-right"></i> Add Timesheet Entry
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');