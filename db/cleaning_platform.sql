-- Create the database
CREATE DATABASE IF NOT EXISTS cleaning_platform;
USE cleaning_platform;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    userid INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

-- Service Categories
CREATE TABLE IF NOT EXISTS service_categories (
    categoryid INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Services
CREATE TABLE IF NOT EXISTS services (
    serviceid INT AUTO_INCREMENT PRIMARY KEY,
    cleanerid INT NOT NULL,
    categoryid INT NOT NULL,
    title VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2),
    availability VARCHAR(100),
    FOREIGN KEY (cleanerid) REFERENCES users(userid),
    FOREIGN KEY (categoryid) REFERENCES service_categories(categoryid)
);

-- Shortlists
CREATE TABLE IF NOT EXISTS shortlists (
    shortlistid INT AUTO_INCREMENT PRIMARY KEY,
    homeownerid INT,
    cleanerid INT,
    FOREIGN KEY (homeownerid) REFERENCES users(userid),
    FOREIGN KEY (cleanerid) REFERENCES users(userid)
);

-- Match History
CREATE TABLE IF NOT EXISTS match_history (
    matchid INT AUTO_INCREMENT PRIMARY KEY,
    cleanerid INT,
    homeownerid INT,
    serviceid INT,
    match_date DATE,
    FOREIGN KEY (cleanerid) REFERENCES users(userid),
    FOREIGN KEY (homeownerid) REFERENCES users(userid),
    FOREIGN KEY (serviceid) REFERENCES services(serviceid)
);
