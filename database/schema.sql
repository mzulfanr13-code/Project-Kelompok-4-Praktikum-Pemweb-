-- ==========================================
-- DATABASE
-- ==========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- CHARACTERS
-- ==========================================

CREATE TABLE characters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    first_appearance VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- EPISODES
-- ==========================================

CREATE TABLE episodes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    season INT NOT NULL,
    episode_number INT NOT NULL,
    synopsis TEXT,
    air_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- QUOTES
-- ==========================================

CREATE TABLE quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_id INT NOT NULL,
    episode_id INT NOT NULL,
    content TEXT NOT NULL,
    submitted_by INT NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (character_id)
        REFERENCES characters(id)
        ON DELETE CASCADE,

    FOREIGN KEY (episode_id)
        REFERENCES episodes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (submitted_by)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- ==========================================
-- ADMIN ACCOUNT
-- password = admin123
-- ==========================================

INSERT INTO users
(username,email,password,role)
VALUES
(
'admin',
'admin@gumball.com',
'$2y$10$Yh4ei11doaUZsvCRMCmW8OmJoYzWyVqKuYiB4f97t9bP7K2Ph8lHa',
'admin'
);

-- ==========================================
-- SAMPLE USERS
-- password = user123
-- ==========================================

INSERT INTO users
(username,email,password,role)
VALUES
(
'user',
'user@gmail.com',
'$2y$10$yXB9iphBez8oxbqY5t0gn.RwNHGzFW0njYtPoB/eXzokf5z9PU91.',
'user'
);

-- ==========================================
-- CHARACTERS DATA
-- ==========================================

INSERT INTO characters
(name,species,description,image_url,first_appearance)
VALUES

(
'Gumball Watterson',
'Blue Cat',
'Main protagonist of the series.',
'assets/images/gumball.png',
'The DVD'
),

(
'Darwin Watterson',
'Goldfish',
'Gumball best friend and adopted brother.',
'assets/images/darwin.png',
'The DVD'
),

(
'Anais Watterson',
'Rabbit',
'Youngest member of the family and highly intelligent.',
'assets/images/anais.png',
'The DVD'
),

(
'Richard Watterson',
'Rabbit',
'Father of the Watterson family.',
'assets/images/richard.png',
'The DVD'
),

(
'Nicole Watterson',
'Cat',
'Mother of the Watterson family.',
'assets/images/nicole.png',
'The DVD'
);

-- ==========================================
-- EPISODES DATA
-- ==========================================

INSERT INTO episodes
(title,season,episode_number,synopsis,air_date)
VALUES

(
'The DVD',
1,
1,
'Gumball and Darwin try to return a rented DVD.',
'2011-05-03'
),

(
'The Responsible',
1,
2,
'Darwin learns responsibility.',
'2011-05-10'
),

(
'The Third',
1,
3,
'Gumball and Darwin search for a third friend.',
'2011-05-17'
),

(
'The Debt',
1,
4,
'Richard owes money and causes problems.',
'2011-05-24'
),

(
'The End',
2,
1,
'The family faces unexpected challenges.',
'2012-08-07'
);

-- ==========================================
-- QUOTES DATA
-- ==========================================

INSERT INTO quotes
(character_id,episode_id,content,submitted_by,status)
VALUES

(
1,
1,
'Life would be so much easier if everything came with instructions.',
1,
'approved'
),

(
2,
2,
'Being responsible is harder than I thought.',
1,
'approved'
),

(
3,
3,
'Sometimes logic is the answer.',
2,
'approved'
),

(
5,
4,
'Family always comes first.',
1,
'approved'
),

(
4,
4,
'I was going to do it tomorrow.',
3,
'pending'
);

-- ==========================================
-- TEST QUERY
-- ==========================================

/*
SELECT
q.id,
c.name AS character_name,
e.title AS episode_title,
q.content,
u.username,
q.status
FROM quotes q
JOIN characters c ON q.character_id = c.id
JOIN episodes e ON q.episode_id = e.id
JOIN users u ON q.submitted_by = u.id;
*/