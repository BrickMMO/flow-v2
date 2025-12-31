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
    if (!validate_blank($_POST['hours']) || 
        !validate_blank($_POST['description']) || 
        !validate_blank($_POST['application_id']) || 
        !validate_blank($_POST['date']))
    {

        message_set('Timesheet Entry Error', 'There was an error with the timesheet entry.', 'red');
        header_redirect('/console/add');

    }
    
    $query = 'UPDATE entries SET
        date = "'.addslashes($_POST['date']).'",
        hours = "'.addslashes($_POST['hours']).'",
        description = "'.addslashes($_POST['description']).'",
        application_id = "'.addslashes($_POST['application_id']).'",
        updated_at = NOW()
        WHERE id = '.$_GET['key'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Timesheet Entry Success', 'Timesheet entry has been successfully updated.');
    header_redirect('/console/timesheet/date/'.$_POST['date']);
    
}

define('APP_NAME', 'Flow');
define('PAGE_TITLE', 'Edit Timesheet Entry');
define('PAGE_SELECTED_SECTION', 'flow');
define('PAGE_SELECTED_SUB_PAGE', '/console/edit');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');
include('../templates/message.php');

$query = 'SELECT * 
    FROM entries 
    WHERE id = "'.$_GET['key'].'" 
    LIMIT 1';
$result = mysqli_query($connect, $query);
$entry = mysqli_fetch_assoc($result);

$applications_json = fetch_json('https://applications.brickmmo.com/api/applications/timesheets/true');
$applications = array();

foreach($applications_json['applications'] as $application) 
{
    
    $applications[$application['id']] = $application['name'];

}

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/flow.png"
        height="50"
        style="vertical-align: top"
    />
    Flow
</h1>

<p>
    <a href="<?=ENV_DOMAIN?>/console/dashboard">Flow</a> / 
    <a href="<?=ENV_DOMAIN?>/console/timesheet/date/<?=htmlspecialchars($entry['date'])?>">Timesheet</a> / 
    Edit Timesheet Entry
</p>

<hr>

<h2>Edit Timesheet Entry</h2>

<form
    method="post"
    novalidate
    id="main-form"
>
    <input  
        name="date" 
        class="w3-input w3-border" 
        type="date" 
        id="date" 
        autocomplete="off"
        value="<?=htmlspecialchars($entry['date'])?>"
    />
    <label for="date" class="w3-text-gray">
        Date <span id="date-error" class="w3-text-red"></span>
    </label>

    <input  
        name="hours" 
        class="w3-input w3-border w3-margin-top" 
        type="number" 
        id="hours" 
        autocomplete="off"
        value="<?=htmlspecialchars($entry['hours'])?>"
    />
    <label for="hours" class="w3-text-gray">
        Hours <span id="hours-error" class="w3-text-red"></span>
    </label>

    <input  
        name="description" 
        class="w3-input w3-border w3-margin-top" 
        type="text" 
        id="description" 
        autocomplete="off"
        value="<?=htmlspecialchars($entry['description'])?>"
    />
    <label for="description" class="w3-text-gray">
        Description <span id="description-error" class="w3-text-red"></span>
    </label>

    <?=form_select_array('application_id', $applications, array('lebel' => 'Application', 'empty_key' => 0, 'empty_value' => '', 'selected' => $entry['application_id']));?>
    <label for="application_id" class="w3-text-gray">
        Application <span id="application-id-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-tag fa-padding-right"></i>
        Update Timesheet Entry
    </button>
</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let hours = document.getElementById("hours");
        let hours_error = document.getElementById("hours-error");
        hours_error.innerHTML = "";
        if (hours.value < 1 || hours.value > 12) {
            hours_error.innerHTML = "(hours is required)";
            errors++;
        }
        else if (!Number.isInteger(Number(hours.value))) {
            hours_error.innerHTML = "(hours is invalid)";
            errors++;
        }

        let description = document.getElementById("description");
        let description_error = document.getElementById("description-error");
        description_error.innerHTML = "";
        if (description.value == "") {
            description_error.innerHTML = "(description is required)";
            errors++;
        }

        let application_id = document.getElementById("application_id");
        let application_id_error = document.getElementById("application-id-error");

        application_id_error.innerHTML = "";
        if (application_id.value == 0) {
            application_id_error.innerHTML = "(application is required)";
            errors++;
        }

        if (errors) return false;
    }

</script>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
