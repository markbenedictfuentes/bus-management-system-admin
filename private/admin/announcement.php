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
    <?php include '../../include/topbar.php'; ?>
    <div class="flex-1 overflow-y-auto p-4">
      <header class="header">
        <div class="container">
          <h1><i class="fas fa-bullhorn"></i> Announcement Dashboard</h1>
        </div>
      </header>

      <div class="container button-container">
        <button id="openModal" class="add-button">
          <i class="fas fa-plus-circle"></i> Add Announcement
        </button>
      </div>

      <div id="announcementModal" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2><i class="fas fa-pen-to-square"></i> Add New Announcement</h2>
            <button id="closeModal" class="close-button"><i class="fas fa-times"></i></button>
          </div>
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
          <form action="action/process_announcement.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="title"><i class="fas fa-heading"></i> Announcement Title</label>
              <input type="text" id="title" name="title" placeholder="Enter title" required>
            </div>
            <div class="form-group">
              <label for="content"><i class="fas fa-align-left"></i> Content</label>
              <textarea id="content" name="content" placeholder="Enter announcement content" rows="4" required></textarea>
            </div>
            <div class="form-group">
              <label for="image"><i class="fas fa-image"></i> Upload Image</label>
              <div class="file-input-container">
                <input type="file" id="image" name="image" accept="image/*">
                <div class="file-input-label"><i class="fas fa-cloud-upload-alt"></i> Choose a file</div>
              </div>
              <div id="file-name" class="file-name"></div>
            </div>
            <div class="form-actions">
              <button type="submit" class="submit-button">
                <i class="fas fa-paper-plane"></i> Submit Announcement
              </button>
            </div>
          </form>
        </div>
      </div>

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
                  <?php $imagePath = "action/uploads/announcements/" . $row['image']; ?>
                  <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Announcement Image">
                <?php endif; ?>
              </div>
              <div class="announcement-actions">
              <a href="update.php?id=<?php echo $row['id']; ?>" class="edit-button"><i class="fas fa-edit"></i> Update</a>
              <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-button"><i class="fas fa-trash"></i> Delete</a>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript for Modal, File Input, and SweetAlert Confirmation -->
  <script>
    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modal = document.getElementById('announcementModal');
    const fileInput = document.getElementById('image');
    const fileName = document.getElementById('file-name');
    const fileInputLabel = document.querySelector('.file-input-label');

    openModalBtn.addEventListener('click', () => {
      modal.classList.add('show');
    });

    closeModalBtn.addEventListener('click', () => {
      modal.classList.remove('show');
    });

    window.addEventListener('click', (event) => {
      if (event.target === modal) {
        modal.classList.remove('show');
      }
    });

    fileInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        fileName.textContent = this.files[0].name;
        fileInputLabel.innerHTML = '<i class="fas fa-check"></i> File selected';
        fileInputLabel.classList.add('file-selected');
      } else {
        fileName.textContent = '';
        fileInputLabel.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Choose a file';
        fileInputLabel.classList.remove('file-selected');
      }
    });

    // SweetAlert for Delete
    document.querySelectorAll('.delete-button').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        Swal.fire({
          title: 'Are you sure you want to delete this announcement?',
          text: "This action cannot be undone.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = url;
          }
        });
      });
    });

    // SweetAlert for Update (Optional Confirmation)
    document.querySelectorAll('.edit-button').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        Swal.fire({
          title: 'Proceed to update this announcement?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#aaa',
          confirmButtonText: 'Yes, update it!'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = url;
          }
        });
      });
    });
  </script>
</body>
</html>
