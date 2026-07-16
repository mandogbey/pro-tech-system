CEATE DATABASE voting_system;

USE voting_sytem;

-- ==================================================
-- USERS
-- ==================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    photo VARCHAR(255) DEFAULT 'default.png';
    fullname VARCHAR(100) NOT NULL,
    faculty_id INT NOT NULL,
    department_id INT NOT NULL,
    year_level VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    has_voted TINYINT(1) DEFAULT 0,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (faculty_id)
        REFERENCES faculties(id),

    FOREIGN KEY (department_id)
        REFERENCES departments(id)
);

-- ==================================================
-- POSITIONS
-- ==================================================
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(100) NOT NULL,
    max_vote INT DEFAULT 1
);

-- ==================================================
-- CANDIDATES
-- ==================================================
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    faculty VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    slogan VARCHAR(255),
    manifesto TEXT,
    photo VARCHAR(255) DEFAULT 'default.png',
    position_id INT NOT NULL,
    status ENUM('Active','Inactive') DEFAULT 'Active',

    FOREIGN KEY(position_id)
    REFERENCES positions(id)
    ON DELETE CASCADE
);

-- ==================================================
-- VOTES
-- ==================================================
CREATE TABLE votes (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    candidate_id INT NOT NULL,

    position_id INT NOT NULL,

    vote_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,

    FOREIGN KEY(candidate_id)
    REFERENCES candidates(id)
    ON DELETE CASCADE,

    FOREIGN KEY(position_id)
    REFERENCES positions(id)
    ON DELETE CASCADE
);

-- ==================================================
-- ELECTION SETTINGS
-- ==================================================
CREATE TABLE election_settings (

    id INT AUTO_INCREMENT PRIMARY KEY,

    election_title VARCHAR(150),

    election_status ENUM('Open','Closed')
    DEFAULT 'Closed',

    start_date DATETIME,

    end_date DATETIME
);

-- ==================================================
-- AUDIT LOGS
-- ==================================================
CREATE TABLE audit_logs (

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_name VARCHAR(100),

    action_performed VARCHAR(255),

    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);