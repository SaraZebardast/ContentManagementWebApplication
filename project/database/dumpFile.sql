-- phpMyAdmin SQL Dump
-- version 4.9.2
-- Generation Time: Dec 12, 2024 at 02:43 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
    `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
    `type` enum('admin','content_creator','editor') COLLATE utf8mb4_turkish_ci NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `type`) VALUES
                                                                        (1, 'admin', 'admin123', 'admin@university.edu', 'admin'),
                                                                        (2, 'student_council', 'sc123', 'studentcouncil@university.edu', 'content_creator'),
                                                                        (3, 'faculty_review', 'fr123', 'facultyreview@university.edu', 'editor'),
                                                                        (4, 'sports_dept', 'sports123', 'sports@university.edu', 'content_creator'),
                                                                        (5, 'academic_office', 'academic123', 'academic@university.edu', 'editor'),
                                                                        (6, 'it_admin', 'it123', 'it@university.edu', 'admin'),
                                                                        (7, 'library_staff', 'lib123', 'library@university.edu', 'content_creator'),
                                                                        (8, 'dept_science', 'science123', 'science@university.edu', 'content_creator'),
                                                                        (9, 'student_affairs', 'sa123', 'studentaffairs@university.edu', 'editor'),
                                                                        (10, 'events_team', 'events123', 'events@university.edu', 'content_creator');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
    `creator_id` int(11) NOT NULL,
    `title` varchar(200) COLLATE utf8mb4_turkish_ci NOT NULL,
    `description` text COLLATE utf8mb4_turkish_ci NOT NULL,
    `image_path` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
    `img_category` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
    `status` enum('pending','approved','rejected') COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `creator_id` (`creator_id`),
    CONSTRAINT `content_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `creator_id`, `title`, `description`, `image_path`, `img_category`, `status`, `created_at`) VALUES
                                                                                                                             (1, 2, 'Annual Science Fair 2025', 'Join us for the biggest science event of the year! Present your projects and win exciting prizes. Registration deadline: January 15, 2025. Open to all departments.', './posters/science_fair.jpg', 'events', 'approved', '2024-12-01 10:00:00'),
                                                                                                                             (2, 4, 'Basketball Tournament Sign-ups', 'Inter-department basketball tournament starting February 2025. Form your teams now! Maximum 8 players per team. Registration fee: $20 per team.', './posters/basketball_signup.jpg', 'sports', 'approved', '2024-12-01 11:30:00'),
                                                                                                                             (3, 7, 'Extended Library Hours', 'Library will remain open 24/7 during final exam week (Dec 15-22). Additional study rooms available on reservation basis.', './posters/library_hours.jpg', 'announcement', 'approved', '2024-12-02 09:15:00'),
                                                                                                                             (4, 8, 'Guest Lecture: AI Ethics', 'Distinguished Prof. Sarah Johnson discussing "Ethics in AI" on Dec 20, 2024. Venue: Main Auditorium, 3:00 PM. All students welcome.', './posters/guest_lecture.jpg', 'academic', 'pending', '2024-12-02 14:20:00'),
                                                                                                                             (5, 2, 'Student Council Elections', 'Cast your vote for Student Council 2025! Voting opens Dec 18-20. Student ID required. Make your voice heard!', './posters/election.jpg', 'announcement', 'approved', '2024-12-03 16:45:00'),
                                                                                                                             (6, 10, 'Winter Music Festival', 'Three days of music, food, and fun! December 22-24. Featured performances by university bands. Free entry with student ID.', './posters/music_fest.jpg', 'events', 'approved', '2024-12-03 18:30:00'),
                                                                                                                             (7, 7, 'New Database Subscriptions', 'Library now offers access to Scientific Journal Database and Historical Archives. Workshop on usage: Dec 16, 2:00 PM.', './posters/database_access.jpg', 'academic', 'pending', '2024-12-04 08:00:00'),
                                                                                                                             (8, 4, 'Yoga Classes Schedule', 'Free yoga classes every Monday and Wednesday, 7:00 AM at the Sports Complex. Bring your own mat!', './posters/yoga_classes.jpg', 'sports', 'approved', '2024-12-04 11:20:00'),
                                                                                                                             (9, 8, 'Research Funding Available', 'Applications open for undergraduate research grants. Deadline: January 10, 2025. Maximum funding: $5000 per project.', './posters/research_funding.jpg', 'academic', 'approved', '2024-12-05 13:40:00'),
                                                                                                                             (10, 10, 'Career Fair 2025', 'Over 50 companies recruiting! January 25, 2025, 9:00 AM - 4:00 PM. Bring your resume. Professional dress required.', './posters/career_fair.jpg', 'events', 'pending', '2024-12-05 15:10:00'),
                                                                                                                             (11, 2, 'Campus Sustainability Initiative', 'Join the Green Campus Movement! Workshop on recycling and sustainability. December 19, 1:00 PM, Room 301.', './posters/sustainability.jpg', 'announcement', 'approved', '2024-12-06 09:30:00'),
                                                                                                                             (12, 4, 'Swimming Pool Maintenance', 'Pool closed for maintenance Dec 25-27. Regular schedule resumes Dec 28.', './posters/pool_maintenance.jpg', 'sports', 'approved', '2024-12-06 14:15:00'),
                                                                                                                             (13, 7, 'Book Donation Drive', 'Donate your used textbooks! Collection point: Library entrance. Dec 15-30. Help make education accessible to all.', './posters/book_donation.jpg', 'announcement', 'pending', '2024-12-07 20:00:00'),
                                                                                                                             (14, 8, 'Chemistry Lab Safety Training', 'Mandatory safety training for all chemistry students. Dec 17, 10:00 AM. Certification provided.', './posters/lab_safety.jpg', 'academic', 'approved', '2024-12-07 22:30:00'),
                                                                                                                             (15, 10, 'Photography Contest', 'Theme: "Campus Life". Submit entries by Jan 5, 2025. First prize: New DSLR camera!', './posters/photo_contest.jpg', 'events', 'approved', '2024-12-08 12:00:00'),
                                                                                                                             (16, 2, 'Mental Health Awareness Week', 'Free counseling sessions, workshops, and support groups. Dec 18-22. Your mental health matters!', './posters/mental_health.jpg', 'announcement', 'approved', '2024-12-08 16:45:00'),
                                                                                                                             (17, 4, 'Intramural Sports Schedule', 'Updated schedule for winter sports. Basketball, volleyball, and badminton courts available for booking.', './posters/intramural.jpg', 'sports', 'pending', '2024-12-09 10:20:00'),
                                                                                                                             (18, 7, 'New Study Room Booking System', 'Online booking system launched for library study rooms. Maximum 4 hours per booking.', './posters/study_rooms.jpg', 'announcement', 'approved', '2024-12-09 13:50:00'),
                                                                                                                             (19, 8, 'Research Symposium Call', 'Submit your abstracts for Annual Research Symposium. Deadline: Jan 20, 2025. All disciplines welcome.', './posters/symposium.jpg', 'academic', 'approved', '2024-12-10 11:00:00'),
                                                                                                                             (20, 10, 'International Food Festival', 'Celebrate diversity through food! Dec 21, 12:00-3:00 PM, Student Center. Register to set up a food stall.', './posters/food_festival.jpg', 'events', 'pending', '2024-12-10 19:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
    `content_id` int(11) NOT NULL,
    `editor_id` int(11) NOT NULL,
    `comment` text COLLATE utf8mb4_turkish_ci NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `content_id` (`content_id`),
    KEY `editor_id` (`editor_id`),
    CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
    CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`editor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content_id`, `editor_id`, `comment`, `created_at`) VALUES
                                                                                      (1, 1, 3, 'Please add registration link and contact information for queries.', '2024-12-01 10:30:00'),
                                                                                      (2, 2, 5, 'Include information about team uniform requirements.', '2024-12-01 12:00:00'),
                                                                                      (3, 4, 9, 'Add directions to the auditorium and livestream link.', '2024-12-02 09:45:00'),
                                                                                      (4, 7, 5, 'Specify if laptops will be provided for the workshop.', '2024-12-02 15:00:00'),
                                                                                      (5, 10, 3, 'List participating companies and add dress code guidelines.', '2024-12-03 17:00:00'),
                                                                                      (6, 13, 5, 'Include list of most-needed textbooks and subjects.', '2024-12-03 19:00:00'),
                                                                                      (7, 14, 9, 'Add information about make-up sessions if available.', '2024-12-04 08:30:00'),
                                                                                      (8, 15, 3, 'Specify image format requirements and submission process.', '2024-12-04 12:00:00'),
                                                                                      (9, 17, 5, 'Add equipment rental information and costs.', '2024-12-05 14:00:00'),
                                                                                      (10, 20, 9, 'Include food safety guidelines for participants.', '2024-12-05 15:45:00');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;