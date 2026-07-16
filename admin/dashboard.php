<?php
include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";

/* ==============================
   Dashboard Statistics
============================== */

$userCount = $conn->query("
SELECT COUNT(*) total
FROM users
WHERE role='user'
")->fetch_assoc()['total'];

$candidateCount = $conn->query("
SELECT COUNT(*) total
FROM candidates
")->fetch_assoc()['total'];

$voteCount = $conn->query("
SELECT COUNT(*) total
FROM votes
")->fetch_assoc()['total'];

$positionCount = $conn->query("
SELECT COUNT(*) total
FROM positions
")->fetch_assoc()['total'];

$election = $conn->query("
SELECT *
FROM election_settings
LIMIT 1
")->fetch_assoc();

$percentage = 0;

if ($userCount > 0 && $positionCount > 0) {
    $percentage = round(
        ($voteCount / ($userCount * $positionCount)) * 100,
        2
    );
}
?>

<div class="dashboard">

    <!-- Statistics Cards -->

    <div class="stats-grid">

        <div class="stat-card blue">
            <div>
                <h2><?= $userCount ?></h2>
                <p>Registered Students</p>
            </div>
            <span>👨‍🎓</span>
        </div>

        <div class="stat-card green">
            <div>
                <h2><?= $candidateCount ?></h2>
                <p>Candidates</p>
            </div>
            <span>🗳️</span>
        </div>

        <div class="stat-card orange">
            <div>
                <h2><?= $voteCount ?></h2>
                <p>Votes Cast</p>
            </div>
            <span>✅</span>
        </div>

        <div class="stat-card purple">
            <div>
                <h2><?= $positionCount ?></h2>
                <p>Positions</p>
            </div>
            <span>🏆</span>
        </div>

    </div>

    <!-- Election Information -->

    <div class="dashboard-panel">

        <h2>Election Information</h2>

        <div class="info-grid">

            <div class="info-item">
                <span>Election Title</span>
                <strong><?= $election['election_title']; ?></strong>
            </div>

            <div class="info-item">
                <span>Status</span>

                <strong class="badge">
                    <?= ucfirst($election['election_status']); ?>
                </strong>
            </div>

            <div class="info-item">
                <span>Start Date</span>
                <strong><?= $election['start_date']; ?></strong>
            </div>

            <div class="info-item">
                <span>End Date</span>
                <strong><?= $election['end_date']; ?></strong>
            </div>

        </div>

        <h3>Voting Progress</h3>

        <div class="progress-bar">

            <div class="progress-fill" style="width:<?= $percentage ?>%;">
                <?= $percentage ?>%
            </div>

        </div>

    </div>

</div>

<?php
include "../includes/admin_footer.php";
?>