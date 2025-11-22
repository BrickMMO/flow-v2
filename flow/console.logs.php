<?php

security_check();

if(
    !isset($_GET['key']) || 
    !is_numeric($_GET['key']))
{
    message_set('Tag Error', 'There was an error with the provided QR code.');
    header_redirect('/console/dashboard');
}

define('APP_NAME', 'QR Codes');
define('PAGE_TITLE', 'QR Scan Logs');
define('PAGE_SELECTED_SECTION', 'qr-codes');
define('PAGE_SELECTED_SUB_PAGE', '/console/dashboard');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');
include('../templates/message.php');

$query = 'SELECT *
    FROM qrs
    WHERE id = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);
$qr = mysqli_fetch_assoc($result);

$query = 'SELECT *
    FROM qr_logs
    WHERE qr_id = "'.addslashes($_GET['key']).'"
    ORDER BY created_at DESC';
$result = mysqli_query($connect, $query);

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/qr.png"
        height="50"
        style="vertical-align: top"
    />
    QR Codes
</h1>
<p>
    <a href="/console/dashboard">Qr Codes</a> / 
    QR Code Logs
</p>

<hr> 

<h2>QR Scan Logs: <?=$qr['name']?></h2>

<table class="w3-table w3-bordered w3-striped w3-margin-bottom">
    <tr>
        <th>Name</th>
        <th>URL</th>
        <th>Date</th>
    </tr>

    <?php while ($record = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?=$record['name']?></td>
            <td><a href="<?=$record['url']?>"><?=$record['url']?></a></td>
            <td><?=time_elapsed_string($record['created_at'])?></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php

// Include footer templates
include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
