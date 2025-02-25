DROP TABLE IF EXISTS goals;
CREATE TABLE goals (
    goal_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    category ENUM('Weight Loss', 'Muscle Gain', 'Endurance', 'Flexibility', 'General Fitness') NOT NULL DEFAULT 'General Fitness',
    description TEXT,
    target_date DATE,
    is_completed BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);