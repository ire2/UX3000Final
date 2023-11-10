CREATE TABLE "reviews" (
  "id" INTEGER NOT NULL UNIQUE,
  "text" text NOT NULL,
  "rating" INTEGER NOT NULL,
  "movie_id" INTEGER NOT NULL,
  PRIMARY KEY("id" AUTOINCREMENT)
);
INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (1, 'such a great movie', 5, 1);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (2, 'Amazing!', 5, 1);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (3, 'Wow', 5, 2);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (4, 'A Hitcock Classic!', 5, 15);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (5, 'The tears would not stop flowing', 5, 13);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (6, 'The tears would not stop flowing', 5, 13);


INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (7, 'Disturbing film that crosses the line between complex and distrubed', 1, 10);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (8, 'Overrated', 1, 5);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (9, 'Not Scary!', 1, 5);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (10, 'A good book to film adaptation', 1, 8);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (11, 'One of my personal favorithe thrillers', 5, 11);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (12, 'Thriller Classic!', 5, 11);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (13, 'Good family movie!', 3, 7);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (14, 'Meh! Wouldn''t watch again', 2, 6);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (15, 'Amazing Movie Series', 4, 9);

INSERT INTO
  "reviews" ("id", "text", "rating", "movie_id")
VALUES
  (16, 'A beautiful classic movie', 5, 13);



/* Movies in Data */
CREATE TABLE "movies" (
  "id" INTEGER NOT NULL UNIQUE,
  "genre" INTEGER NOT NULL,
  "name" TEXT NOT NULL,
  "rating" INTEGER NOT NULL,
  PRIMARY KEY("id" AUTOINCREMENT) FOREIGN KEY(id) REFERENCES reviews(movie_id)
);


INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (1, 2, 'Black Swan', 5);
INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (2, 1, 'Avengers: Endgame', 5);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (3, 2, 'Requiem for a Dream', 5);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (4, 7, 'Puss in Boots: The Last Wish', 3);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (5, 6, 'Hereditary', 3);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (6, 6, 'The Conjuring', 1);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (7, 2, 'Mamma Mia', 2);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (8, 2, 'Pride and Prejudice', 4);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (9, 7, 'Toy Story', 4);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (10, 3, 'American Beauty', 2);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (11, 6, 'The Silence of the Lambs', 5);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (12, 5, 'The Matrix', 4);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (13, 2, 'It''s a Wonderful life', 5);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (14, 2, 'Citizen Kane', 5);

INSERT INTO
  "movies" ("id", "genre", "name", "rating")
VALUES
  (15, 6, 'Vertigo', 5);

CREATE TABLE users (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT)
);

-- password: monkey
INSERT INTO
  users (id, name, email, username, password)
VALUES
  (
    1,
    'Kyle Harms',
    'kyle.harms@cornell.edu',
    'kyle',
    '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.' -- monkey
  );

-- password: monkey
INSERT INTO
  users (id, name, email, username, password)
VALUES
  (
    2,
    'Ignacio Estrada Cavero',
    'ire2@cornell.edu',
    'iggy',
    '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.' -- monkey
  );

--- Sessions ---
CREATE TABLE sessions (
  id INTEGER NOT NULL UNIQUE,
  user_id INTEGER NOT NULL,
  session TEXT NOT NULL UNIQUE,
  last_login TEXT NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(user_id) REFERENCES users(id)
);

--- Groups ----
CREATE TABLE groups (
  id INTEGER NOT NULL UNIQUE,
  name TEXT NOT NULL UNIQUE,
  PRIMARY KEY(id AUTOINCREMENT)
);

INSERT INTO
  groups (id, name)
VALUES
  (1, 'admin');

--- Group Membership ---
CREATE TABLE user_groups (
  id INTEGER NOT NULL UNIQUE,
  user_id INTEGER NOT NULL,
  group_id INTEGER NOT NULL,
  PRIMARY KEY(id AUTOINCREMENT) FOREIGN KEY(group_id) REFERENCES groups(id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);

-- User 'iggy' is a member of the 'admin' group.
INSERT INTO
  user_groups (user_id, group_id)
VALUES
  (1, 1);

  -- images--
CREATE TABLE images (
  id INTEGER NOT NULL UNIQUE,
  movie_id INTEGER NOT NULL,
  file_name TEXT NOT NULL,
  file_ext TEXT NOT NULL,
  source TEXT,
  PRIMARY KEY(id AUTOINCREMENT),
  FOREIGN KEY(movie_id) REFERENCES movies(id)
);
INSERT INTO "images" ("movie_id", "file_name", "file_ext", "source") VALUES (1, "Black_Swan", "png", "https://www.imdb.com/title/tt0947798/mediaviewer/rm4146692096?ref_=ttmi_mi_all_sf_14" ), (2,"avengers", "png", "https://www.imdb.com/title/tt4154796/"), (3,"requiem", "png", "https://www.imdb.com/title/tt0180093/?ref_=nv_sr_srsg_0_tt_8_nm_0_q_requiem" ), (4, "puss_in_boots", "png", "https://www.imdb.com/title/tt3915174/?ref_=tt_mv_close"), (5, "Hereditary", "png", "https://www.imdb.com/title/tt7784604/mediaviewer/rm519258624/?ref_=tt_ov_i"),(6, "conjuring", "png", "https://www.imdb.com/title/tt1457767/mediaviewer/rm1035247872/?ref_=tt_ov_i"),(7,"mamma_mia", "png", "https://www.imdb.com/title/tt0795421/mediaviewer/rm3758003968/?ref_=tt_ov_i" ), (8,"pride", "png", "https://www.imdb.com/title/tt0414387/mediaviewer/rm1343528192/?ref_=tt_ov_i" ), (9, "Toy_story", "png", "https://www.imdb.com/title/tt0114709/mediaviewer/rm3813007616/?ref_=tt_ov_i"), (10, "American_Beauty", "png", "https://www.imdb.com/title/tt0169547/mediaviewer/rm2430294272/?ref_=tt_ov_i"),  (11, "Lambs", "png", "https://www.imdb.com/title/tt0102926/mediaviewer/rm3242988544/?ref_=tt_ov_i"), (12, "The_Matrix", "png", "https://www.imdb.com/title/tt0133093/mediaviewer/rm525547776/?ref_=tt_ov_i"),(13, "Wonderful_Life", "png", "https://www.imdb.com/title/tt0038650/mediaviewer/rm3862243328/?ref_=tt_ov_i"), (14, "Citizen_Kane", "png", "https://www.imdb.com/title/tt0033467/mediaviewer/rm684416000/?ref_=tt_ov_i"), (15, "Vertigo", "png", "https://www.imdb.com/title/tt0052357/mediaviewer/rm4007330816/?ref_=tt_ov_i");




CREATE TABLE tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  movie_id INTEGER NOT NULL,
  tag TEXT NOT NULL,
  FOREIGN KEY(movie_id) REFERENCES movies(id)
);


INSERT INTO "tags" ("movie_id", "tag") VALUES (1, "psychological");

INSERT INTO "tags" ("movie_id", "tag") VALUES (1, "indie film");

INSERT INTO "tags" ("movie_id", "tag") VALUES (1, "thriller");

INSERT INTO "tags" ("movie_id", "tag") VALUES (2, "action");

INSERT INTO "tags" ("movie_id", "tag") VALUES (2, "superhero");

INSERT INTO "tags" ("movie_id", "tag") VALUES (3, "drama");

INSERT INTO "tags" ("movie_id", "tag") VALUES (3, "tragedy");

INSERT INTO "tags" ("movie_id", "tag") VALUES (4, "animation");

INSERT INTO "tags" ("movie_id", "tag") VALUES (4, "children");

INSERT INTO "tags" ("movie_id", "tag") VALUES (5, "horror");

INSERT INTO "tags" ("movie_id", "tag") VALUES (5, "tragedy");

INSERT INTO "tags" ("movie_id", "tag") VALUES (6, "horror");

INSERT INTO "tags" ("movie_id", "tag") VALUES (6, "drama");

INSERT INTO "tags" ("movie_id", "tag") VALUES (7, "musical");

INSERT INTO "tags" ("movie_id", "tag") VALUES (7, "romance");

INSERT INTO "tags" ("movie_id", "tag") VALUES (8, "romance");

INSERT INTO "tags" ("movie_id", "tag") VALUES (8, "drama");

INSERT INTO "tags" ("movie_id", "tag") VALUES (9, "animation");

INSERT INTO "tags" ("movie_id", "tag") VALUES (9, "children");

INSERT INTO "tags" ("movie_id", "tag") VALUES (10, "drama");

INSERT INTO "tags" ("movie_id", "tag") VALUES (10, "indie film");

INSERT INTO "tags" ("movie_id", "tag") VALUES (11, "thriller");

INSERT INTO "tags" ("movie_id", "tag") VALUES (11, "psychological");

INSERT INTO "tags" ("movie_id", "tag") VALUES (12, "action");

INSERT INTO "tags" ("movie_id", "tag") VALUES (12, "sci-fi");

INSERT INTO "tags" ("movie_id", "tag") VALUES (13, "drama");

INSERT INTO "tags" ("movie_id", "tag") VALUES (13, "classic");

INSERT INTO "tags" ("movie_id", "tag") VALUES (14, "drama");

INSERT INTO "tags" ("movie_id", "tag") VALUES (14, "mystery");

INSERT INTO "tags" ("movie_id", "tag") VALUES (15, "classic");

INSERT INTO "tags" ("movie_id", "tag") VALUES (15, "thriller");

INSERT INTO "tags" ("movie_id", "tag") VALUES (15, "mystery");

INSERT INTO "tags" ("movie_id", "tag")
VALUES
  (2, "2000-present"),
  (3, "2000-present"),
  (4, "2000-present"),
  (5, "2000-present"),
  (6, "2000-present"),
  (7, "2000-present"),
  (8, "1980-1999"),
  (9, "1980-1999"),
  (10, "1980-1999"),
  (11, "1980-1999"),
  (12, "2000-present"),
  (13, "before 1980"),
  (14, "before 1980"),
  (15, "before 1980");
