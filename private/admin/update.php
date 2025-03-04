<?php
session_start();
include 'db_connect.php';

// Suriin kung may id na ipinasa sa URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: announcement.php");
    exit();
}

$id = intval($_GET['id']);

// Kunin ang kasalukuyang announcement
$query = "SELECT * FROM announcements WHERE id = $id";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Announcement not found.";
    header("Location: announcement.php");
    exit();
}
$announcement = mysqli_fetch_assoc($result);

// Kapag nagsubmit na ang form, i-proseso ang update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // I-check kung may bagong image na in-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Kung may lumang image, tanggalin ito
        if (!empty($announcement['image'])) {
            $oldImage = "action/uploads/announcements/" . $announcement['image'];
            if (file_exists($oldImage)) {
                unlink($oldImage);
            }
        }
        // I-upload ang bagong image
        $targetDir = "action/uploads/announcements/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Payagan lamang ang ilang file types
        $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image = $fileName;
            } else {
                $_SESSION['error'] = "Error uploading image.";
                header("Location: announcement.php?id=".$id);
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid image file type.";
            header("Location: announcement.php?id=".$id);
            exit();
        }
    } else {
        // Kung walang bagong image, panatilihin ang kasalukuyang image
        $image = $announcement['image'];
    }

    $updateQuery = "UPDATE announcements SET title='$title', content='$content', image='$image' WHERE id=$id";
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['success'] = "Announcement updated successfully.";
        header("Location: announcement.php"); // Redirect sa announcement.php kapag matagumpay
        exit();
    } else {
        $_SESSION['error'] = "Failed to update announcement.";
        header("Location: update.php?id=".$id);
        exit();
    }
}
?>

<?php include '../../include/header2.php'; ?>
<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar.php'; ?>
    <div class="flex-1 overflow-y-auto p-4">
      <header class="header">
        <div class="container">
          <h1><i class="fas fa-edit"></i> Update Announcement</h1>
        </div>
      </header>

      <?php if(isset($_SESSION['error'])): ?>
        <div class="error-message">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
      <?php if(isset($_SESSION['success'])): ?>
        <div class="success-message">
          <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <form action="update.php?id=<?php echo $announcement['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title"><i class="fas fa-heading"></i> Announcement Title</label>
          <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>
        </div>
        <div class="form-group">
          <label for="content"><i class="fas fa-align-left"></i> Content</label>
          <textarea id="content" name="content" rows="4" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
        </div>
        <div class="form-group">
          <label for="image"><i class="fas fa-image"></i> Upload Image (Leave blank to keep current image)</label>
          <div class="file-input-container">
            <input type="file" id="image" name="image" accept="image/*">
            <div class="file-input-label"><i class="fas fa-cloud-upload-alt"></i> Choose a file</div>
          </div>
          <div id="file-name" class="file-name">
            <?php if(!empty($announcement['image'])): ?>
              Current Image: <?php echo htmlspecialchars($announcement['image']); ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="submit-button">
            <i class="fas fa-paper-plane"></i> Update Announcement
          </button>
        </div>
      </form>
    </div>
  </div>
  <!-- Optional: Isama ang katulad na JS para sa file input gaya ng nasa main page -->
</body>
</html>
