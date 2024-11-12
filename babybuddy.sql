-- Criação do banco de dados
CREATE DATABASE babybuddy;

-- Selecionar o banco de dados
USE babybuddy;

-- Criação da tabela de babás
CREATE TABLE babysitters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    hourly_rate DECIMAL(10, 2),
    qualifications TEXT,
    experience TEXT
);

-- Criação da tabela de responsáveis
CREATE TABLE guardians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    babysitter_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    hourly_rate DECIMAL(10, 2) NOT NULL,
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    babysitter_id INT,
    guardian_id INT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id),
    FOREIGN KEY (guardian_id) REFERENCES guardians(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    babysitter_id INT,
    guardian_id INT,
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id),
    FOREIGN KEY (guardian_id) REFERENCES guardians(id)
);

drop table messages;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES guardians(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES babysitters(id) ON DELETE CASCADE
);

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    hourly_rate DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    guardian_id INT NOT NULL,
    FOREIGN KEY (guardian_id) REFERENCES guardians(id)
);

SELECT * FROM babysitters;

DESCRIBE jobs;

ALTER TABLE jobs ADD COLUMN babysitter_id INT;

DESCRIBE messages;

ALTER TABLE guardians ADD location VARCHAR(255);

ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';
FLUSH PRIVILEGES;

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    date DATETIME,
    babysitter_id INT,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id)
);


ALTER TABLE messages ADD recipient_id INT NOT NULL;

SHOW TABLES;

CREATE TABLE finances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    babysitter_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (babysitter_id) REFERENCES babysitters(id)
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATETIME NOT NULL,
    location VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE events 
ADD COLUMN title VARCHAR(255) NOT NULL,
ADD COLUMN start DATETIME NOT NULL,
ADD COLUMN end DATETIME NOT NULL,
ADD COLUMN user_id INT NOT NULL,
ADD FOREIGN KEY (user_id) REFERENCES babysitters(id);  -- ou guardians(id), dependendo de qual tabela usa o 'user_id'





