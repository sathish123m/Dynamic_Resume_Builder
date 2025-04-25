<?php
require('fpdf.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resume_generator";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = 'uploads/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    } else {
        $photo = null; 
    }

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $gender = htmlspecialchars($_POST['gender']);
    $dob = htmlspecialchars($_POST['dob']);
    $objective = htmlspecialchars($_POST['objective']);
    $skills = htmlspecialchars($_POST['skills']);
    $experience = htmlspecialchars($_POST['experience']);
    $education = htmlspecialchars($_POST['education']);
    $projects = htmlspecialchars($_POST['projects']);
    $references = htmlspecialchars($_POST['references']);
    $linkedin = htmlspecialchars($_POST['linkedin']);
    $github = htmlspecialchars($_POST['github']);
    $languages = htmlspecialchars($_POST['languages']);
    $certifications = htmlspecialchars($_POST['certifications']);
    $hobbies = htmlspecialchars($_POST['hobbies']);
    $address = htmlspecialchars($_POST['address']);

    $stmt = $conn->prepare("INSERT INTO resumes (name, email, phone, gender, dob, objective, skills, experience, education, projects, `references`, linkedin, github, languages, certifications, hobbies, address, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssssss", $name, $email, $phone, $gender, $dob, $objective, $skills, $experience, $education, $projects, $references, $linkedin, $github, $languages, $certifications, $hobbies, $address, $photo);

    if ($stmt->execute()) {
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetMargins(10, 10, 10);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 8, 'Professional Resume', 0, 1, 'C');
        $pdf->Ln(5);

        if ($photo) {
            list($width, $height) = getimagesize($photo);
            $maxWidth = 40; // Increased width
            $maxHeight = 40; // Increased height
            $aspectRatio = $width / $height;

            if ($width > $height) {
                $newWidth = $maxWidth;
                $newHeight = $maxWidth / $aspectRatio;
            } else {
                $newHeight = $maxHeight;
                $newWidth = $maxHeight * $aspectRatio;
            }

            $pdf->Image($photo, 170, 10, $newWidth, $newHeight); // Adjusted position for larger photo
        }

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(95, 6, "Name: $name", 0, 0);
        $pdf->Cell(95, 6, "Gender: $gender", 0, 1);
        $pdf->Cell(95, 6, "Date of Birth: $dob", 0, 0);
        $pdf->Cell(95, 6, "Phone: $phone", 0, 1);
        $pdf->Cell(95, 6, "Email: $email", 0, 0);
        $pdf->Cell(95, 6, "LinkedIn: $linkedin", 0, 1);
        $pdf->Cell(95, 6, "GitHub: $github", 0, 0);
        $pdf->Cell(95, 6, "Address: $address", 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Objective:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $objective);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Skills:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $skills);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Experience:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $experience);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Education:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $education);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Projects:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $projects);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'References:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $references);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Languages Known:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $languages);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Certifications:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $certifications);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 6, 'Hobbies/Interests:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, $hobbies);
        $pdf->Ln(3);

        $pdf->Output('F', 'resume.pdf');  
        $pdf->Output('D', 'resume.pdf'); 
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Generator</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #444;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        header {
            width: 100%;
            text-align: center;
            padding: 20px 0;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .home-button:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
            outline: none;
        }

        textarea {
            resize: none;
        }

        button {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            width: 100%;
        }

        button:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        footer {
            text-align: center;
            margin-top: 20px;
            color: #fff;
            font-size: 14px;
        }

        footer a {
            color: #fff;
            text-decoration: underline;
        }

        footer a:hover {
            color: #ddd;
        }
    </style>
</head>
<body>
    <header>Resume Generator</header>
    <a href="index.html" class="home-button">Home</a>
    <div class="container">
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>
            </select>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>

            <label for="photo">Upload Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*">

            <label for="objective">Objective:</label>
            <textarea id="objective" name="objective" rows="4" required></textarea>

            <label for="skills">Skills:</label>
            <textarea id="skills" name="skills" rows="4" required></textarea>

            <label for="experience">Experience:</label>
            <textarea id="experience" name="experience" rows="4" required></textarea>

            <label for="education">Education:</label>
            <textarea id="education" name="education" rows="4" required></textarea>

            <label for="projects">Projects:</label>
            <textarea id="projects" name="projects" rows="4" required></textarea>

            <label for="references">References:</label>
            <textarea id="references" name="references" rows="4" required></textarea>

            <label for="linkedin">LinkedIn Profile:</label>
            <input type="text" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/your-profile">

            <label for="github">GitHub Profile:</label>
            <input type="text" id="github" name="github" placeholder="https://github.com/your-username">

            <label for="languages">Languages Known:</label>
            <textarea id="languages" name="languages" rows="2" placeholder="E.g., English, Spanish, French"></textarea>

            <label for="certifications">Certifications:</label>
            <textarea id="certifications" name="certifications" rows="4" placeholder="E.g., AWS Certified, PMP"></textarea>

            <label for="hobbies">Hobbies/Interests:</label>
            <textarea id="hobbies" name="hobbies" rows="4" placeholder="E.g., Reading, Traveling, Coding"></textarea>

            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" placeholder="Your full address"></textarea>

            <label for="template">Select Template:</label>
            <select id="template" name="template" required>
                <option value="template1">Simple Design</option>
                <option value="template2">Modern Design</option>
            </select>

            <button type="submit">Generate Resume</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2025 Resume Generator. All rights reserved.</p>
    </footer>
</body>
</html>