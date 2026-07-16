<div class="admin-sidebar">

    

    <nav class="sidebar-menu">

        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <span class="icon">🏠</span>
            Dashboard
        </a>

        <a href="users.php" class="<?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
            <span class="icon">👥</span>
            Manage Students
        </a>

        <a href="candidates.php" class="<?= basename($_SERVER['PHP_SELF']) == 'candidates.php' ? 'active' : ''; ?>">
            <span class="icon">🗳️</span>
            Candidates
        </a>

        <a href="add_candidate.php" class="<?= basename($_SERVER['PHP_SELF']) == 'add_candidate.php' ? 'active' : ''; ?>">
            <span class="icon">➕</span>
            Add Candidate
        </a>

        <a href="positions.php" class="<?= basename($_SERVER['PHP_SELF']) == 'positions.php' ? 'active' : ''; ?>">
            <span class="icon">🏛️</span>
            Positions
        </a>

        <a href="vote_count.php" class="<?= basename($_SERVER['PHP_SELF']) == 'vote_count.php' ? 'active' : ''; ?>">
            <span class="icon">📊</span>
            Vote Count
        </a>

        <a href="settings.php" class="<?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <span class="icon">⚙️</span>
            Election Settings
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="../logout.php" class="logout-link">
            <span class="icon">🚪</span>
            Logout
        </a>
    </div>

</div>

<div class="admin-content">