-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 07:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blogger`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `title` varchar(1000) NOT NULL,
  `descr` text NOT NULL,
  `userid` varchar(1000) NOT NULL,
  `content` longtext NOT NULL,
  `category` varchar(1000) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`title`, `descr`, `userid`, `content`, `category`, `id`) VALUES
('Milenge Tumhe bhi', 'A poem about friends', 'Vis', 'Milenge Tumhe Bhi\nJo Badhenge Na Aage Tumhare\n\nChalenge Jo Saath Tumhare\n\n\n\nMilenge Tumhe Bhi\n\nNazro se samajh jaayenge takleef tumhare\n\nUljhan ko Suljha De\n\nAise saathi Tumhare\n\n\n\nMilenge tumhe bhi\n\nBole bina Jo samajh lenge baat tumhari\n\nApno se jo lage \n\nAise yaar tumhare\n\n\n\nDua karta hoon\n\nMilenge tumhe bhi\n\nJinke liye keh pao\n\nYeh hai dost khaas tumhare', 'Poem', 2),
('Remedy', 'poem', 'Vis', 'Laugh to get out of depression\r\n\r\nSmile to leave a joyful impression\r\n\r\nMake others laugh to get happiness\r\n\r\nIt will help them to get out of their dizziness\r\n\r\nTalk about your problems with your dear ones\r\n\r\nAfter that you won\'t be a lonely one\r\n\r\nDon\'t think about the darkness in the box\r\n\r\nGet out and see the brightness outside the box\r\n\r\nA bad event is not an end to your life\r\n\r\nMake it a new beginning of your life\r\n\r\nWish you best luck in your life\r\n', 'Poem', 3),
('Where have all the real people gone ', 'a small poem about real life', 'Vis', 'The world has turned upside down\r\nPeople wear masks of joy\r\nWhich helps them to hide their frown\r\nWhere have all the real people gone\r\n\r\nThe world has turned upside down\r\nThey smile in your presence\r\nTalk ill about you in your absence\r\nWhere have all the real people gone\r\n\r\nThere was a time when everything was good\r\nEvery smile was genuine\r\nNobody was rude\r\n\r\nGone are the days when we used to say\r\n\"How are you today\"\r\n\r\nThe world has turned upside down\r\nWhere have all the real people gone', 'Poem', 4),
('Theatre', 'A story written in an exam', 'Vis', 'Theatre\r\n\r\n\r\n               It was Sunday and I was enjoying the latest movie in the theatre with my family. The movie was being played at a loud volume when slowly the sound started to decrease until there was a pin drop of silence. After that the brightness of the screen reduced and then it turned off.\r\nThe whole theatre now was pitch black.\r\n               It took time until my eyes adjusted to the darkness.I tried to pat on my brother\'s shoulder to call him but I was just moving my hands nowhere. Surprised when I looked the whole row of seats were empty. I was pretty scared when I started hearing someone in a low tone calling my name from the back of my seat.\r\n                 I turned and just saw white eyes without pupils staring at me and the face was of my brother and the next moment it vanished in front of my eyes. In the grim silence, I stood up and saw a figure of a small boy in the darkness. The noise started coming from that figure which started getting clear and scary, it was saying 4, 18,  5, 1, 13. I ran away from there. I stopped and took a sigh of relief when I heard my parent\'s voice coming from the washroom.\r\n                 I went to the washroom but to my surprise, there wasn\'t anyone in the washroom. To think clearly I turned on the nob of the tap and the cold water started touching my hands. I started wondering over the numbers when I started replacing them for the letters in their position in the alphabet. I got the first letter D but the water which was cold when I started washing my face was now warm. I looked in the mirror and saw my face. My face was covered with blood from the tap. But now I had figured out the word it was a DREAM.\r\n            Suddenly a drop of blood fell on my hair and when I looked above a man with a distorted face was on the ceiling and also upright. After I figured out the word I gathered some courage and pulled out that person\'s hair and...then I woke up from this terrible nightmare but I still had a strand of hair in my hand. Right at that my brother yelled me and asked me why did I pull out his hair.\r\n               I didn\'t know whether to laugh or be scared.\r\n', 'Story', 5);

-- --------------------------------------------------------

--
-- Table structure for table `regform`
--

CREATE TABLE `regform` (
  `userid` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phone` int(10) NOT NULL,
  `email` varchar(200) NOT NULL,
  `gender` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regform`
--

INSERT INTO `regform` (`userid`, `password`, `phone`, `email`, `gender`) VALUES
('Vis', 'Vis', 0, '', ''),
('Vish', 'vis', 2147483647, 'vis@g.com', 'male');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regform`
--
ALTER TABLE `regform`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
