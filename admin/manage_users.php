

<?php
include_once '../config/db.php';
include_once '../includes/log_action.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/access_denied.php");
    exit();
}

$result = mysqli_query($conn, "SELECT id, username, email, role, blocked FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Secure Auth</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f8fc;
            margin: 0;
            padding: 40px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #222;
            margin-bottom: 25px;
            font-size: 28px;
            letter-spacing: 0.5px;
        }

        .top-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        .add-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: #2563eb;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        th {
            background: #3b82f6;
            color: white;
            padding: 14px;
            text-align: left;
            font-size: 16px;
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }

        tr:hover {
            background: #f1f5ff;
            transition: 0.3s;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .btn {
            border: none;
            padding: 6px 12px;
            margin: 3px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-block {
            background: #ef4444;
            color: white;
        }

        .btn-block:hover {
            background: #dc2626;
        }

        .btn-unblock {
            background: #22c55e;
            color: white;
        }

        .btn-unblock:hover {
            background: #16a34a;
        }

        .btn-delete {
            background: #6b7280;
            color: white;
        }

        .btn-delete:hover {
            background: #4b5563;
        }

        .link {
            display: block;
            text-align: center;
            margin-top: 25px;
        }

        .link a {
            text-decoration: none;
            color: #3b82f6;
            font-weight: 500;
            transition: 0.3s;
        }

        .link a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h2>Manage Users</h2>

    <div class="top-bar">
        <a href="add_user.php"><button class="add-btn">+ Add New User</button></a>
    </div>

    <div class="table-container">
        <?php if($result && mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= $row['blocked'] ? '<span style="color:red;">Blocked</span>' : '<span style="color:green;">Active</span>' ?></td>
                <td>
                    <?php if(!$row['blocked']): ?>
                        <a href="block_user.php?id=<?= $row['id'] ?>"><button class="btn btn-block">Block</button></a>
                    <?php else: ?>
                        <a href="unblock_user.php?id=<?= $row['id'] ?>"><button class="btn btn-unblock">Unblock</button></a>
                    <?php endif; ?>
                    <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">
                        <button class="btn btn-delete">Delete</button>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p style="text-align:center;">No users found.</p>
        <?php endif; ?>
    </div>

    <div class="link">
        <a href="system_logs.php">View Audit Logs</a>
    </div>
</body>
</html>

