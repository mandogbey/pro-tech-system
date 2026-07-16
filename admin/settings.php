<?php

include "../includes/auth_admin.php";
include "../includes/db.php";
include "../includes/admin_header.php";
include "../includes/admin_sidebar.php";

$message = "";
$messageType = "success";

// Current Settings
$result = $conn->query("
SELECT *
FROM election_settings
LIMIT 1
");

$settings = $result->fetch_assoc();

if(isset($_POST['save']))
{
    $title = trim($_POST['title']);
    $status = $_POST['status'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $stmt = $conn->prepare("
    UPDATE election_settings
    SET
        election_title=?,
        election_status=?,
        start_date=?,
        end_date=?
    WHERE id=?
    ");

    $stmt->bind_param(
        "ssssi",
        $title,
        $status,
        $start,
        $end,
        $settings['id']
    );

    if($stmt->execute())
    {
        $message = "Election settings updated successfully.";

        $result = $conn->query("
        SELECT *
        FROM election_settings
        LIMIT 1
        ");

        $settings = $result->fetch_assoc();
    }
    else
    {
        $message = "Unable to update settings.";
        $messageType = "danger";
    }
}
?>

<div class="container-fluid py-4">

    <!-- Header -->

    <div class="card shadow border-0 rounded-4 mb-4 bg-primary text-white">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h2 class="fw-bold mb-2">
                        ⚙ Election Settings
                    </h2>

                    <p class="mb-0">
                        Configure election information and voting schedule.
                    </p>

                </div>

                <div>

                    <?php if($settings['election_status']=="Open"){ ?>

                        <span class="badge bg-success fs-6 px-4 py-2">
                            🟢 OPEN
                        </span>

                    <?php } else { ?>

                        <span class="badge bg-danger fs-6 px-4 py-2">
                            🔴 CLOSED
                        </span>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

    <?php if($message!=""){ ?>

    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show">

        <?= $message ?>

        <button class="btn-close" data-bs-dismiss="alert"></button>

    </div>

    <?php } ?>

    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow border-0 rounded-4">

                <div class="card-header bg-light">

                    <h4 class="mb-0">
                        📝 Election Details
                    </h4>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Election Title
                            </label>

                            <input
                            type="text"
                            class="form-control form-control-lg"
                            name="title"
                            value="<?= htmlspecialchars($settings['election_title']); ?>"
                            required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Election Status
                            </label>

                            <select
                            class="form-select form-select-lg"
                            name="status">

                                <option value="Open"
                                <?= $settings['election_status']=="Open"?"selected":"" ?>>
                                    Open
                                </option>

                                <option value="Closed"
                                <?= $settings['election_status']=="Closed"?"selected":"" ?>>
                                    Closed
                                </option>

                            </select>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-bold">
                                    Start Date
                                </label>

                                <input
                                type="datetime-local"
                                class="form-control"
                                name="start_date"
                                value="<?= date('Y-m-d\TH:i',strtotime($settings['start_date'])) ?>"
                                required>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-bold">
                                    End Date
                                </label>

                                <input
                                type="datetime-local"
                                class="form-control"
                                name="end_date"
                                value="<?= date('Y-m-d\TH:i',strtotime($settings['end_date'])) ?>"
                                required>

                            </div>

                        </div>

                        <button
                        class="btn btn-primary btn-lg px-5 rounded-pill"
                        type="submit"
                        name="save">

                            💾 Save Settings

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Side Card -->

        <div class="col-lg-4">

            <div class="card shadow border-0 rounded-4">

                <div class="card-body">

                    <h4 class="mb-4">
                        📋 Current Election
                    </h4>

                    <table class="table table-borderless">

                        <tr>
                            <th>Title</th>
                            <td><?= htmlspecialchars($settings['election_title']) ?></td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>

                                <?php if($settings['election_status']=="Open"){ ?>

                                    <span class="badge bg-success">
                                        Open
                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-danger">
                                        Closed
                                    </span>

                                <?php } ?>

                            </td>
                        </tr>

                        <tr>
                            <th>Starts</th>
                            <td><?= date("d M Y H:i",strtotime($settings['start_date'])) ?></td>
                        </tr>

                        <tr>
                            <th>Ends</th>
                            <td><?= date("d M Y H:i",strtotime($settings['end_date'])) ?></td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
include "../includes/admin_footer.php";
?>