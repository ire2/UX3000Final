<header>
  <h1 id="title"><?php echo htmlspecialchars($title); ?> </h1>

  <nav id="menu">
    <ul>
      <li class="<?php echo $nav_home_class; ?>"><a href="/">Home: Catalog</a></li>
      <li class="<?php echo $nav_movies_class; ?>"><a href="/reviews">Movie Reviews</a></li>
      <li class="<?php echo $nav_create_class; ?>"><a href="/create">Create an Account</a></li>

      <?php if (is_user_logged_in()) { ?>
        <li class="float-right"><a href="<?php echo logout_url(); ?>">Sign Out</a></li>
      <?php } ?>
    </ul>
  </nav>
</header>
