<div
  id="avatar-options"
  class="w3-modal"
  style="z-index: 200; opacity: 0; display: none"
>

    <div class="w3-card-4 w3-border" style="max-width: 300px; position: fixed; top: 68px; right: 10px; z-index: 120" id="">
        
        <img src="<?=user_avatar($_user['id']);?>" alt="" style="max-width: 100%">

        <div class="w3-container w3-white">

            <p>
                You are logged in as 
                <a href="<?=ENV_DOMAIN?>/dashboard"><?=user_name($_user['id'])?></a>
            </p>
            <?php if($_user['github_username']): ?>
                <p>
                    <a href="https://github.com/<?=$_user['github_username']?>">
                        <i class="fa-brands fa-github fa-padding-right"></i>
                        <?=$_user['github_username']?>
                    </a>
                </p>
            <?php endif; ?>

        </div>

        <footer class="w3-container w3-center w3-light-grey w3-padding w3-border-top">

            <a class="w3-button w3-border w3-white" href="<?=ENV_DOMAIN?>/dashboard">
                <i class="fa-solid fa-user fa-padding-right "></i>
                My Account
            </a>
            <a class="w3-button w3-border w3-white" href="<?=ENV_DOMAIN?>/action/logout">
                <i class="fa-solid fa-lock-open fa-padding-right "></i>
                Logout
            </a>
            <a class="w3-button w3-white w3-border w3-margin-top" onclick="closeModal('avatar-options');">
                Close
            </a>
            
        </footer>

    </div>

</div>

<script>

    function toggleAvatarOptions(event)
    {
        
        var avatarOptions = document.getElementById("avatar-options");
        if (avatarOptions.style.display == "block") 
        {
            closeAvatarOptions();
        } 
        else 
        { 
            avatarOptions.style.display = "block";
            closeSidebar();
        }

        event.preventDefault();
        event.stopPropagation();

    }

    document.addEventListener('click', function(e){

        if(e.target.className == "w3-overlay" || e.target.className == "w3-modal")
        {
            closeAvatarOptions();
            closeSidebar();
            closeAllModals();
        }

    });

    function closeAllModals()
    {

        let modals = document.getElementsByClassName('w3-modal');
        for(var i = 0; i < modals.length; i++) 
        {
            closeModal(modals[i].id);
        }

    }

    function closeSidebar()
    {

        let sidebar = document.getElementById("sidebar");
        if (sidebar.style.left == "0px") {
            w3SidebarToggle(false);
        }

    }

    function closeAvatarOptions()
    {

        var avatarOptions = document.getElementById("avatar-options");
        if (avatarOptions.style.display == "block")
        {
            avatarOptions.style.display = "none";
        }
        
    }

</script>