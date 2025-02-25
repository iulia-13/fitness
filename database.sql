-- Drop existing database if it exists
DROP DATABASE IF EXISTS fitnessdb;

-- Create the fitness_app database
CREATE DATABASE fitnessdb;
USE fitnessdb;

-- Users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    age INT,
    weight DECIMAL(5,2),
    height DECIMAL(5,2),
    difficulty_level ENUM('beginner', 'medium', 'intermediate') DEFAULT 'beginner',
    fitness_goal TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    profile_completed BOOLEAN DEFAULT 0
);

-- Add indexes for users table
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_difficulty ON users(difficulty_level);

-- Plans table
CREATE TABLE plans (
    plan_id INT PRIMARY KEY AUTO_INCREMENT,
    plan_name VARCHAR(255) NOT NULL,
    description TEXT,
    difficulty_level ENUM('beginner', 'medium', 'intermediate'),
    is_public BOOLEAN DEFAULT true,
    creator_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creator_id) REFERENCES users(user_id)
);

-- Add indexes for plans table
CREATE INDEX idx_plan_difficulty ON plans(difficulty_level);
CREATE INDEX idx_plan_creator ON plans(creator_id);

-- Progress tracking table
CREATE TABLE progress (
    progress_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    plan_id INT,
    completed_date DATE,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (plan_id) REFERENCES plans(plan_id)
);

-- Add indexes for progress table
CREATE INDEX idx_progress_user ON progress(user_id);
CREATE INDEX idx_progress_date ON progress(completed_date);

-- Goals table
CREATE TABLE goals (
    goal_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    description TEXT,
    target_date DATE,
    is_completed BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    title VARCHAR(255) NOT NULL,
    category ENUM('Weight Loss', 'Muscle Gain', 'Endurance', 'Flexibility', 'General Fitness') NOT NULL DEFAULT 'General Fitness'
);

-- Add indexes for goals table
CREATE INDEX idx_goals_user ON goals(user_id);
CREATE INDEX idx_goals_completion ON goals(is_completed);

-- Calendar events table
CREATE TABLE calendar_events (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    event_title VARCHAR(255),
    event_description TEXT,
    event_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Add indexes for calendar events table
CREATE INDEX idx_calendar_user ON calendar_events(user_id);
CREATE INDEX idx_calendar_date ON calendar_events(event_date); 