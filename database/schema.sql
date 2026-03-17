-- database/schema.sql
-- Create database
CREATE DATABASE IF NOT EXISTS touch_magazine;
USE touch_magazine;

-- Admin Users
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Categories
INSERT IGNORE INTO categories (name, slug) VALUES 
('Politics, Economics & Current Issues', 'politics-economics'),
('Travel & Photography', 'travel-photography'),
('Heritage, Culture & Environment', 'heritage-culture'),
('Food Recipes', 'food-recipes'),
('Sports, Wellness & Entertainment', 'sports-wellness');

-- Articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    content LONGTEXT NOT NULL,
    image_path VARCHAR(255),
    author_name VARCHAR(100) DEFAULT 'The Touch Editorial Board',
    publish_date DATE,
    is_featured BOOLEAN DEFAULT FALSE,
    is_editor_pick BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- Monthly Issues
CREATE TABLE IF NOT EXISTS issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    issue_month VARCHAR(50) NOT NULL,
    issue_year INT NOT NULL,
    cover_image VARCHAR(255) NOT NULL,
    pdf_file VARCHAR(255) NOT NULL,
    description TEXT,
    is_latest BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Podcasts
CREATE TABLE IF NOT EXISTS podcasts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    thumbnail_path VARCHAR(255),
    video_url VARCHAR(255),
    duration VARCHAR(50),
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'responded') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site Settings (Homepage contents, RGHE logo, Contact details)
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description VARCHAR(255)
);

-- Seed Default Settings
INSERT IGNORE INTO site_settings (setting_key, setting_value, description) VALUES 
('rghe_logo', 'images/rghe_logo.png', 'Educational Partner RGHE Footer Logo Path'),
('hero_headline', 'The Pulse <br><span class="italic font-normal">of</span> Sri Lanka.', 'Homepage Hero Headline'),
('hero_subheading', 'Inside The City', 'Homepage Hero Subheading'),
('hero_text', 'A modern monthly magazine blending insightful journalism with stunning visual storytelling...', 'Homepage Hero Paragraph text'),
('hero_bg_image', 'images/politics.png', 'Homepage Hero Background Image'),
('footer_partner_text', 'Educational Partner RGHE', 'Text placed in footer regarding the partnership');

-- Default Admin (Password: TouchAdmin2026!)
-- ONLY FOR DEVELOPMENT
INSERT IGNORE INTO admins (username, password_hash) VALUES 
('admin', '$2y$10$iXoHTr/C2F6E3H2tHhOMwODS.oM6QoP9f8YdM6aFjPInIeE0k1l2C');
