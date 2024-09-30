<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['gateway'])) {
    header("location: login.php");
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once "config.php";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["downloadText"])) {
        $zip = new ZipArchive();
        if ($zip->open("file.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $passkey = $_POST["passkey"];
        
            $decryption_iv = '1234567891011121';
            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $decryption_key = "HeapsV";

            $sql = "SELECT * FROM retrieve where username = '" . $_SESSION['username'] . "'";
            $result = $conn->query($sql);

            $data = '';
            while ($row = $result->fetch_assoc()) {
                $data .= "Website: " . $row["website"] . "\n";
                $data .= "Username: " . $row["username1"] . "\n";
                $data .= "Password: " . openssl_decrypt($row["password"], $ciphering, $decryption_key, $options, $decryption_iv) . "\n";
                $data .= "-------------------------\n";
            }

            $textFileName = "passwords.txt";
            file_put_contents($textFileName, $data);

            $zip->setPassword($passkey);
            $zip->addFile($textFileName, basename($textFileName));
            $zip->setEncryptionName(basename($textFileName), ZipArchive::EM_AES_256);
            $zip->close();

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="passwords.zip"');
            header('Content-Length: ' . filesize("file.zip"));

            readfile("file.zip");

            unlink($textFileName);
            unlink("file.zip");

            exit;
        } else {
            echo 'Failed to create the zip file.';
        }
    } elseif (isset($_POST["downloadCSV"])) {
        $passkey = $_POST["passkey"];

    $decryption_iv = '1234567891011121';
    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $decryption_key = "HeapsV";

    $sql = "SELECT * FROM retrieve where username = '" . $_SESSION['username'] . "'";
    $result = $conn->query($sql);

    $data = "Website,Username,Password\n";
    while ($row = $result->fetch_assoc()) {
        $website = $row["website"];
        $username = $row["username1"];
        $password = openssl_decrypt($row["password"], $ciphering, $decryption_key, $options, $decryption_iv);
        $data .= "$website,$username,$password\n";
    }

    $csvFileName = "passwords.csv";
    file_put_contents($csvFileName, $data);

    $zip = new ZipArchive();
    if ($zip->open("file.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        $zip->setPassword($passkey);

        $zip->addFile($csvFileName, basename($csvFileName));
        $zip->setEncryptionName(basename($csvFileName), ZipArchive::EM_AES_256);

        $zip->close();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="file.zip"');
        header('Content-Length: ' . filesize("file.zip"));

        readfile("file.zip");

        unlink($csvFileName);
        unlink("file.zip");

        exit;
    } else {
        echo 'Failed to create the ZIP file.';
    }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        .button-50 {
            appearance: button;
            background-color: #000;
            background-image: none;
            border: 1px solid #000;
            border-radius: 4px;
            box-shadow: #fff 4px 4px 0 0,#000 4px 4px 0 1px;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-family: ITCAvantGardeStd-Bk,Arial,sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            margin: 0 5px 10px 0;
            overflow: visible;
            padding: 12px 40px;
            text-align: center;
            text-transform: none;
            touch-action: manipulation;
            user-select: none;
            -webkit-user-select: none;
            vertical-align: middle;
            white-space: nowrap;
        }

        .button-50:focus {
            text-decoration: none;
        }

        .button-50:hover {
            text-decoration: none;
        }

        .button-50:active {
            box-shadow: rgba(0, 0, 0, .125) 0 3px 5px inset;
            outline: 0;
        }

        .button-50:not([disabled]):active {
            box-shadow: #fff 2px 2px 0 0, #000 2px 2px 0 1px;
            transform: translate(2px, 2px);
        }

        @media (min-width: 768px) {
            .button-50 {
                padding: 12px 50px;
            }
        }
    </style>
    <title>Retrieve</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<body style="margin: 50px;">
    <h1>List of Passwords</h1>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th>Website</th>
                <th>Username</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cpass = $row["password"];
            $decryption_iv = '1234567891011121';
            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $decryption_key = "HeapsV";

            $sql = "SELECT * FROM retrieve where username = '" . $_SESSION['username'] . "'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["website"] . "</td>
                    <td>" . $row["username1"] . "</td>
                    <td>" . openssl_decrypt($row["password"], $ciphering, $decryption_key, $options, $decryption_iv) . "</td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='update.php?sid=$row[sid]&website=$row[website]&username1=$row[username1]'>Update</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <form method="POST" enctype="multipart/form-data">
        <input style="width: 100%; padding: 12px 20px; margin: 8px 0; box-sizing: border-box; border: none;border-bottom: 2px solid black;" type="text" name="passkey" placeholder="Enter Passkey" required /><br>
        <center>
            <input type="submit" class="button-50" role="button" name="downloadText" value="Download as Text" />
            <input type="submit" class="button-50" role="button" name="downloadCSV" value="Download as CSV" />
        </center>
    </form>
</body>
</html>
