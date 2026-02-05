<?php
include '../includes/db.php';

// 1. Security Check: Ensure user is Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login.php");
    exit();
}

// 2. Handle Form Submissions (Add or Delete)
$message = "";

// A. Add Bus Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_bus'])) {
    $bus_number = $_POST['bus_number'];
    $route_id = $_POST['route_id'];
    $bus_type = $_POST['bus_type'];
    $seats = $_POST['total_seats'];
    $dep_time = $_POST['departure_time'];
    $arr_time = $_POST['arrival_time'];

    $sql = "INSERT INTO buses (bus_number, route_id, bus_type, total_seats, departure_time, arrival_time) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisiss", $bus_number, $route_id, $bus_type, $seats, $dep_time, $arr_time);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Bus added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

// B. Delete Bus Logic
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM buses WHERE bus_id=$id");
    header("Location: manage_bus.php"); // Refresh page
    exit();
}

// 3. Fetch Data for Display
// Get all routes for the dropdown menu
$routes = $conn->query("SELECT * FROM routes");

// Get all buses to show in the table (Joined with Routes to show Source/Dest)
$buses = $conn->query("
    SELECT b.*, r.source, r.destination 
    FROM buses b 
    JOIN routes r ON b.route_id = r.route_id 
    ORDER BY b.bus_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Buses - SmartBus Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark p-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-bus-alt me-2"></i>SmartBus Admin</a>
            <a href="../includes/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Manage Buses</li>
            </ol>
        </nav>

        <?php echo $message; ?>

        <div class="row">
            
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Add New Bus</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="manage_bus.php">
                            <div class="mb-3">
                                <label class="form-label">Bus Number</label>
                                <input type="text" name="bus_number" class="form-control" placeholder="e.g. KL-01-AB-1234" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Select Route</label>
                                <select name="route_id" class="form-select" required>
                                    <option value="">-- Choose Route --</option>
                                    <?php while($r = $routes->fetch_assoc()): ?>
                                        <option value="<?php echo $r['route_id']; ?>">
                                            <?php echo $r['source'] . " ➔ " . $r['destination']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="form-text"><a href="manage_route.php">Create new route?</a></div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="bus_type" class="form-select">
                                        <option value="AC">AC</option>
                                        <option value="NON-AC">Non-AC</option>
                                        <option value="SLEEPER">Sleeper</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Seats</label>
                                    <input type="number" name="total_seats" class="form-control" value="40" required>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Departure</label>
                                    <input type="time" name="departure_time" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Arrival</label>
                                    <input type="time" name="arrival_time" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" name="add_bus" class="btn btn-primary w-100">Add Bus</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Existing Fleet</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Bus No</th>
                                    <th>Route</th>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($buses->num_rows > 0): ?>
                                    <?php while($row = $buses->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo $row['bus_number']; ?></td>
                                        <td>
                                            <?php echo $row['source']; ?> <i class="fas fa-arrow-right small text-muted"></i> <?php echo $row['destination']; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-dark"><?php echo $row['bus_type']; ?></span>
                                        </td>
                                        <td>
                                            <?php echo substr($row['departure_time'], 0, 5); ?> - 
                                            <?php echo substr($row['arrival_time'], 0, 5); ?>
                                        </td>
                                        <td>
                                            <a href="manage_bus.php?delete=<?php echo $row['bus_id']; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure you want to delete this bus?');">
                                               <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No buses found. Add one on the left!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>