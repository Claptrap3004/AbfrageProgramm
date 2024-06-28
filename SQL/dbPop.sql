USE abfrageprogramm;
INSERT INTO user (username, email, password) VALUES ('admin', 'admin@mail.org','hallo'),
                                                    ('rene', 'rene@mail.org','hallo'),
                                                    ('fremder', 'fremder@mail.org','hallo');

INSERT INTO category (text) VALUES ('PC - Grundlagen'),
                                   ('Netzwerk'),
                                   ('Datenbank'),
                                   ('Programmierung'),
                                   ('Projektmanagement'),
                                   ('Betriebssysteme');

INSERT INTO question (category_id, user_id, text) VALUES (1,1,'Welches Bauteil eines Computers führt Berechnungen durch ?'),
                                                         (2,1,'Nennen Sie zwei Transport - Protokolle'),
                                                         (3,1, 'Welcher SQL - Befehl wird zur Erstelleung neuer Einträge verwendet ?');

INSERT INTO answer (text) VALUES ('CPU'),
                                 ('Northbridge'),
                                 ('RAM'),
                                 ('USB - Port'),
                                 ('TCP'),
                                 ('UDP'),
                                 ('ipV4'),
                                 ('ipV6'),
                                 ('CREATE'),
                                 ('SELECT'),
                                 ('JOIN'),
                                 ('INSERT INTO');

INSERT INTO stats (user_id,question_id, times_asked, times_right) VALUES (2,1,0,0),
                                                                 (2,2,0,0),
                                                                 (2,3,0,0);

INSERT INTO answerToQuestion (question_id, answer_id, is_right) VALUES (1,1,1),
                                                                      (1,2,0),
                                                                      (1,3,0),
                                                                      (1,4,0),
                                                                      (2,5,1),
                                                                      (2,6,1),
                                                                      (2,7,0),
                                                                      (2,8,0),
                                                                      (3,9,0),
                                                                      (3,10,0),
                                                                      (3,11,0),
                                                                      (3,12,1);


