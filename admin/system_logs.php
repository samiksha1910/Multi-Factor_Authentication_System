
<?php
include('../config/db.php');

$result = mysqli_query($conn, "SELECT * FROM audit_logs ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Audit Logs | Secure Auth</title>
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
            color: #fff;
            text-align: left;
            padding: 14px;
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

        .no-data {
            text-align: center;
            font-size: 18px;
            color: #555;
            margin-top: 50px;
        }

        .table-container {
            overflow-x: auto;
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
    <h2>Audit Logs</h2>

    <div class="table-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<table>
                    <tr>
                        <th>User ID</th>
                        <th>Event</th>
                        <th>Details</th>
                        <th>Time</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['event']}</td>
                        <td>{$row['details']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='no-data'>No logs found.</div>";
        }
        ?>
    </div>
</body>
</html>
