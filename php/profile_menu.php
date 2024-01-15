<div class="profile_menu">
    <a href="profile.php" id="profile_menu_profile"><i style='font-size:12px' class='fa fa-fw fa-user'></i> Στοιχεία Λογαριασμού</a>
    
    <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'student') : ?>
            <a href="student_applications.php" id="profile_menu_student_applications"><i style='font-size:12px' class='fas'>&#xf15c;</i> Αιτήσεις</a>
    <?php else : ?>
        <a href="company_ads.php" id="profile_menu_company_ads"><i style='font-size:12px' class='fas'>&#xf15c;</i> Αγγελίες</a>
    <?php endif; ?>
</div>
