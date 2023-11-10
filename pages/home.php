<?php
$title = 'Home';
$nav_home_class = 'active_page';

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
$form_feedback_classes = array(
  'genre' => 'hidden',
  'rating' => 'hidden',
  'name' => 'hidden'
);

$submit = false;
$err = false;
$db = init_sqlite_db('db/site.sqlite', 'db/init.sql');
$tag_query = "SELECT DISTINCT tag FROM tags";
$tag_list= array();

$sql = "SELECT movies.id AS 'movies.id', movies.genre AS 'movies.genre', movies.name AS 'movies.name', movies.rating AS 'movies.rating', images.id AS 'images.id', images.file_ext AS 'images.file_ext', images.file_name AS 'images.file_name', images.source AS 'images.source'
FROM movies
LEFT JOIN images ON (movies.id = images.movie_id)";

if(isset($_GET['tags'])) {
  $tag_request= $_GET['tags'];
  $tag_list = explode(',', $tag_request );
  $sql .= "LEFT JOIN tags ON (movies.id = tags.movie_id)
  WHERE tags.tag IN ('" . implode("', '", $tag_list) . "') ";
}


$tags = exec_sql_query($db, $tag_query)->fetchAll();

if (is_user_logged_in()) {

  define("MAX_FILE_SIZE", 10000000);

  $upload_feedback = array(
    'general_error' => False,
    'too_large' => False
  );


  $upload_source = NULL;
  $upload_file_name = NULL;
  $upload_file_ext = NULL;
  $upload_movie_name =Null;


  if (isset($_POST["upload"])) {
    if (empty($upload_source)) {
      $upload_source = NULL;
    }

    $upload = $_FILES['png-file'];


    $form_valid = True;
    $upload_movie_name = trim(filter_input(INPUT_POST, 'movie_name', FILTER_SANITIZE_STRING));
    $upload_genre = trim(filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING));
    $upload_rating = trim(filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_STRING));
    if (empty($upload_movie_name)) {
      $form_feedback_classes['name'] = '';
      $form_valid= false;
    }
    if (empty($upload_genre)) {
      $form_feedback_classes['genre'] = '';
      $form_valid = false;
    }
    if (empty($upload_rating)) {
      $form_feedback_classes['rating'] = '';
      $form_valid = false;
    }

    if ($upload['error'] == UPLOAD_ERR_OK) {

      $upload_file_name = basename($upload['name']);


      $upload_file_ext = strtolower(pathinfo($upload_file_name, PATHINFO_EXTENSION));

      if (!in_array($upload_file_ext, array('png'))) {
        $form_valid = False;
        $upload_feedback['general_error'] = True;
      }
    } else if (($upload['error'] == UPLOAD_ERR_INI_SIZE) || ($upload['error'] == UPLOAD_ERR_FORM_SIZE)) {
      $form_valid = False;
      $upload_feedback['too_large'] = True;
      $err = true;
    } else {
      $form_valid = False;
      $upload_feedback['general_error'] = True;
      $err = true;
    }



    if ($form_valid) {
      $result = exec_sql_query(
        $db,
        "INSERT INTO movies (name, genre, rating) VALUES (:name, :genre, :rating)",
        array(
          ':name' => $upload_movie_name,
          ':genre' => $upload_genre,
          ':rating' => $upload_rating
        )
      );

      if ($result){
        $movie_id = $db->lastInsertId('id');
        $result = exec_sql_query(
          $db,
          "INSERT INTO images (file_name, movie_id, file_ext, source) VALUES (:file_name, :movie_id, :file_ext, :source)",
          array(
            ':file_name' => $upload_file_name,
            ':movie_id' => $movie_id,
            ':file_ext' => $upload_file_ext,
            ':source' => $upload_source
          )
        );
    }
      if ($result) {

        $record_id = $db->lastInsertId('id');
        $upload_storage_path = 'public/uploads/movies/' . $record_id . '.' . $upload_file_ext;
        $submit = true;

        if (move_uploaded_file($upload["tmp_name"], $upload_storage_path) == False) {
          error_log("Failed to permanently store the uploaded file on the file server. Please check that the server folder exists.");
          $err = true;
          $submit = false;
        }
      }
    }
  }
}



$records = exec_sql_query($db, $sql)->fetchAll();
?>
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
  <main>
  <div class="intro">
    <?php if(is_user_logged_in()) { ?>
      <h1>Welcome Back!</h1>
        <p> Thanks for continuing to be an amazing member! We desperately want to hear about your new likes and dislikes in the world of movies. We're doing our best to keep the site updated and fresh with the hottest films. </p>
      <?php } else { ?>
        <h1> Welcome and Join out Community!</h1>
        <p> We're glad you're here. Check out our selection of movies and discover your new favorite! Join our community to add reviews and stay in the loop about whats good in the world <em> Film!</em> Our Catalog encompassess a plethora of movies from the old classics, to the original horror films to modern family fun and thrillers. Join us to partake in the experience.  </p>
        <?php } ?>
    </div>

<div class="box">
  <div class="sidebar">
    <form class="insert_login cap"
   method="get" action="">
   <h2 class="center">Filter Movies</h2>
      <div class="checkbox-list">
        <?php foreach ($tags as $tag) : ?>
          <?php $checked = in_array($tag['tag'], $tag_list) ? 'checked' : ''; ?>
          <div class="checkbox-item">
            <input type="checkbox" id="<?php echo $tag['tag']; ?>" name="tags" value="<?php echo $tag['tag']; ?>"<?php echo $checked; ?>>
            <label for="<?php echo $tag['tag']; ?>"><?php echo $tag['tag']; ?></label>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="align-right">
      <button type="submit" value="Submit"> Filter! </button>
      </div>
    </form>
      <div class="view-all">
        <a href="/">
          <button>View All</button>
        </a>
      </div>

    <section class="upload" id="upload">

<?php
if (is_user_logged_in()) { ?>

  <div class="insert_login dark">
    <?php if ($is_admin) { ?>
      <?php if ($submit) { ?>
          <h3 class="submit"> You Succesfully Added to the Catalog!</h2>
        <?php } ?>
        <?php if ($err) { ?>
          <h3 class="submit"> There was an error storing your file!</h2>
        <?php } ?>
          <h3>Upload Movie Cover</h3>
          <form action="/" method="post" enctype="multipart/form-data">


            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">

            <?php if ($upload_feedback['too_large']) { ?>
              <p class="feedback">We're sorry. The file failed to upload because it was too big. Please select a file that&apos;s no larger than 1MB.</p>
            <?php } ?>

            <?php if ($upload_feedback['general_error']) { ?>
              <p class="feedback">We're sorry. Something went wrong. Please select a PNG file to upload.</p>
            <?php } ?>

            <div class="label-input">
              <label for="movie_name">Movie Name:</label>
              <p class="feedback <?php echo $form_feedback_classes['name']; ?>">Please provide a movie genre.</p>
              <input id="movie_name" type="text" name="movie_name" placeholder="Enter movie name">
            </div>

            <div class="label-input">
              <label for="upload-file">Cover Image:</label>
              <input id="upload-file" type="file" name="png-file" accept=".png">
            </div>

            <div class="label-input">
              <label for="upload-source" class="optional">Source URL:</label>
              <input id='upload-source' type="url" name="source" placeholder="URL where found. (optional)">
            </div>

            <div class="label-input">
            <label for="genre">Genre:</label>
            <p class="feedback <?php echo $form_feedback_classes['genre']; ?>">Please provide a movie genre.</p>
            <select id="genre" name="genre">
            <option value="">SELECT GENRE</option>
              <?php foreach (GENRE as $code => $genre) { ?>
                <option value="<?php echo $code; ?>"><?php echo $genre; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="label-input">
            <label for="rating">Rating:</label>
            <p class="feedback <?php echo $form_feedback_classes['rating']; ?>">Please provide a movie genre.</p>
            <select id="rating" name="rating">
            <option value="">SELECT RATING</option>
              <?php foreach (RATING as $code => $rating) { ?>
                <option value="<?php echo $code; ?>"><?php echo $rating; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="align-right">

            <div class="align-right">
              <button type="submit" name="upload">Upload</button>
            </div>
          </form>
    </div>
    <?php }  else { ?>
      <h2> Welcome Back! </h2>
      <h4> While at this time you cannot upload a movie into the catalog , please feel free to leave a review of any of these films if you've seen it recenty:  <a href="/reviews"> We Can't Wait to Hear About Your Viewing Experience! </a></h4>
    <?php } ?>
<?php } else { ?>
    <div class="insert_login dark">
              <h3> Please Login:</h3>
              <?php echo login_form('/', $session_messages);} ?>
            </div>
  </div>
  <div class="main">
    <section class="gallery">
      <h3> Movie List:</h3>
      <p class="center darken"> Click on the movie title to view the entry</p>
      <?php
      if (count($records) > 0) { ?>
        <div class="gallery-container">
          <?php foreach ($records as $record) {
            $file_url = '/public/uploads/movies/' . $record['images.id'] . '.' . $record['images.file_ext'];
          ?>
            <ul>
              <li class="title"><a href="entry?movie_id=<?php echo htmlspecialchars($record['movies.id']); ?>">
              <em><?php echo htmlspecialchars($record['movies.name']); ?></em> </a><?php echo htmlspecialchars(RATING[$record['movies.rating']]); ?>
          </a> <?php echo htmlspecialchars(RATING[$record['movies.rating']]); ?></li>
              <li class="small"><?php echo htmlspecialchars(GENRE[$record['movies.genre']]); ?></li>
              <li>
                <a href="<?php echo htmlspecialchars($file_url) ?>" title="Download <?php echo htmlspecialchars($record['images.file_name']); ?>" download>
                  <div class="thumbnail">
                    <img src="<?php echo htmlspecialchars($file_url); ?>" alt="<?php echo htmlspecialchars($record['images.file_name']); ?>">
                    <cite><?php echo htmlspecialchars($record['images.source']); ?></cite>
                  </div>
                    <img alt="" src="/public/images/download-icon.png">
                </a>
              </li>
            </ul>
          <?php } ?>
        </div>
      <?php
      } else { ?>
        <p> There are no movies.</p>
      <?php } ?>
    </section>
  </div>
    </div>
  </main>
</body>

</html>
