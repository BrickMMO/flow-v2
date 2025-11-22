<?php

security_check();

if(
    !isset($_GET['key']) || 
    !is_numeric($_GET['key']))
{
    message_set('Tag Error', 'There was an error with the provided QR code.');
    header_redirect('/console/dashboard');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['name']) || !validate_blank($_POST['url']))
    {
        message_set('QR Code Error', 'There was an error with the provided QR code.', 'red');
        header_redirect('/console/add');
    }
    
    $query = 'UPDATE qrs SET
        name = "'.addslashes($_POST['name']).'",
        url = "'.addslashes($_POST['url']).'",
        updated_at = NOW()
        WHERE id = '.$_GET['key'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('QR Success', 'QR code has been successfully updated.');
    header_redirect('/console/dashboard');
    
}

define('APP_NAME', 'QR Codes');
define('PAGE_TITLE', 'Edit QR Code');
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
    Add QR Code
</p>

<hr>

<h2>Edit QR Code: <?=$qr['name']?></h2>

<!-- Display the QR code -->
<img src="<?= $qr['image'] ?>" alt="" style="max-width: 200px" class="w3-padding w3-border">

<!-- Edit form -->
<form
    method="post"
    novalidate
    id="main-form"
>

    <input  
        name="name" 
        class="w3-input w3-border w3-margin-top" 
        type="text" 
        id="name" 
        autocomplete="off"
        value="<?=$qr['name']?>"
    />
    <label for="name" class="w3-text-gray">
        Name <span id="name-error" class="w3-text-red"></span>
    </label>

    <input  
        name="url" 
        class="w3-input w3-border w3-margin-top" 
        type="text" 
        id="url" 
        autocomplete="off"
        value="<?=$qr['url']?>"
    />
    <label for="url" class="w3-text-gray">
        URL <span id="url-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-tag fa-padding-right"></i>
        Add QR Code
    </button>

</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let name = document.getElementById("name");
        let name_error = document.getElementById("name-error");
        name_error.innerHTML = "";
        if (name.value == "") {
            name_error.innerHTML = "(name is required)";
            errors++;
        }

        let url = document.getElementById("url");
        let url_error = document.getElementById("url-error");
        url_error.innerHTML = "";
        if (url.value == "") {
            url_error.innerHTML = "(URL is required)";
            errors++;
        }

        if (errors) return false;
    }

</script>

<?php

include('../templates/main_footer.php');
include('../templates/html_footer.php');

?>
