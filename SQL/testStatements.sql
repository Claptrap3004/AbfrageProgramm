
DROP TABLE IF EXISTS track_quiz_content_rene_mail_org;
DROP TABLE IF EXISTS track_quiz_content_admin_mail_org;
DROP TABLE IF EXISTS track_quiz_content_fremder_mail_org;
DROP TABLE IF EXISTS quiz_content_rene_mail_org;
DROP TABLE IF EXISTS quiz_content_admin_mail_org;
DROP TABLE IF EXISTS quiz_content_fremder_mail_org;

CREATE TABLE quiz_content_rene_mail_org (id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT,
        is_actual BOOL);
CREATE TABLE quiz_content_admin_mail_org (id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT,
        is_actual BOOL);

CREATE TABLE quiz_content_fremder_mail_org (id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT,
        is_actual BOOL);

CREATE TABLE track_quiz_content_rene_mail_org(
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_id INT,
     answer_id INT
                                         );
CREATE TABLE track_quiz_content_admin_mail_org(
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_id INT,
     answer_id INT
);
CREATE TABLE track_quiz_content_fremder_mail_org(
    id INT PRIMARY KEY AUTO_INCREMENT,
    content_id INT,
     answer_id INT
);
