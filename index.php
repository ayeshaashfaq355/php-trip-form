<?php
$insert = false; // by default no record inserted

if (isset($_POST['name'])) {
    // 1️⃣ Database connection
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "trip"; // database name

    $con = mysqli_connect($server, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // 2️⃣ Data sanitization
    $name   = htmlspecialchars(trim($_POST['name']));
    $age    = (int) $_POST['age'];
    $gender = htmlspecialchars(trim($_POST['gender']));
    $email  = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone  = preg_replace("/[^0-9]/", "", $_POST['phone']);
    $desc   = htmlspecialchars(trim($_POST['desc']));

      if ($age <= 0 || $age > 120) {
        die("❌ Invalid age! Please enter a valid number between 1 and 120.");
    }


    // 3️⃣ Prepare SQL query with placeholders (?)
    $stmt = $con->prepare("INSERT INTO trip (name, age, gender, email, phone, other, dt) 
                           VALUES (?, ?, ?, ?, ?, ?, current_timestamp())");

    // 4️⃣ Bind variables to placeholders
    // "sissss" → s = string, i = integer
    $stmt->bind_param("sissss", $name, $age, $gender, $email, $phone, $desc);

    // 5️⃣ Execute query
    if ($stmt->execute()) {
        $insert = true; // record successfully inserted
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    // 6️⃣ Close statement & connection
    $stmt->close();
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Travel Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bahria University US Trip Form</h1>
        <p>Please fill out the form below to confirm your spot for this exciting trip!</p>

        <?php if ($insert): ?>
            <p class="submitmsg">Thank you for joining. We look forward to seeing you on our trip.</p>
        <?php endif; ?>

      <form action="index.php" method="post">
    <input type="text" name="name" placeholder="Enter your full name" 
           pattern="[A-Za-z ]{2,50}" title="Name should only contain letters and spaces, 2-50 characters" required>

    <input type="number" name="age" placeholder="Enter your age" 
           min="1" max="120" title="Age must be between 1 and 120" required>
    
    <select name="gender" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <input type="email" name="email" placeholder="Enter your email" 
           title="Enter a valid email address" required>

    <input type="tel" name="phone" placeholder="Enter your phone number" 
           pattern="[0-9]{11}" title="Phone number must be exactly 11 digits" required>

    <textarea name="desc" rows="4" placeholder="Any special instructions or notes..."
              maxlength="200" title="Max 200 characters"></textarea>

    <div class="form-buttons">
        <button type="submit" class="btn submit-btn">Submit</button>
        <button type="reset" class="btn reset-btn">Reset</button>
    </div>
    
</form>

   
</body>
</html>
