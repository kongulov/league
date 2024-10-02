CREATE DATABASE IF NOT EXISTS league;

USE league;

CREATE TABLE IF NOT EXISTS teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    strength INT NOT NULL
);

CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    home_team_id INT NOT NULL,
    away_team_id INT NOT NULL,
    home_team_goals INT NOT NULL,
    away_team_goals INT NOT NULL,
    week INT NOT NULL,
    is_finished BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (home_team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (away_team_id) REFERENCES teams(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS league_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    points INT DEFAULT 0,
    wins INT DEFAULT 0,
    draws INT DEFAULT 0,
    losses INT DEFAULT 0,
    goal_difference INT DEFAULT 0,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);

INSERT INTO teams (name, strength) VALUES
    ('Real Madrid', 5),
    ('Barcelona', 5),
    ('Liverpool', 4),
    ('Manchester United', 3);
