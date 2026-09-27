<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>

/* =========================================
   ESG SENTINEL - COMMON SIDEBAR
   ========================================= */

.sidebar {
    width: 250px !important;
    height: 100vh !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;

    background: #0b2422 !important;

    padding: 28px 18px !important;
    box-sizing: border-box !important;

    color: #ffffff !important;

    z-index: 9999 !important;

    overflow-y: auto !important;
}


/* ---------- LOGO ---------- */

.sidebar .logo {
    font-family: Arial, sans-serif !important;
    font-size: 24px !important;
    font-weight: 700 !important;

    color: #ffffff !important;

    margin: 0 0 35px 0 !important;
    padding: 0 10px !important;

    letter-spacing: 1px !important;
}

.sidebar .logo span {
    color: #4fd18b !important;
}


/* ---------- MENU ---------- */

.sidebar .sidebar-menu {
    list-style: none !important;

    margin: 0 !important;
    padding: 0 !important;
}


/* ---------- MENU ITEM ---------- */

.sidebar .sidebar-menu li {
    margin: 0 0 7px 0 !important;
    padding: 0 !important;
}


/* ---------- MENU LINK ---------- */

.sidebar .sidebar-menu li a {
    display: block !important;

    width: 100% !important;
    box-sizing: border-box !important;

    padding: 12px 14px !important;

    border-radius: 8px !important;

    background: transparent !important;

    color: #d9e7e4 !important;

    text-decoration: none !important;

    font-family: Arial, sans-serif !important;
    font-size: 14px !important;
    font-weight: 500 !important;

    transition: all 0.2s ease !important;
}


/* ---------- HOVER ---------- */

.sidebar .sidebar-menu li a:hover {
    background: #193f3a !important;

    color: #ffffff !important;
}


/* ---------- ACTIVE PAGE ---------- */

.sidebar .sidebar-menu li a.active {
    background: #24594f !important;

    color: #ffffff !important;

    font-weight: 600 !important;
}


/* ---------- ACTIVE HOVER ---------- */

.sidebar .sidebar-menu li a.active:hover {
    background: #2c675b !important;

    color: #ffffff !important;
}


/* ---------- SCROLLBAR ---------- */

.sidebar::-webkit-scrollbar {
    width: 5px !important;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #315b55 !important;
    border-radius: 10px !important;
}


/* =========================================
   MOBILE
   ========================================= */

@media (max-width: 600px) {

    .sidebar {
        display: none !important;
    }

}
.user-section {
    margin: -10px 0 25px 0;
    padding: 14px;
    background: #102f2b;
    border: 1px solid #28534c;
    border-radius: 10px;
}

.user-name {
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 4px;
}

.user-role {
    color: #7fc9a5;
    font-size: 12px;
    margin-bottom: 12px;
}

.logout-btn {
    display: block;
    text-align: center;
    padding: 8px 10px;
    border-radius: 7px;
    background: #193f3a;
    color: #ffffff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
}

.logout-btn:hover {
    background: #24594f;
}

</style>


<aside class="sidebar">

    <div class="logo">
        ESG<span>SENTINEL</span>
    </div>



<div class="user-section">

    <div class="user-name">
        <?php echo htmlspecialchars($_SESSION["user_name"] ?? "Admin"); ?>
    </div>

    <a href="logout.php" class="logout-btn">
        Logout
    </a>

</div>
    <ul class="sidebar-menu">

        <li>
            <a href="dashboard.php"
               class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                Dashboard
            </a>
        </li>


        <li>
            <a href="live_monitoring.php"
               class="<?php echo ($current_page == 'live_monitoring.php') ? 'active' : ''; ?>">
                Live Monitoring
            </a>
        </li>


        <li>
            <a href="sensors.php"
               class="<?php echo ($current_page == 'sensors.php') ? 'active' : ''; ?>">
                Sensors
            </a>
        </li>


        <li>
            <a href="esg_analytics.php"
               class="<?php echo ($current_page == 'esg_analytics.php') ? 'active' : ''; ?>">
                ESG Analytics
            </a>
        </li>


        <li>
            <a href="reports.php"
               class="<?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">
                Evidence & Reports
            </a>
        </li>


        <li>
            <a href="ai_insights.php"
               class="<?php echo ($current_page == 'ai_insights.php') ? 'active' : ''; ?>">
                AI Insights
            </a>
        </li>


        <li>
            <a href="what_if.php"
               class="<?php echo ($current_page == 'what_if.php') ? 'active' : ''; ?>">
                What-if Simulation
            </a>
        </li>


        <li>
            <a href="reverification.php"
               class="<?php echo ($current_page == 'reverification.php') ? 'active' : ''; ?>">
                Re-Verification
            </a>
        </li>


        <li>
            <a href="alerts.php"
               class="<?php echo ($current_page == 'alerts.php') ? 'active' : ''; ?>">
                Alerts
            </a>
        </li>


        <li>
            <a href="settings.php"
               class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
                Settings
            </a>
        </li>
        <li>
    <a href="certificate.php" class="<?php echo ($current_page == 'certificate.php') ? 'active' : ''; ?>">
        Certificate
    </a>
</li>

    </ul>

</aside>