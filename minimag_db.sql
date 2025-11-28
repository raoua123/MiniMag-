-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 11:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minimag_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `reading_time` int(11) DEFAULT NULL,
  `publication_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'published'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `excerpt`, `content`, `image_url`, `category`, `reading_time`, `publication_date`, `status`) VALUES
(1, 'Créez votre coin créatif', 'Des astuces simples pour rendre votre bureau plus joyeux et productif.', '<h2>Transformez votre espace de travail</h2><p>Créez un environnement inspirant pour booster votre créativité. Organisez votre espace avec des éléments qui vous motivent et vous inspirent.</p><h3>Conseils pratiques</h3><ul><li>Choisissez un éclairage adapté</li><li>Ajoutez des plantes vertes</li><li>Personnalisez votre décoration</li></ul>', 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800&h=600&fit=crop', 'Design', 5, '2025-11-28 19:34:53', 'published'),
(2, 'Découvrez le bonheur simple', 'Apprenez à apprécier les petites choses de la vie quotidienne.', '<h2>Le bonheur dans les détails</h2><p>Redécouvrez la beauté des moments simples du quotidien. Le bonheur se cache souvent là où on ne l\'attend pas.</p><h3>Exercices pratiques</h3><ul><li>Tenir un journal de gratitude</li><li>Pratiquer la pleine conscience</li><li>Prendre le temps de respirer</li></ul>', 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=800&h=600&fit=crop', 'Lifestyle', 4, '2025-11-28 19:34:53', 'published'),
(3, 'Snacks créatifs en 10min', 'Des idées de snacks rapides, sains et délicieux.', '<h2>Snacks express et healthy</h2><p>Des recettes simples et rapides pour vos petites faims. Parfait pour les pauses café ou les envies sucrées.</p><h3>Recettes favorites</h3><ul><li>Toast à l\'avocat et graines</li><li>Yogourt aux fruits et granola</li><li>Boules d\'énergie maison</li></ul>', 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&h=600&fit=crop', 'Food', 3, '2025-11-28 19:34:53', 'published'),
(4, '5 astuces pour rendre tes études plus fun', 'Découvre comment transformer tes sessions de révision en moments plus légers et motivants grâce à quelques habitudes simples.', '<h2>1. Transforme ton espace de travail</h2> <p>Commence par organiser ton bureau comme un petit coin cosy. Ajoute une lampe chaleureuse, quelques stickers, et une playlist douce en fond. Un environnement agréable aide à rester concentré plus longtemps.</p> <h2>2. Utilise la technique du pomodoro… version fun</h2> <p>Au lieu de simples sessions de 25 minutes, crée des “mini-missions”. Pendant chaque session, fixe-toi un objectif précis, comme “finir 5 exercices” ou “résumer un chapitre”, puis accorde-toi une mini-récompense à la pause.</p> <h2>3. Gamifie ta révision</h2> <p>Crée un système de points: chaque exercice fait = 10 points, chaque chapitre relu = 20 points. À la fin de la journée, échange tes points contre une récompense: un épisode de série, un snack, ou 30 minutes de scroll tranquille.</p> <h2>4. Étudie avec un ami… même en ligne</h2> <p>Les sessions en duo, avec un ami sérieux, rendent la révision moins lourde. Fixez-vous un planning commun, allumez la caméra ou l’audio, et travaillez chacun sur vos tâches en vous motivant mutuellement.</p> <h2>5. Varie les supports</h2> <p>Alterner entre cours écrits, vidéos, quiz et fiches récap aide à mieux retenir. Ton cerveau s’ennuie moins et tu as l’impression de “jouer” avec la matière plutôt que de la subir.</p> <p><em>Le secret n’est pas d’étudier plus, mais d’étudier mieux… et avec un peu plus de fun ✨</em></p>', 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1200&q=80', 'Life', 4, '2025-11-28 21:14:47', 'published');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `author_name`, `author_email`, `content`, `created_at`, `status`) VALUES
(1, 2, 'raoua', NULL, 'bon article', '2025-11-28 20:59:52', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `article_id`, `ip_address`, `created_at`) VALUES
(1, 2, NULL, '2025-11-28 20:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$/q.KTlHOtcdUOD7XK3h2TuGMYZw3qBne6ue2VNWZ6Lyq8L37RY94a', 'admin', '2025-11-28 20:09:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`article_id`,`ip_address`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
