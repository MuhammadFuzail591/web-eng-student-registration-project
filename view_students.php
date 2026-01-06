<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$filename = 'students_data.txt';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students Data</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .data-display {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            max-height: 600px;
            overflow-y: auto;
        }

        .no-data {
            text-align: center;
            color: #999;
            padding: 40px;
            font-size: 16px;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }

        .info-box {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box strong {
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Records</h1>
        <p class="subtitle">All registered students data</p>

        <a href="index.html" class="back-btn">Back</a>

        <?php
        if (file_exists($filename)) {
            $fileContent = file_get_contents($filename);
            
            if (!empty(trim($fileContent))) {
                $recordCount = substr_count($fileContent, '=== Student Record ===');
                
                echo '<div class="info-box">';
                echo '<strong>Total Records:</strong> ' . $recordCount;
                echo '</div>';
                
                echo '<div class="data-display">';
                echo htmlspecialchars($fileContent);
                echo '</div>';
            } else {
                echo '<div class="no-data">No student records found. Register a student to see data here.</div>';
            }
        } else {
            echo '<div class="no-data">No data file found. Register your first student to create the database.</div>';
        }
        ?>
    </div>
</body>
</html>