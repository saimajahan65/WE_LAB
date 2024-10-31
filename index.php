<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
        }
        h1 {
            color: #4CAF50;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action-buttons a {
            text-decoration: none;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            margin: 0 5px;
            font-size: 14px;
        }
        .delete-button {
            background-color: #e74c3c;
        }
        .update-button {
            background-color: #3498db;
        }
        .form-container {
            margin: 20px 0;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
        }
        .form-container input {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>Student Management System</h1>

<!-- Form to Insert Student -->
<div class="form-container">
    <form method="post">
        <h2>Add Student</h2>
        <input type="number" name="id" placeholder="Student ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="number" name="age" placeholder="Age" required>
        <input type="text" name="course" placeholder="Course" required>
        <input type="submit" name="insert" value="Add Student">
    </form>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Course</th>
        <th>Action</th>
    </tr>

    <?php
    // Database connection
    $dbhost = "localhost";
    $dbuser = "root"; // default username for XAMPP
    $dbpass = ""; // default password for XAMPP (leave empty)
    $dbname = "we_lab"; // your database name

    // Create connection
    $con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Insert Student
    if (isset($_POST['insert'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $course = $_POST['course'];

        $insertQuery = "INSERT INTO student (ID, Name, Age, Course) VALUES ('$id', '$name', '$age', '$course')";
        
        if ($con->query($insertQuery) === TRUE) {
            echo "<script>alert('New student added successfully!');</script>";
        } else {
            echo "<script>alert('Error: {$con->error}');</script>";
        }
    }

    // Delete Student
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $deleteQuery = "DELETE FROM student WHERE ID='$id'";
        if ($con->query($deleteQuery) === TRUE) {
            echo "<script>alert('Student deleted successfully!');</script>";
        } else {
            echo "<script>alert('Error: {$con->error}');</script>";
        }
    }

    // Update Student (Show Update Form)
    $row = null; // Initialize $row to avoid undefined variable warning
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $result = $con->query("SELECT * FROM student WHERE ID='$id'");
        
        // Check if a valid student was found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Fetch data if found
        } else {
            echo "<script>alert('No student found with this ID.');</script>";
        }
    }

    // Update Student (Process Update)
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $course = $_POST['course'];

        $updateQuery = "UPDATE student SET Name='$name', Age='$age', Course='$course' WHERE ID='$id'";
        if ($con->query($updateQuery) === TRUE) {
            echo "<script>alert('Student updated successfully!');</script>";
            // Redirect to clear the form after update
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Error: {$con->error}');</script>";
        }
    }

    // Fetch and display students
    $result = $con->query("SELECT * FROM student");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['ID']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['Age']}</td>
                <td>{$row['Course']}</td>
                <td class='action-buttons'>
                    <a href='?edit={$row['ID']}' class='update-button'>Edit</a>
                    <a href='?delete={$row['ID']}' class='delete-button'>Delete</a>
                </td>
              </tr>";
    }
    ?>
</table>

<?php if (isset($_GET['edit']) && $row): ?>
<div class="form-container">
    <form method="post">
        <h2>Update Student</h2>
        <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
        <input type="text" name="name" placeholder="Name" value="<?php echo $row['Name']; ?>" required>
        <input type="number" name="age" placeholder="Age" value="<?php echo $row['Age']; ?>" required>
        <input type="text" name="course" placeholder="Course" value="<?php echo $row['Course']; ?>" required>
        <input type="submit" name="update" value="Update Student">
    </form>
</div>
<?php endif; ?>

</body>
</html>
