<?php
require_once '../config/config.php';

try {
    // PDO bağlantısı
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    echo "Database connected successfully!<br>";

    // Kullanıcıları seed et
    $checkUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($checkUsers == 0) {
        $pdo->exec("
            INSERT INTO users (name, email, password, role) VALUES
            ('Admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin'),
            ('Guest', 'guest@example.com', '" . password_hash('guest123', PASSWORD_DEFAULT) . "', 'guest');
        ");
        echo "Users seeded successfully!<br>";
    } else {
        echo "Users already exist. Skipping users seed...<br>";
    }

    // Rooms seed page
    $checkRooms = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
    if ($checkRooms == 0) {
        $pdo->exec("
            INSERT INTO rooms (name, type, capacity, status) VALUES
            ('Room 101', 'Single', 1, 'available'),
            ('Room 102', 'Double', 2, 'available'),
            ('Suite 201', 'Suite', 4, 'available');
        ");
        echo "Rooms seeded successfully!<br>";
    } else {
        echo "Rooms already exist. Skipping rooms seed...<br>";
    }

    // Bookings seed page
    $checkBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    if ($checkBookings == 0) {
        $pdo->exec("
            INSERT INTO bookings (user_id, room_id, check_in, check_out, status) VALUES
            (1, 1, '2024-12-01', '2024-12-05', 'booked'),
            (2, 2, '2024-12-10', '2024-12-12', 'booked');
        ");
        echo "Bookings seeded successfully!<br>";
    } else {
        echo "Bookings already exist. Skipping bookings seed...<br>";
    }

    // Reviews seed page
    $checkReviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    if ($checkReviews == 0) {
        $pdo->exec("
            INSERT INTO reviews (user_id, room_id, rating, comment) VALUES
            (2, 1, 5, 'Amazing stay! Highly recommended.'),
            (1, 3, 4, 'Spacious and luxurious, but a bit pricey.');
        ");
        echo "Reviews seeded successfully!<br>";
    } else {
        echo "Reviews already exist. Skipping reviews seed...<br>";
    }

      // Guest Users seed
        $checkGuestUsers = $pdo->query("SELECT COUNT(*) FROM guest_users")->fetchColumn();
        if ($checkGuestUsers == 0) {
            $pdo->exec("
                INSERT INTO guest_users (name, email, password, role) VALUES
                ('John Doe', 'john.doe@example.com', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'guest'),
                ('Jane Smith', 'jane.smith@example.com', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'guest');
            ");
            echo "Guest users seeded successfully!<br>";
        } else {
            echo "Guest users already exist. Skipping guest users seed...<br>";
        }



    // Seed Homepage Images
    $images = [
        '/images/home/1.jpeg',
        '/images/home/2.jpeg',
        '/images/home/3.jpeg',
        '/images/home/4.jpeg',
        '/images/home/5.jpg',
        '/images/home/6.jpg'
    ];

    foreach ($images as $image) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM homepage_images WHERE image_path = :image_path");
        $stmt->execute([':image_path' => $image]);

        if ($stmt->fetchColumn() == 0) {
            $insertStmt = $pdo->prepare("INSERT INTO homepage_images (image_path) VALUES (:image_path)");
            $insertStmt->execute([':image_path' => $image]);
            echo "Inserted $image into homepage_images.<br>";
        } else {
            echo "$image already exists. Skipping...<br>";
        }
    }

    // **Seed Settings**
    $defaultSettings = [
        ['name' => 'site_name', 'value' => 'Uia Motell'],
        ['name' => 'admin_email', 'value' => 'admin@uia.com']
    ];

    foreach ($defaultSettings as $setting) {
        $stmt = $pdo->prepare("
            INSERT INTO settings (name, value) 
            VALUES (:name, :value) 
            ON DUPLICATE KEY UPDATE value = VALUES(value)
        ");
        $stmt->execute([
            ':name' => $setting['name'],
            ':value' => $setting['value']
        ]);
        echo "Inserted or updated {$setting['name']} in settings.<br>";
    }

      // Seed Shutdown Setting**
    $defaultSettings[] = ['name' => 'shutdown', 'value' => '0'];

    foreach ($defaultSettings as $setting) {
        $stmt = $pdo->prepare("
                    INSERT INTO settings (name, value) 
                    VALUES (:name, :value) 
                    ON DUPLICATE KEY UPDATE value = VALUES(value)
                ");
        $stmt->execute([
            ':name' => $setting['name'],
            ':value' => $setting['value']
        ]);
    }
    echo "Shutdown setting seeded successfully.<br>";


    echo "Seeding completed successfully!";
} catch (PDOException $e) {
    echo "Seeding failed: " . $e->getMessage();
}
