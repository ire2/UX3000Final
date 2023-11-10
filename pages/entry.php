<?php
$entry= false;
const RATING = array(
    1 => '★☆☆☆☆',
    2 => '★★☆☆',
    3 => '★★★☆☆',
    4 => '★★★★☆',
    5 => '★★★★★'
  );
  const GENRE = array(
    1 => 'Action and Adventure',
    2 => 'Drama',
    3 => 'Comedy',
    4 => 'Fantasy',
    5 => 'Science Fiction',
    6 => 'Horror',
    7 => "Animation"
  );

if(isset($_GET['movie_id'])) {

    $entry = true;
    $movie = $_GET['movie_id'];
    $sql = "SELECT
    reviews.id AS 'reviews.id',
    reviews.text AS 'reviews.text',
    reviews.rating AS 'reviews.rating',
    movies.genre AS 'movies.genre',
    movies.name AS 'movies.name',
    movies.rating AS 'movies.rating',
    images.id AS 'images.id',
    images.file_ext AS 'images.file_ext',
    images.file_name AS 'images.file_name',
    images.source AS 'images.source',  COUNT(reviews.id) AS 'reviews.count'
  FROM movies
  LEFT JOIN images ON movies.id = images.movie_id
  LEFT JOIN reviews ON movies.id = reviews.movie_id
  WHERE movies.id = $movie";

    $sql2 = "SELECT
    tags.tag AS 'tags.tag'
    FROM tags
    LEFT JOIN movies ON (tags.movie_id = movies.id)
    WHERE movies.id = $movie";

    $sql3 = "SELECT reviews.id AS 'reviews.id', reviews.text AS 'reviews.text', reviews.rating AS 'reviews.rating', movies.genre AS 'movies.genre', movies.name AS 'movies.name', movies.rating AS 'movies.rating'
    FROM reviews
    INNER JOIN movies ON (reviews.movie_id = movies.id)
    WHERE movies.id = $movie";

}
$records = exec_sql_query($db, $sql)->fetchAll();
$tags = exec_sql_query($db, $sql2)->fetchAll();
$reviews = exec_sql_query($db, $sql3)->fetchAll();

$title = $records[0]['movies.name']; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
  <title>Home</title>
</head>

<body>
<?php include 'includes/header.php'; ?>
    <?php if ($entry) { ?>
        <div class="movie-container">
            <?php $file_url = '/public/uploads/movies/' . $records[0]['images.id'] . '.' . $records[0]['images.file_ext'];
          ?>
        <img src="<?php echo htmlspecialchars($file_url); ?> " alt="Movie Image" class="movie-image">
        <div class="movie-info">
        <h1 class="movie-title"><em><?php echo htmlspecialchars($records[0]['movies.name']); ?></em> </a><?php echo htmlspecialchars(RATING[$records[0]['movies.rating']]); ?> </h1>
        <h2 class="movie-genre"> This Movies Primary Genre: <?php echo htmlspecialchars(GENRE[$records[0]['movies.genre']]); ?>  </h2>
        <p class="tags">
            <em> Tags for this movie: </em>

        <?php foreach ($tags as $tag) { ?>
           #<?php echo htmlspecialchars($tag['tags.tag']) ; ?>
        <?php }?>
            </p>



        <?php if($records[0]['reviews.count'] > 0) { ?>
        <table class="movie-reviews">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Review</th>
                    <th>Reviewer Rating</th>

                 </tr>
            </thead>
            <?php

          foreach ($reviews as $review) { ?>
            <tr>
              <td><em>
                <?php echo htmlspecialchars($review['movies.name']); ?></em>
              </td>
              <td> <?php echo htmlspecialchars($review['reviews.text']); ?></td>
              <td><?php echo htmlspecialchars(RATING[$review['reviews.rating']]); ?></td>


            </tr>
          <?php } ?>
          </table>
            <?php } else { ?>
                <p> There are no reviews for <?php echo htmlspecialchars($records[0]['movies.name']); ?> </p>
                <a href="/reviews"> Add One Here! </a>
            <?php } ?>


        <ul>

        </ul>

    <?php } else { ?>
        <div class="intro">
            <h2>There is no movie query!</h2>
        </div>

        <?php } ?>
</body>
</html>
