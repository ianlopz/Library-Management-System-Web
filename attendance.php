<?php
session_start();
include('includes/config.php');
include('qr/qrlib.php');

if (isset($_POST['generate_qr'])) {
    $studentId = $_POST['student_id'];
    $qrData = "Student ID: " . $studentId;
    $qrFile = 'qrcodes/' . $studentId . '.png';
    
    QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 4);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>QR Code Generator & Scanner</title>
    <script type="text/javascript" src="js/instascan.min.js"></script>
</head>
<body>
    <h2>Generate QR Code</h2>
    <form method="post">
        <label>Student ID:</label>
        <input type="text" name="student_id" required>
        <button type="submit" name="generate_qr">Generate QR</button>
    </form>
    <?php if (isset($qrFile)) { ?>
        <img src="<?php echo $qrFile; ?>" alt="QR Code">
    <?php } ?>

    <h2>Scan QR Code</h2>
    <video id="preview"></video>
    <form action="CheckInOut.php" method="post">
        <input type="text" name="studentID" id="text" placeholder="Scan QR Code" required>
        <button type="submit">Submit</button>
    </form>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No cameras found');
            }
        }).catch(function (e) {
            console.error(e);
        });

        scanner.addListener('scan', function (c) {
            document.getElementById('text').value = c;
            document.forms[0].submit();
        });
    </script>
</body>
</html>
