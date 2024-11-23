  <!-- //* settings page for admin -->

  <?php
    session_start();

    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    require_once '../config/config.php';

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    // Save settings to the database
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $site_name = $_POST['site_name'];
        $admin_email = $_POST['admin_email'];

        $stmt = $pdo->prepare("
        INSERT INTO settings (name, value) 
        VALUES ('site_name', :site_name), ('admin_email', :admin_email)
        ON DUPLICATE KEY UPDATE value = VALUES(value)
    ");
        $stmt->execute([
            ':site_name' => $site_name,
            ':admin_email' => $admin_email
        ]);

        $_SESSION['message'] = "Settings updated successfully!";
        $_SESSION['message_type'] = 'success';

        header('Location: settings.php');
        exit;
    }


    // Load settings from the database
    $stmt = $pdo->query("SELECT name, value FROM settings");
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Edit Site Settings</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>
      <div class="container mt-5">
          <h1>Edit Site Settings</h1>

          <!-- Toast Notification -->
          <?php if (isset($_SESSION['message'])): ?>
              <div class="toast-container position-fixed top-0 end-0 p-3">
                  <div class="toast show text-bg-<?= $_SESSION['message_type'] ?>" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="d-flex">
                          <div class="toast-body">
                              <?= $_SESSION['message'] ?>
                          </div>
                          <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                  </div>
              </div>
              <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
          <?php endif; ?>

          <!-- Settings Form -->
          <form method="POST" class="mt-4">
              <div class="mb-3">
                  <label for="site_name" class="form-label">Site Name</label>
                  <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label for="admin_email" class="form-label">Admin Email</label>
                  <input type="email" name="admin_email" value="<?= htmlspecialchars($settings['admin_email'] ?? '') ?>" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-warning">Save Settings</button>
          </form>


          <div class="card border-0 shadow-none mb-4" style="min-width: 100%;">
              <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                      <h4 class="card-title m-0">Shutdown Website</h4>
                      <div class="form-check form-switch">
                          <input
                              onchange="toggleShutdown(this.checked)"
                              class="form-check-input"
                              type="checkbox"
                              id="shutdown_toggle"
                              <?= isset($settings['shutdown']) && $settings['shutdown'] == 1 ? 'checked' : '' ?>>
                      </div>
                  </div>
                  <p class="card-text">
                      No customers will be allowed to book hotel rooms when the site is shut down.
                  </p>
              </div>
          </div>



      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script>
          // Automatically hide toast after 3 seconds
          const toastEl = document.querySelector('.toast');
          if (toastEl) {
              setTimeout(() => {
                  toastEl.classList.remove('show');
              }, 2000);
          }
      </script>


      <script>
          function toggleShutdown(isChecked) {
              fetch('update_shutdown.php', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({
                          shutdown: isChecked ? 1 : 0
                      })
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          alert('Shutdown setting updated successfully!');
                      } else {
                          alert('Failed to update shutdown setting!');
                      }
                  });
          }
      </script>
  </body>

  </html>