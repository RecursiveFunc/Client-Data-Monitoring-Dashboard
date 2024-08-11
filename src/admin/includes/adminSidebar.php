<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php
if (isset($_SESSION['jenis_role']) && $_SESSION['jenis_role']) {
    // Check if the user is an admin (assuming jenis_role=2 for admin)
    if ($_SESSION['jenis_role'] == 1) {
        // Admin menu items
        echo '<li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <span data-feather="home"></span>
                    Main Dashboard
                </a>
              </li>';
        echo '<li class="nav-item">
                <a class="nav-link" href="profile.php">
                    <span data-feather="user"></span>
                    Profile
                </a>
              </li>';
        // Add more admin-specific menu items as needed
    } elseif ($_SESSION['jenis_role'] == 2 || $_SESSION['jenis_role'] == 3) {
        // Regular user menu items
        echo '<li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <span data-feather="home"></span>
                    Main Dashboard
                </a>
              </li>';
        echo '<li class="nav-item">
                <a class="nav-link" href="profile.php">
                    <span data-feather="user"></span>
                    Profile
                </a>
              </li>';
        echo '<li class="nav-item">
            <a class="nav-link" href="users.php">
                <span data-feather="users"></span>
                Users
            </a>
        </li>';
        echo '<li class="nav-item">
            <a class="nav-link" href="summary.php">
                <span data-feather="activity"></span>
                Summary
            </a>
        </li>';
        echo '<li class="nav-item">
            <a class="nav-link" href="pekerjaan.php">
                <span data-feather="briefcase"></span>
                Jobs
            </a>
        </li>';
        echo '<li class="nav-item">
            <a class="nav-link" href="app.php">
                <span data-feather="monitor"></span>
                Application
            </a>
        </li>';
    }
}
?>