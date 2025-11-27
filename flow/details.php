<?php

define('APP_NAME', 'Flow');
define('PAGE_TITLE', 'Contribution Details');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$query = 'SELECT *
    FROM entries
    WHERE user_id = "'.addslashes($_GET['key']).'"
    LIMIT 10';
$result = mysqli_query($connect, $query);

$applications_json = fetch_json('https://applications.brickmmo.com/api/applications/timesheets/true');
$applications = array();
foreach($applications_json['applications'] as $application) {
    $applications[$application['id']] = $application;
}

$user = fetch_json('https://sso.brickmmo.com/api/user/'.$_GET['key']);
$user = $user['user'];

?>

<div class="w3-center">
    <h1>
        <i class="fa-brands fa-github"></i>
        <?=$user['github_username']?>
    </h1>
</div>

<hr>

<div>

    <img src="<?=$user['avatar']?>" alt="" class="w3-margin-bottom" style="width:150px">
    <p>Name: <span class="w3-bold"><?=$user['first']?> <?=$user['last']?></span></p>
    <p>
        GitHub URL:
        <a href="https://github.com/<?=$user['github_username']?>">
            <span class="w3-bold">https://github.com/<?=$user['github_username']?></span>
        </a>
    </p>

</div>

<hr>

<?php if(mysqli_num_rows($result) == 0): ?>
    
    <div class="w3-panel w3-light-grey">
        <h3 class="w3-margin-top"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> No Results Found</h3>
        <p>There are no timesheet entries for <strong><?=date('l F j, Y', strtotime($_GET['date']))?></strong>.</p>
    </div>

<?php else: ?>

    <table class="w3-table w3-bordered w3-striped w3-margin-bottom">
        <thead>
            <tr>
                <th>Project</th>
                <th>Hours</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while($record = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?=$applications[$record['application_id']]['name']?></td>
                    <td><?=$record['hours']?></td>
                    <td><?=$record['description']?></td>
                    <td><?=time_elapsed_string(date($record['date']))?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php endif; ?>

<hr>

<a href="/list" class="w3-button w3-white w3-border">
    <i class="fa-solid fa-caret-left fa-padding-right"></i>
    Back to Contributions List
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');