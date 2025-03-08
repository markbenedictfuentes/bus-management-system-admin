<?php
session_start();
include 'db_connect.php';

$query = "SELECT * FROM announcements ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<?php include '../../include/header2.php'; ?>

<body class="h-screen flex bg-gray-100">
  <?php include '../../include/sidebar2.php'; ?>
  <div class="flex-1 flex flex-col">
    <?php include '../../include/topbar2.php'; ?>
    <div class="flex-1 overflow-y-auto p-4">
      <header class="header">
        <div class="container">
          <h1><i class="fas fa-bullhorn"></i> Announcement Dashboard</h1>
        </div>
      </header>

      <div class="announcement-container">
        <div class="announcement-grid">
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="announcement-card">
              <div class="announcement-header">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
              </div>
              <div class="announcement-body">
                <p><?php echo htmlspecialchars($row['content']); ?></p>
                <?php if(!empty($row['image'])): ?>
                  <?php $imagePath = "/private/admin/action/uploads/announcements/" . $row['image']; ?>
                  <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Announcement Image">
                <?php endif; ?>
              </div>
              </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
