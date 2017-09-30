-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2017 at 03:13 PM
-- Server version: 5.7.11
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tboy9_turbo`
--

-- --------------------------------------------------------

--
-- Table structure for table `quiz_participant`
--

CREATE TABLE `quiz_participant` (
  `participant_id` int(11) NOT NULL,
  `participant_email` varchar(255) NOT NULL,
  `total_time` float DEFAULT '0',
  `total_questions` int(11) DEFAULT '0',
  `questions_answered` varchar(1000) DEFAULT ' ',
  `questions_answered_correctly` int(11) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `average_time` float NOT NULL DEFAULT '0',
  `total_score` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `question_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `options` text NOT NULL,
  `answer` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`question_id`, `question`, `options`, `answer`, `created`) VALUES
  (1, 'Who formed the first political party in Nigeria?', 'Olusegun Obasanjo*Osama Bin Laden*Peter Obi*Herbert Macauly', 'Herbert Macauly', '2017-09-12 11:19:37'),
  (120, 'The first military president in Nigeria was', 'Sir James Robertson.*Alhaji Shehu shagari.*Sir Anthony Richard.*General Ibrahim Babangida.', 'General Ibrahim Babangida.', '2017-09-28 16:45:28'),
  (5, 'What does the eagle in the Nigeria coat of arm represent?', 'Peace*Terror*Agriculture*Strength', 'Strength', '2017-09-13 11:50:07'),
  (4, 'What was the first political party in Nigeria?', 'APC*PDP*NNDP*AD', 'NNDP', '2017-09-13 11:40:59'),
  (109, 'Which of the following pioneered Christian missionary activities in Calabar?', 'Mary Slessor.*William Baike.*Thomas Freeman.*Hope Waddell.', 'Hope Waddell.', '2017-09-28 16:18:13'),
  (110, 'The Premier of the Eastern Region of Nigeria in 1962 was', 'Chief Michael Okpara.*Dr. Nnamdi Azikiwe.*Dr. Akanu Ibiam.*Mr. Ukpabi Asika.', 'Chief Michael Okpara.', '2017-09-28 16:19:20'),
  (84, 'When did Nigeria become a republic?', '1 August 1962*1st October 1963*12 March 1969*Febuary 15 1966', '1st October 1963', '2017-09-27 11:48:26'),
  (9, 'The constitution of the federal republic of Nigeria', 'promotes unity of diversity*allows for the dominance of the minority ethnic groups*concentrates governmental power at on one level of government*ensures the dominance of one political party.', 'promotes unity of diversity', '2017-09-13 12:02:43'),
  (85, 'Which state is Kainji Dam located in?', 'Niger State*Kwara State*Nasarawa State*Kogi State', 'Niger State', '2017-09-27 11:50:34'),
  (119, 'The Nigeria first indigenous Governor General and also the first Ceremonial President was\r\n', 'Dr. Nnamdi Azikwe.*Alhaji Tafawa Balewwa.*Alhaji shehu Shagari.*Chief Olusegun Obasanjo.', 'Dr. Nnamdi Azikwe.', '2017-09-28 16:43:38'),
  (12, 'The six registered political parties in Nigeria in 1982 were', 'UPN, NPN, PPA, PPP, NPP, and NAP*UPN, GNPP, NAP, PRP, NPP, NPN*NPN, UPN, NPP, PRP, PPP NNDP*NPC, GNPP, PRP, UPN, NPP and PPA', 'UPN, GNPP, NAP, PRP, NPP, NPN', '2017-09-13 12:10:42'),
  (13, 'The first general election in Nigeria was held in\r\n', '1933*1952*1955*1959', '1959', '2017-09-13 12:13:22'),
  (14, 'The first military government in Nigeria was headed by', 'General Yakubu Gowon*General Agunyi Ironsi*General M. Mohammed*General O. Obasanjo.', 'General Agunyi Ironsi', '2017-09-13 12:40:42'),
  (15, 'The first governor-general of colonial Nigeria was?\n', 'sir.Hugh Clifford*sir James Robertson*Lord Lugard*sir Ralph  moore.', 'Lord Lugard', '2017-09-13 12:44:43'),
  (115, 'The 1945 General Strike was led by', 'Herbert Macaulay.*Michael Imoudu.*Wahab Goodluck.*Olufunmilayo Ransome-Kuti.', 'Michael Imoudu.', '2017-09-28 16:26:26'),
  (116, 'The colonial officer who was responsible for splitting the Southern Province in to Eastern and Western Provinces was', 'Bernard Bourdillon.*Graeme Thompson.*Hugh Clifford.*Frederick Lugard.', 'Bernard Bourdillon.', '2017-09-28 16:27:47'),
  (98, 'When was slave trade abolished  in Nigeria?', '1935*1987*1833*1955', '1833', '2017-09-27 14:46:36'),
  (107, 'The first newspaper in Nigeria was called?', 'The Tribune.*The Sun.*The Nation.*Iwe Irohin.', 'Iwe Irohin.', '2017-09-28 16:14:51'),
  (108, 'In colonial Nigeria, mining industry was promoted in order to', 'diversify the economy.*engage the railway system.*make Nigeria self-reliant.*maximize exploitation.', 'maximize exploitation.', '2017-09-28 16:16:05'),
  (21, 'The minorities Commission appointed in Nigeria in 1957 recommend that', 'More states should be created in the federation*No more states should created before independence*Nigeria should revert to a unitary structure*the legislature should Legislate for the minority areas', 'More states should be created in the federation', '2017-09-13 14:16:57'),
  (118, 'The ruler that was driven out of Lagos by the British was', 'Oba Akintoye.*Oba Dosumu.*Oba Kosoko.*Oba Adele.', 'Oba Kosoko.', '2017-09-28 16:34:44'),
  (23, 'Africans were first elected to the legislative council in British West Africa in', 'Ghana*Sierra Leone *The Gambia*Nigeria', 'Ghana', '2017-09-13 14:25:33'),
  (25, 'The idea of democracy started with the', 'Romans* Pensions*Greece*Egyptian', 'Greece', '2017-09-13 14:51:16'),
  (117, 'An influential advocate of the amalgamation of the Northern and Southern Protectorates was', 'E.D. Morel*Otunba Payne*John Beecroft*Henry Carr', 'E.D. Morel', '2017-09-28 16:29:02'),
  (28, 'A bill that applies to the whole population and is intended to promote the general welfare is called', ' A private bill*A decree*An Appropriation bill*A public deal', 'An Appropriation bill', '2017-09-13 15:06:47'),
  (30, 'Which of the following made the earliest contact with the Nigerian society?', 'British*Portuguese*French*German', 'Portuguese', '2017-09-13 15:35:07'),
  (31, 'Under the 1963 republican constitution,the president exercised ?', 'judicial power*executive power*nominal power*concurrent powers', 'nominal power', '2017-09-13 15:38:25'),
  (103, 'The Colony and Protectorate of Lagos was amalgamated with the Protectorate of Southern Nigeria in 1906 to form the', 'Colony and Protectorate of Southern Nigeria.*Colony and Protectorate of Nigeria.*Protectorate of Southern Nigeria.*Oil Rivers Protectorate.', 'Colony and Protectorate of Nigeria.', '2017-09-28 16:04:33'),
  (104, 'A reason for the amalgamation of the Northern and Southern protectorates of Nigeria in 1914 was', 'economic expediency.*territorial integration.*political balancing.*religious harmony.', 'economic expediency.', '2017-09-28 16:05:59'),
  (100, 'What was the first TV station in Nigeria?', 'WNTV*DSTV*GoTV*NTA', 'WNTV', '2017-09-28 15:59:32'),
  (101, 'The spread of Islam between the 11th and 14th centuries in Nigeria was largely due to', 'Arab missionary activities.*the waging of jihads.*trade and commerce.*absence of any religion in the area.', 'trade and commerce.', '2017-09-28 16:01:34'),
  (102, 'Armed resistance to British rule in Northern Nigeria ended with the conquest of', 'Kano and Sokoto.*Bida and Kano.*Katsina and Kano.*Bauchi and Katsina.', 'Kano and Sokoto.', '2017-09-28 16:02:42'),
  (37, 'Nigeria is divided into how many geopolitical zones?', 'Four (4) geopolitical Zones*Two (2) geopolitical Zones* Nine (9) geopolitical Zones*Six (6) geopolitical Zones', 'Six (6) geopolitical Zones', '2017-09-14 11:21:11'),
  (38, 'What was the first capital city in Nigeria?', 'Ikeja*Abuja*Calabar*Uyo', 'Calabar', '2017-09-14 11:22:38'),
  (114, 'The objective of the 1934 legislation imposing quotas on imported goods from non-British sources was to', 'develop Nigerian industries.*increase Nigeria\'s Gross National Product.*exclude cheap Japanese textiles.*regulate trade between Nigeria and other countries.', 'exclude cheap Japanese textiles.', '2017-09-28 16:24:48'),
  (41, 'The first Nigeria leader to become chairman organization of african unity was', 'Tafawa balewa*Muritala Muhammed*Yakuba Gowon*Aguiyi Ironsi', 'Yakuba Gowon', '2017-09-14 11:36:07'),
  (42, 'Between 1960-1966 Nigeria was governed under the?', 'Presidential System*Westminster System*Confedral System*Unitary System', 'Confedral System', '2017-09-14 11:44:59'),
  (113, 'One major inadequacy of the Richards Constitution was that', 'it was introduced without due and exhaustive consultations.*the official members of the legislature were too many.*it could not solve Nigeriaâ€™s economic and social problems.*it did not cater for the interest of the minorities.', 'it was introduced without due and exhaustive consultations.', '2017-09-28 16:23:28'),
  (112, 'A major recommendation of the Henry Willink Commission was the', 'protection of special interests.*creation of more administrative units.*inclusion of a bill of rights in the 1960 Constitution.*need to ensure the educational rights of the people.', 'inclusion of a bill of rights in the 1960 Constitution.', '2017-09-28 16:22:10'),
  (46, 'The military coup of July 25, 1975 which topped general Yakubu Gowon from power took place when he was attending which important event?', 'OAU Summit in Kampala*UN General Assembly in New York*Assembly of Heads of States of ECOWAS in Monrovia*The Olympic Games', 'OAU Summit in Kampala', '2017-09-14 12:11:45'),
  (47, 'Which of the following political parties did not participate in the 1979 General Elections in Nigeria?', 'Unity Party of Nigeria*National Party of Nigeria*Social Democratic Party*National Nominal Democracy Party', 'Social Democratic Party', '2017-09-14 12:14:29'),
  (48, 'Alhaji Shehu Shagari was sworn in as President of the Federal republic of Nigeria in 1979 by?', 'Justice Fatai Williams*Justice Adetokunbo Ademola*Justice Salihu Modibbo Alfa Belgore*Justice Isa Mohammed', 'Justice Adetokunbo Ademola', '2017-09-14 12:19:02'),
  (49, 'The British took over Nigeria through?', 'Negotiation*Bargaining *War *The Sea', 'The Sea', '2017-09-14 12:20:51'),
  (50, 'Which of the following courts served as the highest judicial organ for Nigeria up till 1963?', 'Supreme court*Federal Court of Appeal *Appellate court*The privy council', 'The privy council', '2017-09-14 12:22:35'),
  (51, 'What was the primary purpose of the Sir Henry Willinks Commission of Inquiry?', 'To approve the independence of Nigeria*To allay the fears of minorities in Nigeria*To amalgamate Northern and Southern Nigeria*To make Lagos a British colony', 'To allay the fears of minorities in Nigeria', '2017-09-14 12:31:39'),
  (111, 'The political party with the most radical orientation in the First Republic was the', 'NEPU.*NPC.*NCNC.*AG.', 'NEPU.', '2017-09-28 16:20:37'),
  (54, 'Which of these men introduced indirect rule in Nigeria?', 'Mungo Park*Dr. Nnamdi Azikiwe*Lord Lugard*Sir James Robertson', 'Lord Lugard', '2017-09-14 13:03:03'),
  (87, 'Who was the first American President to visit Nigeria?', 'Jimmy Carter April 3, 1978*Franklin D. Roosevelt March 31-April 3, 1978*Franklin D. Roosevelt March January 26-27, 1943*Jimmy Carter April December 9, 1943', 'Jimmy Carter April 3, 1978', '2017-09-27 11:55:27'),
  (86, 'Which individual first made the motion for Nigeriaâ€™s independence and in what year?', 'S.L Akintola 1956*Remi Fani Kayode 1958*Anthony Enahoro 1953*Sir Abubakar Tafawa Balewa 1951', 'Anthony Enahoro 1953', '2017-09-27 11:53:25'),
  (57, 'A nation consists of people with', 'Common history.* Common ancestry.*A shared set of values.*A,B, and C above.', 'A,B, and C above.', '2017-09-14 13:11:54'),
  (58, 'Who was the first man to buy a car in Nigeria?', 'Bob Jensen*Fred Shaw*Nathaniel James*Henry Ford', 'Bob Jensen', '2017-09-14 13:38:10'),
  (59, ' Who designed the Nigerian flag?', 'Micheal Taiwo Akinkumi*Oni Jacob*Maria James*Tafawa Balewa', 'Micheal Taiwo Akinkumi', '2017-09-14 13:40:07'),
  (60, ' When was Nigeria formed?', '1914*1916*1941*1967', '1914', '2017-09-14 13:42:48'),
  (61, 'Where was crude oil first discovered in Nigeria?', 'Idanre Hill located in Ondo State*Olumo Rock located in Ogun State*Obajana located in Kogi State*Oloibiri Oilfield, located in Oloibiri in Ogbia LGA of Bayelsa State', 'Oloibiri Oilfield, located in Oloibiri in Ogbia LGA of Bayelsa State', '2017-09-14 13:54:23'),
  (62, 'Who formed the first political party in Nigeria?', 'Herbert Macauly*Tafawa Balewa*Taiwo Akinkumi*Lord Lugard', 'Herbert Macauly', '2017-09-27 08:29:16'),
  (106, 'Certain chiefs in Eastern Nigeria were called warrant chiefs because they', 'had some royal connections.*were created by the British.*had warrants to arrest offenders.*were the first to receive Western education.', 'were created by the British.', '2017-09-28 16:13:50'),
  (64, 'What does the eagle in the Nigerian coat of arm represent?', 'Peace*Strength*Unity*Courage', 'Strength', '2017-09-27 08:30:55'),
  (65, 'What do the two horses on the Nigerian coat of arm represent?', 'Dignity*Pride*Strength*Power', 'Dignity', '2017-09-27 08:31:47'),
  (66, 'What does the white color in Nigerian flag stand for?', 'Peace*Loyalty*Dignity*Authority', 'Peace', '2017-09-27 08:35:53'),
  (131, 'How old is Nigeria?', '55 years old.*56 years old.*57 years old.*58 years old.', '57 years old.', '2017-09-28 17:23:25'),
  (105, 'The Nigerian Council established in 1914 was ineffective because it', 'was not designed to be so.*lacked sufficient funds.*had too many vocal members.*was dominated by hand-picked members.', 'was dominated by hand-picked members.', '2017-09-28 16:07:51'),
  (69, 'What do the two horses on the Nigerian coat of arm represent?', 'Dignity*pride*Strength*power', 'Dignity', '2017-09-27 10:34:16'),
  (70, 'What does the white color in Nigerian flag stand for?', 'Peace*Loyalty*Dignity*Authority', 'Peace', '2017-09-27 10:52:21'),
  (71, 'What does the green color in Nigerian flag represent?', 'Forests and abundant natural wealth of Nigeria.*Weed, and abundant plant*Vegetation and Rich Soil.*Forest and color', 'Forests and abundant natural wealth of Nigeria.', '2017-09-27 10:55:15'),
  (72, 'Nigeria is divided into how many geopolitical zones?', 'Six (6) geopolitical zones*Five (5) geopolitical zones*Four (4) geopolitical zones*Nine(9) geopolitical zones', 'Six (6) geopolitical zones', '2017-09-27 11:04:18'),
  (73, 'What was the first capital city in Nigeria?', 'Calabar*Abuja*Onitsha*Ibadan', 'Calabar', '2017-09-27 11:05:22'),
  (76, 'Who gave Nigeria her name?', 'Shehu Shagari*Lord Luggard*Flora Shaw*Taiwo Akinkumi', 'Flora Shaw', '2017-09-27 11:17:24'),
  (99, 'When was the Nigerian Naira introduced?', '1st January 1973*1st January 1972*1st January 1971*1st January 1970', '1st January 1973', '2017-09-28 15:58:27'),
  (78, 'National Anthem "Arise o compatriots..." was composed by Who?', ' Benedict E. Odiase.*Chris Okotie.*Lilian Jean Williams.*Frances Berda', ' Benedict E. Odiase.', '2017-09-27 11:27:09'),
  (79, 'Where was crude oil first discovered in Nigeria?', 'Oloibiri Oilfield, located in Oloibiri in Ogbia LGA of Bayelsa State.*Aje Field located in Block OML 113, offshore Lagos.*Orient Petroleum Refinery at Aguleri area in Anambra state.*Orogho in Orhionmwon Local Government Area of Edo State.', 'Oloibiri Oilfield, located in Oloibiri in Ogbia LGA of Bayelsa State.', '2017-09-27 11:31:08'),
  (81, 'What is the premier university in Nigeria?', 'obafemi Awolowo University*University of Ibadan*University Of Nigeria*University of Lagos', 'University of Ibadan', '2017-09-27 11:33:38'),
  (88, 'The use of Naira and kobo as Nigerian currency took effect from which year?', '1 January 1989*1 January 1912*1 January 1973*1 January 1914', '1 January 1973', '2017-09-27 11:57:00'),
  (83, 'When did Nigeria get her independence?', '1st October 1969.*1st October 1962.*1st October 1960.*1st October 1999.', '1st October 1960.', '2017-09-27 11:36:58'),
  (89, 'Who is the shortest reigning head of state in Nigeriaâ€™s history?', 'General Murtala Mohammed*Major-General Johnson Aguiyi-Ironsi*Major-General Olusegun Obasanjo*General Yakubu Gowon', 'General Murtala Mohammed', '2017-09-27 11:59:07'),
  (90, 'Who was Nigeria\'s last Governor-General?', 'Sir Frederick Lugard*Nnamdi Azikiwe*Sir James Wilson Robertson*Sir John Macpherson', 'Nnamdi Azikiwe', '2017-09-27 12:06:18'),
  (91, 'What is the home state of President Umaru Yar\'Adua?\n', 'Nasarawa*kano*Maiduguri*Kastina', 'Kastina', '2017-09-27 12:12:38'),
  (92, 'Abuja became the capital of Nigeria in what Year?', '1993*1991*1989*1992', '1991', '2017-09-27 12:15:18'),
  (93, 'The first military government in Nigeria was headed by', 'General Yakubu Gowon*General Agunyi Ironsi*General O. Obasanjo*General M. Mohammed', 'General Agunyi Ironsi', '2017-09-27 12:18:06'),
  (94, 'Who composed Nigeria\'s former National Anthem?', 'Abraham Nelson*Taiwo Akinkunmi*Lilian Jean Williams*Frances Berda', 'Frances Berda', '2017-09-27 12:23:38'),
  (95, 'Who wrote the lyrics of Nigeria\'s former National Anthem ?', 'Lilian Jean Williams*Charles Olumo*Frances Berda*Abraham Nelson', 'Lilian Jean Williams', '2017-09-27 12:26:51'),
  (96, 'What is centenary?', '50 years*100 years*500 years*5 yeears', '100 years', '2017-09-27 12:28:09'),
  (97, 'One of these was in existence before the outbreak of the second world war\n', 'The OAU*The League of Nations*The Commonwealth of Nations*ECOWAS', 'The League of Nations', '2017-09-27 12:57:40'),
  (121, 'The flag of Nigeria was designed in 1959 and first officially hoisted on', '1st October 1960.*3rd october 1960.*1st otober 1963.*27 febuary 1964.', '1st October 1960.', '2017-09-28 16:46:46'),
  (122, 'In 1897, British Journalist Flora Shaw, later wife of Lord Frederick Lugard, suggested the name â€œNigeriaâ€ after ', 'River Niger.*Cross River.*River Benue.*River Nile', 'River Niger.', '2017-09-28 16:48:19'),
  (123, 'One of the last independent West African kings Oba Ovonramwen of Benin was overthrown by the British in what year?\r\n', '1895*1894*1897*1984', '1897', '2017-09-28 16:49:34'),
  (124, 'Formation of Nigeria was under Administration of', 'Herbert Macauly.*Fedrick Luggard.*Yakubu Gowon.*Ibraheem Babangida.', 'Fedrick Luggard.', '2017-09-28 16:50:19'),
  (125, 'The Biafran War came to an end, leaving nearly two million people dead in what year?', 'January 15 1970*October 7 1971*Febuary 8 1976*September 4 1978', 'January 15 1970', '2017-09-28 16:51:17'),
  (126, 'What year did Nigeria change from driving on the right hand side of the road to the left?\r\n', 'April 2 1921*April 3 1974*April 7 1974*April 2 1971', 'April 2 1971', '2017-09-28 16:52:14'),
  (127, 'When was the National Youth Service Corps Scheme introduced?\r\n', 'May 1977*May 1976*May 1975*May 1973', 'May 1973', '2017-09-28 16:53:42'),
  (128, 'Murtala Mohammed was gunned down, in an abortive coup attempt, on his way to work from his residence on\r\n', 'Febuary 13 1976.*Febuary 13 1975.*October 9 1977*Febuary 1 1960.', 'Febuary 13 1976.', '2017-09-28 16:54:31'),
  (129, ' Lagos became a crown colony in what year?\r\n', '1861*1862* 1863* 1864', '1864', '2017-09-28 17:10:33'),
  (130, 'Which of the following played the greatest role in the British conquest of Nigeria?', 'The John Holt Company.*The Royal Niger Company.*The Church Missionary Society.*The Roman Catholic Mission.', 'The Royal Niger Company.', '2017-09-28 17:16:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `quiz_participant`
--
ALTER TABLE `quiz_participant`
  ADD PRIMARY KEY (`participant_id`),
  ADD UNIQUE KEY `participant_acc_no` (`participant_email`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `quiz_participant`
--
ALTER TABLE `quiz_participant`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
