
<?php
include_once '../config/db.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/access_denied.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = $conn->prepare("SELECT * FROM compromise_reports WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $report = $result->fetch_assoc();
} else {
    die("Invalid Report ID.");
}

if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    
    $valid_statuses = ['Pending', 'Reviewed', 'Resolved'];
    if (!in_array($new_status, $valid_statuses)) {
        die("Invalid status value!");
    }

    $update = $conn->prepare("UPDATE compromise_reports SET status = ? WHERE id = ?");
    $update->bind_param("si", $new_status, $id);
    
    if ($update->execute()) {
        echo "<script>alert('✅ Status updated successfully!'); window.location='compromise_requests.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to update status.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Report Status</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 30px; 
            background: #f8f9fa; 
        }
        .container { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            width: 450px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
            margin: auto; 
        }
        h2 { 
            text-align: center; 
            color: #333; 
        }
        select, button { 
            width: 100%; 
            padding: 10px; 
            margin-top: 10px; 
            border-radius: 5px; 
            border: 1px solid #ccc; 
        }
        button { 
            background: #007bff; 
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        button:hover { 
            background: #0056b3; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Report Status</h2>
        <form method="POST">
            <label>Status:</label>
            <select name="status" required>
                <option value="Pending" <?= ($report['status']=='Pending')?'selected':''; ?>>Pending</option>
                <option value="Reviewed" <?= ($report['status']=='Reviewed')?'selected':''; ?>>Reviewed</option>
                <option value="Resolved" <?= ($report['status']=='Resolved')?'selected':''; ?>>Resolved</option>
            </select>
            <button type="submit" name="update_status">Update Status</button>
        </form>
    </div>
</body>
</html>
