<?php

define('APP_NAME', 'Flow');
define('PAGE_TITLE', 'Contributions');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

$query = 'SELECT user_id,
    SUM(hours) AS total_hours
    FROM entries
    GROUP BY user_id
    ORDER BY total_hours';
$result = mysqli_query($connect, $query);
$usder_count = mysqli_num_rows($result);

$applications_json = fetch_json('https://applications.brickmmo.com/api/applications/timesheets/true');
$applications = array();
foreach($applications_json['applications'] as $application) {
    $applications[$application['id']] = $application;
}

$users_json = fetch_json('https://sso.brickmmo.com/api/users');
$users = array();
foreach($users_json['users'] as $user) {
    $users[$user['id']] = $user;
}

?>

<div class="w3-center">
    <h1>Contributions</h1>
</div>

<hr>

<div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <?php while ($record = mysqli_fetch_assoc($result)): ?>

        <div style="width: calc(33.3% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                <div class="w3-card-4 w3-margin-top" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                        
                <header class="w3-container w3-green">
                    <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$users[$record['user_id']]['github_username']?></h4>
                </header>

                <div class="w3-center w3-margin">
                    <a href="<?=ENV_DOMAIN?>/details/<?=$record['user_id']?>">
                        <img src="<?=$users[$record['user_id']]['avatar']?>" alt="" style="width: 100%;">    
                    </a>
                    
                    <div class="w3-margin-top">
                        Name: 
                        <strong>
                            <?=$users[$record['user_id']]['first']?>
                            <?=$users[$record['user_id']]['last']?>
                        </strong>
                    
                        <br>

                        Total Hours: 
                        <strong>
                            <?=number_format($record['total_hours'], 2)?>
                        </strong>

                        <br>

                    </div>
                    <div class="w3-margin-top">

                        <a
                            href="<?=ENV_DOMAIN?>/details/<?=$record['user_id']?>"
                            class="w3-button w3-white w3-border"
                        >
                            <i class="fa-solid fa-magnifying-glass fa-padding-right"></i> Details
                        </a>

                        <a
                            href="https://github.com/<?=$users[$record['user_id']]['github_username']?>"
                            class="w3-button w3-white w3-border"
                        >
                            <i class="fa-brands fa-github fa-padding-right"></i> GitHub
                        </a>

                    </div>

                </div>
                
            </div>

        </div>

    <?php endwhile; ?>

</div>



<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');