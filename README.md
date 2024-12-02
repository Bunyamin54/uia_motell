# uia_motell

# php_booking-prosjekt

Room Booking System

This project is a dynamic web application for managing room bookings in a small motel with a total of 25 rooms.

Project Description

RoomBookingSystem is a web-based application that enables users to book rooms of various types in a motel. Built with PHP and Bootstrap, this system aims to efficiently handle room reservations for both guests and administrators.

Features
Room Types and Capacity

Offers at least three room types: Single Room, Junior Suite, and Family Suite.
Each room type has predefined capacity limits for the number of adults and children.

Room Management (Administrator Only)

Administrators can:
Add, update, or delete room types.
Set rooms as unavailable for specific periods (e.g., maintenance or special events).
Manage room availability through a dedicated administrative interface.

Room Booking for Guests
Guests can:
Search for available rooms by specifying check-in and check-out dates.
Provide details such as the number of adults and children.
Specify optional preferences like floor level or proximity to elevators.

Loyalty Program and Guest Profile
Guests can:
View booking history and retrieve receipts.
Update personal details and save preferences for future bookings.

Technologies Used

Backend: PHP 8.2
Frontend: HTML5, CSS3 (Bootstrap), JavaScript (jQuery), and Toastify for notifications.
Database: MySQL (managed via phpMyAdmin), used for storing user and booking data.

Installation Requirements

Server
A web server such as Apache, with phpMyAdmin for database management.

PHP version 8.0 or higher.

Database
MySQL or MariaDB to handle data storage.

# Room Booking System

```

UIA_MOTELL/
├── admin/
│   ├── ajax_rooms.php            
│   ├── available_rooms.php       
│   ├── booking.php            
│   ├── confirmation.php          
│   ├── dashboard.php            
│   ├── home.php                 
│   ├── login.php                
│   ├── logout.php               
│   ├── payment.php              
│   ├── rooms.php                
│   ├── settings.php             
│   ├── update_shutdown.php      
├── config/
│   ├── config.php                
│   ├── db.php                   
├── inc/
│   ├── contact.php               
│   ├── footer.php               
│   ├── home.php                 
│   ├── login_register.php      
│   ├── navbar.php                
│   ├── reviews.php               
│   ├── rooms.php               
├── migrations/
│   ├── run-mig.php              
│   ├── run-seed.php            
├── public/
│   ├── images/                  
│   │   ├── home/                 
│   │   ├── reviews/              
│   │   ├── rooms/               
│   ├── index.php                 
│   ├── scripts.js                
│   ├── styles.css                
├── README.md                   
