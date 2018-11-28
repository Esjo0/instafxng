<?php
require_once 'init/initialize_general.php';
$thisPage = "Education";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instaforex Nigeria | Forex Trading Seminar</title>
    <meta name="title" content="Instaforex Nigeria | Forex Traders Forum" />
    <meta name="keywords" content="instaforex, forex seminar, forex trading seminar, forex traders froum, how to trade forex, trade forex, instaforex nigeria.">
    <meta name="description" content="Learn how to trade forex, get free information about the forex market in our forex traders forum">
    <link rel="stylesheet" href="css/free_seminar.css">
    <?php require_once 'layouts/head_meta.php'; ?>
    <link rel="stylesheet" href="css/prettyPhoto.css">
</head>
<body>
<?php require_once 'layouts/header.php'; ?>
<!-- Main Body: The is the main content area of the web site, contains a side bar  -->
<div id="main-body" class="container-fluid">
    <div class="row no-gutter">
        <?php require_once 'layouts/topnav.php'; ?>
        <!-- Main Body - Content Area: This is the main content area, unique for each page  -->
        <div id="main-body-content-area" class="col-md-8 col-md-push-4 col-lg-9 col-lg-push-3">

            <!-- Unique Page Content Starts Here
            ================================================== -->
                <div class="super-shadow page-top-section">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 style="margin: 0;"><?php echo $row['forum_title']; ?></h3>
                            <p><strong><span style="color: black;"><a href="<?php echo $row['link_url']; ?>"><?php echo $row['link_text']; ?></a></span></strong>
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <img src="<?php echo $row['image_path']; ?>" alt="" class="img-responsive"/>
                        </div>
                    </div>
                    </div>

                    <div class="section-tint super-shadow">
                    <div class="row text-center">
                        <div class="col-sm-12 text-danger">
                            <h3><strong><?php echo $row['share_thoughts_header']; ?></strong></h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $row['share_thoughts_body']; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h5>See photos from previous traders forums.</h5>

                            <ul class="gallery clearfix photo_g">
                                <li><a href="images/traders-forum/traders-forum-1.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-1.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-2.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-2.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-3.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-3.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-4.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-4.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-5.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-5.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-6.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-6.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-7.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-7.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-8.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-8.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-9.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-9.jpg"
                                                     alt=" "/></a></li>
                                <li><a href="images/traders-forum/traders-forum-10.jpg" rel="prettyPhoto[gallery1]"
                                       title=""><img src="images/traders-forum/thumbnails/traders-forum-10.jpg"
                                                     alt=" "/></a></li>
                            </ul>
                        </div>
                    </div>
                    <br/>

                    <div class="row" id="signup-section">

                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (isset($message_success)) { ?>
                                    <div class="alert alert-success">
                                        <a href="#" class="close" data-dismiss="alert"
                                           aria-label="close">&times;</a>
                                        <strong>Success!</strong> <?php echo $message_success; ?>
                                    </div>
                                <?php } ?>

                                <?php if (isset($message_error)) { ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert"
                                           aria-label="close">&times;</a>
                                        <strong>Oops!</strong> <?php echo $message_error; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <span id="opt"></span>

                        <section id="more">

                            <div class="row">
                                <div class="col-sm-12">
                                    <form data-toggle="validator" id="signup-form" role="form" method="post"
                                          action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>">
                                        <h3 class="text-uppercase text-center signup-header">RESERVE A SEAT NOW</h3>
                                        <br/>
                                        <input name="date"
                                               class="form-control" type="hidden"
                                               value="<?php echo $row['scheduled_date']; ?>" >
                                        <input name="forum_title"
                                               class="form-control" type="hidden"
                                               value="<?php echo $row['forum_title']; ?>" >
                                        <div class="form-group has-feedback">
                                            <label for="name" class="control-label">Your Full Name</label>
                                            <div class="input-group margin-bottom-sm">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-user fa-fw"></i></span>
                                                <input type="text" class="form-control" id="name" name="name"
                                                       placeholder="Your Name" data-minlength="5" required>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label for="email" class="control-label">Your Email Address</label>
                                            <div class="input-group margin-bottom-sm">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-envelope-o fa-fw"></i></span>
                                                <input type="email" class="form-control" id="email" name="email_add"
                                                       placeholder="Your Email" data-error="Invalid Email" required>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block with-errors"></div>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <label for="phone" class="control-label">Your Phone Number</label>
                                            <div class="input-group">
                                                    <span class="input-group-addon"><i
                                                                class="fa fa-phone fa-fw"></i></span>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                       placeholder="Your Phone" data-minlength="11" maxlength="11"
                                                       required>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                            <div class="help-block">Example - 08031234567</div>
                                        </div>

                                        <div class="form-group">
                                            <label for="venue" class="control-label">Choose your venue</label>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="venue"
                                                              value="Diamond Estate" checked required>Block 1A, Plot
                                                    8, Diamond Estate, LASU/Isheri road, Isheri Olofin,
                                                    Lagos.</label>
                                            </div>
                                            <div class="radio">
                                                <label><input id="venue" type="radio" name="venue"
                                                              value="Ajah Office" required>Block A3, Suite 508/509
                                                    Eastline Shopping Complex, Opposite Abraham Adesanya Roundabout,
                                                    along Lekki - Epe expressway, Lagos.</label>
                                            </div>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="entry_channel" class="control-label">How did you hear about the forum?</label>

                                            <div class="form_group">
                                                <select id="entry_channel" class="form-control" name="entry_channel" required='required'>
                                                    <option value="">Choose an option</option>
                                                    <option value="1">Facebook</option>
                                                    <option value="2">Instagram</option>
                                                    <option value="3">Twitter</option>
                                                    <option value="4">WhatsApp</option>
                                                    <option value="5">Email Invite</option>
                                                    <option value="6">SMS Invite</option>
                                                    <option value="7">Instafxng Website</option>
                                                    <option value="8">Friend</option>
                                                    <option value="9">Other means</option>
                                                </select>
                                            </div>

                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>

                                        </div>
                                        <div><br /></div>

                                        <div class="form-group"><div class="g-recaptcha" data-sitekey="6LcKDhATAAAAAF3bt-hC_fWA2F0YKKpNCPFoz2Jm"></div></div>
                                        <div class="form-group">
                                            <button type="submit" name="reserve_seat" class="btn btn-default btn-lg">Reserve Your Seat&nbsp;<i class="fa fa-chevron-circle-right"></i></button>
                                        </div>

                                        <small>All fields are required</small>
                                    </form>
                                </div>
                            </div>

                        </section>

                    </div>
                <div class="row text-center">
                    <h2 class="color-fancy">For further enquiries, please call 08182045184, 07081036115</h2>
                </div>
                </div>
            <!-- Unique Page Content Ends Here
            ================================================== -->

        </div>
        <!-- Main Body - Side Bar  -->
        <div id="main-body-side-bar" class="col-md-4 col-md-pull-8 col-lg-3 col-lg-pull-9 left-nav">
            <?php require_once 'layouts/sidebar.php'; ?>
        </div>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
</body>
</html>