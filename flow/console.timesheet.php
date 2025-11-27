<?php

security_check();

if (!isset($_GET['date'])) 
{
    header_redirect('/console/add/date/'.date('Y-m-d'));
}

define('APP_NAME', 'Flow');
define('PAGE_TITLE', 'Timesheet');
define('PAGE_SELECTED_SECTION', 'flow');
define('PAGE_SELECTED_SUB_PAGE', '/console/timesheet');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');


// Query hour entries for selected date
$query = 'SELECT * 
    FROM entries 
    WHERE user_id = "'.$_user['id'].'" 
    AND date = "'.$_GET['date'].'" ORDER BY id DESC';
$result = mysqli_query($connect, $query);

$applications_json = fetch_json('https://applications.brickmmo.com/api/applications/timesheets/true');
$applications = array();
foreach($applications_json['applications'] as $application) {
    $applications[$application['id']] = $application['name'];
}

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/qr.png"
        height="50"
        style="vertical-align: top"
    />
    Flow
</h1>
<p>
    <a href="/console/dashboard">Flow</a> / 
    Timesheet
</p>

<hr>

<h2><?=date('l F j, Y', strtotime($_GET['date']))?></h2>

<?php if(mysqli_num_rows($result) == 0): ?>
    
    <div class="w3-panel w3-light-grey">
        <h3 class="w3-margin-top"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> No Results Found</h3>
        <p>There are no timesheet entries for <span class="w3-bold"><?=date('l F j, Y', strtotime($_GET['date']))?></span>.</p>
    </div>

<?php else: ?>

    <table class="w3-table w3-bordered w3-striped w3-margin-bottom">
        <thead>
            <tr>
                <th>Project</th>
                <th>Hours</th>
                <th>Description</th>
                <th class="bm-table-icon"></th>
                <th class="bm-table-icon"></th>
            </tr>
        </thead>
        <tbody>
            <?php while($record = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?=htmlspecialchars($applications[$record['application_id']])?></td>
                    <td><?=htmlspecialchars($record['hours'])?></td>
                    <td><?=htmlspecialchars($record['description'])?></td>
                    <td>
                        <a href="/console/edit/<?=$record['id'] ?>">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </td>
                    <td>
                        <a href="#" onclick="return confirmModal('Are you sure you want to delete the timesheet entry for <?=$record['date'] ?>?', '/console/timesheet/date/<?=$_GET['date']?>/delete/<?=$record['id'] ?>');">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php endif; ?>

<a
    href="/console/add/date/<?=$_GET['date']?>"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-pen-to-square fa-padding-right"></i> Add Timesheet Entry
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
