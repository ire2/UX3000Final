<?php
$title = "Create an Account!";
$nav_create_class = "active_page";
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
    <title>Create an account</title>
  </head>

  <body>
  <?php include 'includes/header.php'; ?>
    <main>
        <?php if (is_user_logged_in()) { ?>
            <div class="intro">
            <h2>Welcome! <em><?php echo htmlspecialchars($current_user['name']); ?> </em>You are already part of the community </strong>! </h2></div>
        <?php } else { ?>
            <h2 class="signup" id="h2signup"> Create an account with us and join our cinephile community! </h2>
                <?php echo signup_form('/', $session_messages); } ?>
    </main>
    </body>
</html>
