<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:og="http://ogp.me/ns#"
  xmlns:fb="http://www.facebook.com/2008/fbml"
  xml:lang="<?php print $language->language ?>" 
  lang="<?php print $language->language ?>" 
  dir="<?php print $language->dir ?>"
>
  <head>
    <title><?php print $head_title; ?></title>
    <?php print $head; ?>
    <?php print $styles; ?>
    <?php print $scripts; ?>
    <meta name="google-site-verification" content="pZgSESN5QxVKkNG8sfYvuTkAG_sSYMh6IyN0em74Gso" />
    <meta property="og:site_name" content="Level Business" />
    <meta property="fb:app_id" content="206040062739797" />
    <meta property="og:image" content="http://<?php print $_SERVER['HTTP_HOST'] . '/' . drupal_get_path('theme','level_omega'); ?>/images/level_fb_like.png" />  
    <?php 
    // TODO: add fb_app id and fb_admins as system variables 
    // TODO, we can get some of the types from the SIC code for bars, hotes etc. 
    // TODO move this to a seperate module
      if (arg(1) == 'company') {
        global $base_root;
        $path = $base_root . request_uri();
  
        print '<meta property="og:type" content="company" />';
        print '<meta property="og:title" content="'. trim($title) . '" />';
        // TODO: change this to be just http://levelbusiness.com
        print '<meta property="og:url" content="http://'. $_SERVER['HTTP_HOST'] . request_uri() .'" />';
      }
    ?> 
  </head>

  <body class="<?php print $body_classes; ?>">

    <?php if($help || $messages): ?>
      <div id="system_messages">
         <?php print $help; ?>
         <?php print $messages; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($admin)) print $admin; ?>

    <!-- Wrap all page elements, outside of grid -->
    <div id="page" class="clearfix">

      <!-- Logo and login/out/register links -->
      <div id="header_container" class="container_4 clearfix">

        <div id="site-header" class="grid_4 alpha">

          <div id="branding" class="grid_1 alpha omega">

            <a href="http://<?php print $_SERVER['HTTP_HOST'] ?>"><img src="<?php print drupal_get_path('theme','level_omega'); ?>/images/Logo.png" alt="Level business logo" /></a>

          </div><!-- /#branding -->
  
          <?php
            if (isset($primary_links)) {
              print theme('links', $primary_links, array('class' => 'links primary-links'));
            }
            if ($header_last) {
              print $header_last;
            }
          ?>
        </div><!-- /#site-header -->

      </div><!-- /#header-container -->

     <?php if (!empty($page_tools)): ?>           
       <div id="page_tools" class="container_4 clearfix">
         <?php print $page_tools; ?>
       </div>
     <?php endif; ?>

      <!-- Main page content -->
      <div id="content" class="container_4 clearfix">

        <div id="main_content_container" class="grid_4 clearfix alpha omega">
        <?php 
          // top_bar can be used for bold page titles such as company names on the company profile page. It will allways span the page after the left column.
          if($top_bar || $title):
        ?>
          <div id="top_bar" class="clearfix">
            <?php  if ($top_bar): // TOP bar used in place of standard page title ?>
              <?php print $top_bar; ?>
            <?php else:?>
              <h1 class="title" id="page-title"><?php print $title; ?></h1>
            <?php endif; ?>
          </div><!-- /#top_bar -->
        <?php endif; ?>

        <div id="main_wrapper" class="grid_4 alpha omega<?php // print $content_classes; ?> clearfix">
          
          <?php if ($tabs): ?>
            <div id="content-tabs">
              <?php print $tabs; ?>
            </div><!-- /#content-tabs -->
          <?php endif; ?>
          
          <!-- Search block and text (and 'Companies in the news') -->
          <?php if($content_top): ?>
            <div id="content-top">
              <?php print $content_top; ?>
            </div><!-- /#content-top -->
          <?php endif; ?>

          <!-- Rest of page content -->
          <div id="main-content">
            <?php print $content; ?>
          </div><!-- /#main-content -->

          <?php if($content_bottom): ?>
            <div id="content-bottom">
              <?php print $content_bottom; ?>
            </div><!-- /#content-bottom -->
          <?php endif; ?>

          </div><!-- /#main-wrapper -->

          <?php if($right_sidebar): ?>
            <div id="right_sidebar" class="grid_1 omega">
              <?print $right_sidebar ?>
            </div>
          <?php endif;?> 

        </div><!-- /#main_content_container -->

      </div><!-- /#content -->

      <div id="footer_wrapper" class="container_4">
        <div id="footer_main" class="grid_4 clearfix alpha">
        <?php if($footer) {
          print $footer;
        }?>
        </div><!-- /#footer_main -->
      </div><!-- /#footer_wrapper -->

    </div><!-- /#page -->

    <?php print $closure; ?>

    <script>
      FB.XFBML.parse();
    </script>
  </body>
</html>
