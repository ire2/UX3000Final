<?php

$title = 'Movie Reviews';
$nav_movies_class = 'active_page';
$submit = false;

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

$form_values = array(
  'rating' => '',
  'name' => '',
  'review' => ''
);
$insert_values = array(
  'rating' => '',
  'name' => '',
  'review' => ''
);
$sticky_values = array(
  'rating' => '',
  'name' => '',
  'review' => ''
);
$form_feedback_classes = array(
  'rating' => 'hidden',
  'name' => 'hidden',
  'review' => 'hidden'
);

$show_confirmation = FALSE;

if (isset($_POST['request-insert'])) {
  $form_values['rating'] = ($_POST['insert_rating']);
  $form_values['name'] = ($_POST['movie_id']);
  $form_values['review'] = trim($_POST['review']);

  $form_valid= TRUE;

  if ($form_values['rating'] == '') {

    $form_valid = False;

    $form_feedback_classes['rating'] = '';
  }
  if ($form_values['name'] == '') {

    $form_valid = False;

    $form_feedback_classes['name'] = '';
  }
  if ($form_values['review'] == '') {

    $form_valid = False;

    $form_feedback_classes['review'] = '';
  }


  if($form_valid){
    $show_confirmation= True;

    $insert_values['rating'] = ($_POST['insert_rating'] == '' ? NULL : (int)$_POST['insert_rating']);
    $insert_values['name'] = ($_POST['movie_id'] == '' ? NULL : (int)$_POST['movie_id']);
    $insert_values['review'] = ($_POST['review'] == '' ? NULL : trim($_POST['review']));

    $result = exec_sql_query(
      $db,
      "INSERT INTO reviews (text, rating, movie_id) VALUES (:text, :rating, :movie_id);",
      array(
        ':text' => htmlspecialchars($insert_values['review']),
        ':rating' => $insert_values['rating'],
        ':movie_id' => $insert_values['name']
      )
    );
    $submit = true;
  }
}


$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');
$sql = "SELECT reviews.id AS 'reviews.id', reviews.text AS 'reviews.text', reviews.rating AS 'reviews.rating', movies.genre AS 'movies.genre', movies.name AS 'movies.name', movies.rating AS 'movies.rating'
          FROM reviews
          INNER JOIN movies ON (reviews.movie_id = movies.id)";


$genre = $_GET['genre'];
$rating = $_GET['rating'];
$sticky_genre = '';
$sticky_rating ='';
if($genre != '' && $genre != null) {
  $sql .=  " WHERE movies.genre = '$genre'";
  $sticky_genre = GENRE[$genre];
}
if($rating != '' && $rating != null) {
  $sql .= " ORDER BY reviews.rating $rating";
  $sticky_rating = $rating;
}

$records = exec_sql_query($db, $sql)->fetchAll();

$sql2 = "SELECT id, name FROM movies";

$films = exec_sql_query($db, $sql2)->fetchAll();


?>
<!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
    <title>Movies!</title>
  </head>

  <body>
    <?php include 'includes/header.php'; ?>
    <div class="intro">
    <?php if(is_user_logged_in()) { ?>
      <h1>Welcome Back!</h1>
        <p> Add your own review to the catalog! Right now a popular movie seems to be <em>Everything Everywhere All at Once</em>! If you've seen it or any other movie in our catalog recently share your thoughts below.</p>
      <?php } else { ?>
        <h1>Join out Community!</h1>
        <p> It seem's you do not have an account with us yet, without one you won't be able to add your own personal reviews! However, you can still view other's reviews. </p>
        <?php } ?>
    </div>
    <div class="box">
    <div class="sidebar">
        <h2 class="sideTitle">Filter Movies</h2>

        <form action="" method="get">
          <label for="genre">Genre:</label>
            <select name="genre" id="genre">
              <option value="">All Genres</option>
              <?php foreach(GENRE as $code => $genre){?>
              <option value="<?php echo $code; ?>" <?php if($genre == $sticky_genre) {echo 'selected';} ?>><?php echo $genre; ?></option>
              <?php } ?>
            </select>
        <label id="rating"for="rating">Rating:</label>
          <select name="rating" id="rating">
            <option value="">No Filter</option>
            <option value="DESC" <?php if($sticky_rating == 'DESC') {echo 'selected';} ?>>High to Low</option>
            <option value="ASC" <?php if($sticky_rating == 'ASC') {echo 'selected';} ?>>Low to High</option>
          </select>
          <div class="align-right">
          <button type="submit">Filter</button>
        </div>
        </form>
        <?php if(is_user_logged_in()) { ?>
        <div class="insert">

          <form class="form" method="post" action="/reviews">
              <?php if ($submit){?>
              <p class="submit"> You succesfully submit a movie review! Feel free to submit another!</p>
              <?php } ?>
              <h3 class="form_title"> Insert Review! </h3>
              <p class="feedback <?php echo $form_feedback_classes['name']; ?>">Please provide your movie name.</p>
              <div class="label_input">
              <label for="movie_id">Movie Name:</label>
                <select name="movie_id" id="movie_id">
                  <option value="">All Movies</option>
                    <?php foreach($films as $film){ ?>
                    <option value="<?php echo $film['id']; ?>"><?php echo $film['name']; ?></option>
                    <?php } ?>
                </select>
              </div>
              <p class="feedback <?php echo $form_feedback_classes['rating']; ?>">Please provide a movie genre.</p>
              <div class="label_input">
              <label for="insert_rating">Select Rating</label>
                <select id="insert_rating" name="insert_rating">
                  <option value="">Ratings</option>

                  <?php foreach (RATING as $code => $rating) { ?>
                    <option value='<?php echo $code; ?>' <?php if ($code == $sticky_values['rating']) {
                                                            echo "selected";
                                                          } ?>> <?php echo $code; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="label_input" id="text_insert">
                <label for="review">Short Review:</label>
                <p class="feedback <?php echo $form_feedback_classes['review']; ?>">Please provide a review. </p>
                <input type="text" id="review" name="review" maxlength="25" placeholder="25 Word Limit"required>
              </div>

              <div class="align-right">
                <button type="submit" value="Insert Review" name="request-insert"> Insert Review </button>
              </div>
        </form>
        <?php } else { ?>
            <div class="insert_login">
              <h3> Please Login:</h3>
              <?php echo login_form('/', $session_messages);} ?>
            </div>
      </div>
    <div class="main">
      <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Genre</th>
                <th>Review</th>
                <th>Reviewer Rating</th>
                <th>Critic Rating</th>
             </tr>
  </thead>
        <?php

      foreach ($records as $record) { ?>
        <tr>
          <td><em>
            <?php echo htmlspecialchars($record['movies.name']); ?></em>
          </td>
          <td><?php echo htmlspecialchars(GENRE[$record['movies.genre']]); ?></td>
          <td> <?php echo htmlspecialchars($record['reviews.text']); ?></td>
          <td><?php echo htmlspecialchars(RATING[$record['reviews.rating']]); ?></td>
          <td><?php echo htmlspecialchars(RATING[$record['movies.rating']]); ?></td>

        </tr>
      <?php } ?>
      </table>
      </div>
    </div>
  </body>

  </html>
