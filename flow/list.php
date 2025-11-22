<?php

define('APP_NAME', 'Flow');
define('PAGE_TITLE', 'Dashboard');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

/*
$query = 'SELECT qrs.*,(
        SELECT COUNT(*)
        FROM qr_logs
        WHERE qrs.id = qr_logs.qr_id
    ) AS scans
    FROM qrs 
    ORDER BY name';
$result = mysqli_query($connect, $query);
$qr_count = mysqli_num_rows($result);

$query = 'SELECT *
    FROM qr_logs
    INNER JOIN qrs 
    ON qrs.id = qr_logs.qr_id';
$log_count = mysqli_num_rows(mysqli_query($connect, $query));
*/

?>

<main>

    <div class="w3-center">
        <h1>Flow</h1>
    </div>

    <hr>

    <div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        
    
    </div>

</main>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');