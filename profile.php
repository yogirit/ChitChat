<!DOCTYPE html>
<html>
	<head>
		<?php 
			include("header.php");
			if ($user->isUser($_REQUEST['profile_username'])){
				$profile_user = new User($conn, $_REQUEST['profile_username']);
			} else {
				header('Location: index.php');
			}
		?>
	  <title><?php echo $profile_user->getFirstAndLastName(); ?> - Profile</title>
		<meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
<style type="text/css">
	/*Profile card*/
	.profile-card .profile-card-img-block{
	    float:left;
	    width:100%;
	    height:150px;
	    overflow:hidden;
	}
	.profile-card .profile-card-body{
	    position:relative;
	}
	.profile-card .profile {
	    position: absolute;
	    top: -62px;
	    left: 50%;
	    width:100px;
	    margin-left: -50px;
	}
	.profile-card .profile-card-img-block{
	    position:relative;
	}
	.profile-card .profile-card-img-block .btn-over-img{
		position: relative;
		top: -140px;
    right: 10px;
	}
	.profile-card .profile-card-img-block > .profile-info-box{
	    position:absolute;
	    width:100%;
	    height:100%;
	    color:#fff;
	    padding:20px;
	    text-align:center;
	    font-size:14px;
	   -webkit-transition: 1s ease;
	    transition: 1s ease;
	    opacity:0;
	}
	.profile-card .profile-card-img-block:hover > .profile-info-box{
			border-radius: .25rem!important;
	    opacity:1;
	    -webkit-transition: all 1s ease;
	    transition: all 1s ease;
	}
</style>
<body onbeforeunload="countPage();">
	<div role="main" class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="card profile-card shadow p-3 mb-4 bg-white rounded">
				  <div class="profile-card-img-block">
				    <div class="profile-info-box bg-primary">
				    	<?php echo $profile_user->getBio(); ?>
				    </div>
				    <img class='rounded cover-img' src='<?php echo $profile_user->getCoverPic(); ?>'>     
				    <?php
				    	if($profile_user->getUsername() == $user->getUsername()){
				    		echo "<a href='settings.php' class='btn-over-img btn btn-primary float-right'><i class='fa fa-pencil'></i>&nbsp;Edit</a>";
				    	} else {
				    		if ($user->isFriend($profile_user->getUsername())){
									$friend_button = "<button id='" . $profile_user->getUsername() . "' class='btn-over-img btn btn-sm btn-danger float-right addfriend' onclick='friend(this);friendAction(this);reload();' value='0'>Remove Friend</button>";
								} elseif ($request->didReceiveRequest($profile_user->getUsername()) == 1) {
									$friend_button = "<button id='" . $profile_user->getUsername() . "' class='btn-over-img btn btn-sm btn-success float-right addfriend' onclick='friend(this);friendAction(this);reload();' value='1'>Accept Request</button>";
								} elseif ($request->didSendRequest($profile_user->getUsername()) == 1) {
									$friend_button = "<button id='" . $profile_user->getUsername() . "' class='btn-over-img btn btn-sm btn-warning float-right addfriend' onclick='friend(this);friendAction(this);' value='2'>Cancel Request</button>";
								} else {
									$friend_button = "<button id='" . $profile_user->getUsername() . "' class='btn-over-img btn btn-sm btn-success float-right addfriend' onclick='friend(this);friendAction(this);' value='3'>Add Friend</button>";
								}
								echo $friend_button;
				    	}
				    ?>
				  </div>
				  <div class="profile-card-body pt-5">
				    <img src="<?php echo $profile_user->getProfilePic(); ?>" alt="profile-image" class="profile border border-default padding-5-circle"/>
				    <center><h6 class="text-primary"><?php echo $profile_user->getFirstAndLastName(); ?></h6></center>
				  </div>
				</div>
				<?php
				  $profile_fullname = "<div class='row text-dark'><div class='col-4 pr-0'>Name </div><div class='col-8 px-0 float-left'>- " . $profile_user->getFirstAndLastName() . "</div></div>";
				  $profile_user_username = "<div class='row text-dark'><div class='col-4 pr-0'>Username </div><div class='col-8 px-0 float-left'>- " . $profile_user->getUsername() . "</div></div>";
				  $profile_user_friend_count = "<div class='row text-dark'><div class='col-4 pr-0'>Friends </div><div class='col-8 px-0 float-left'>- " . $profile_user->getNumOfFriends() . "</div></div>";
				  $profile_user_post_count = "<div class='row text-dark'><div class='col-4 pr-0'>Posts </div><div class='col-8 px-0 float-left'>- " . $profile_user->getNumOfPosts() . "</div></div>";
				  if($profile_user->getBio() != '')
				  	$profile_bio = "<div class='row text-dark'><div class='col-4 pr-0'>Bio </div><div class='col-8 px-0 float-left'>- " . $profile_user->getBio() . "</div></div>";
				  else
				  	$profile_bio = "";
				  echo "<div class='card mb-3'><div class='card-body'><div class='row col-12'><h5>Account Info</h5></div>" . $profile_fullname . $profile_user_username . $profile_user_friend_count . $profile_user_post_count . $profile_bio . "</div></div>";

					$birthday = $profile_user->getBirthday();
					$phone_no = $profile_user->getPhoneNo();
					$gender = $profile_user->getGender();
					$city = $profile_user->getCity();
					$state = $profile_user->getState();
					$country = $profile_user->getCountry();
					$school  = $profile_user->getSchool();
					$college = $profile_user->getCollege();
					$location = '';
					if ( $city != ''){
						$location = $city;
						if($state != ''){ $location .= ", " . $state; }
						if($country != ''){ $location .= ", " . $country; }
					} elseif ( $state != '') {
						$location = $state;
						if($country != ''){ $location .= ", " . $country; }
					} elseif ( $country != '') {
						$location = $country;
					}
					if( $birthday != '' || $gender != '' || $phone_no != '' || $school != '' || $college != '' || $location != ''){
						if( $gender != ''){
							$gender = "<div class='row text-dark'><div class='col-4 pr-0'>Gender </div><div class='col-8 px-0 float-left'>- " . $gender . "</div></div>";
						}
						if( $birthday != ''){
							$birthday = "<div class='row text-dark'><div class='col-4 pr-0'>Birthday </div><div class='col-8 px-0 float-left'>- " . date( 'd M Y', strtotime($birthday)) . "</div></div>";
						}
						if( $phone_no != ''){
							$phone_no = "<div class='row text-dark'><div class='col-4 pr-0'>Phone No </div><div class='col-8 px-0 float-left'>- " . $phone_no . "</div></div>";
						}
						if( $location != ''){
							$location = "<div class='row text-dark'><div class='col-4 pr-0'>Address </div><div class='col-8 px-0 float-left'>- " . $location . "</div></div>";
						}
						if( $school != ''){
							$school = "<div class='row text-dark'><div class='col-4 pr-0'>School </div><div class='col-8 px-0 float-left'>- " . $school . "</div></div>";
						}
						if( $college != ''){
							$college = "<div class='row text-dark'><div class='col-4 pr-0'>College </div><div class='col-8 px-0 float-left'>- " . $college . "</div></div>";
						}
						echo  "<div class='card mb-3'><div class='card-body'><div class='row col-12'><h5>Personal Info</h5></div>" . $gender . $birthday . $phone_no . $location . $school . $college . "</div></div>";
					}
				?>
			</div>

			<div class="col-md-8">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs mb-3">
				  <li class="nav-item">
				    <a class="nav-link active" data-toggle="tab" href="#profile_post_box">Profile Post</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" data-toggle="tab" href="#friend_box">Friends</a>
				  </li>
				  <?php
				  	// show this tab when profile_user is not main user
				  	if($user->getUsername() != $profile_user->getUsername()){
				  		echo "<li class='nav-item'>
									    <a class='nav-link' data-toggle='tab' href='#message_box'>Message</a>
									  </li>";
				  	} 
				  ?>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content mb-2">

				  <!--- post by profile_user and user if user and profile_user are same make user_to none--->
				  <div class="tab-pane active" id="profile_post_box">
				  	<div class="card shadow p-3 mb-4 bg-white rounded post_block">
						  <div class="form-group">
						    <textarea id="postBody" class="form-control border border-primary" rows="5" maxlength="60000" id="post" placeholder="Post Something here..." style="min-height:100px;max-height:200px;"></textarea>
						    <div class="form-row">
						      <div class="col">
						        <input type="file" id="postImage"  class="form-control-file btn float-left pl-0 pb-0" accept="image/*">
						        <input type="hidden" id='userTo' value='<?php if($user->getUsername() != $profile_user->getUsername()){echo $profile_user->getUsername();}else{ echo '';} ?>'>
						        <input type="hidden" id='imageLocation' value=''>
						        <button type="submit" class="btn btn-primary float-right" id='sendPostBtn'><i class="fa fa-pencil"></i>&nbsp;Post</button>
						      </div>
						    </div>
						  </div>
						</div>
				  	<div class="posts_area">
						</div>
						<img id="loading" src="assets/images/icons/loading.gif">
				  </div>

				  <!---profile_user friend_list--->
				  <div class="tab-pane container-fluid fade" id="friend_box">
				  	<?php include 'infinite_friends_loading.php' ?>
				  </div>

				  <!---message tab for other user's not for profile_user--->
				  <?php
				  	if($user->getUsername() != $profile_user->getUsername()){
				  		echo "<div class='tab-pane container-fluid fade' id='message_box'><iframe src='message_card.php?user_to=" . $profile_user->getUsername() . "' class='border border-default' style='height:514px;width:100%;'></iframe></div>";
				  	} 
				  ?>
				</div>
			</div>
		</div>
	</div>
  <?php include("footer.php"); ?>
</body>
</html>

<!--- image upload model --->
<div id="myUploadImageModel" z-index="-2" class="modal" role="dialog">
 <div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Crop & Upload Image</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      <div class="text-center">
        <div id="uploadedImageDemo"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-success" id="cropImage">Upload Image</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
   </div>
  </div>
</div>

<script>
	/*
		var postRequestResponse : true if response get else false if post loading request is running
		var profileUsername : have current username

		deleteAllMessages() : delete messages
		reload() : called to reload the page to default uri
		loadPosts() : called whenever user delete post or scroll down
		scrollDown(id) : used to lock scrollbar to scrolldown an id and than release scrollbar
		deletePost(obj) : deletePost called by element which needs to be deleted and load new post after deleting

		$(document).ready({ load first time 10 posts })
		$(document).ready({ make corppie object to crop the uploaded image })
		$('#sendPostBtn') { send post if either image or post_body are not empty }
	*/
	var postRequestResponse = true;
	var profileUsername = '<?php echo $profile_user->getUsername(); ?>';
	var profileUserFullname = '<?php echo $profile_user->getFirstAndLastName(); ?>';
	function deleteAllMessages(){
		bootbox.confirm({
      message: "this will delete all messages by you to " + profileUserFullname + '.',
      buttons: {
        confirm: { label: 'Yes', className: 'btn-success' },
        cancel: { label: 'No', className: 'btn-danger' }
      },
      callback: function (result) {
        if(result){
          $.post("includes/delete_all_messages.php", {name : profileUsername},function(data){
						location.href = 'profile.php?profile_username=' + profileUsername;
					});
        }
      }
    });
	}
	function reload(){
		location.href = 'profile.php?profile_username=<?php echo $profile_user->getUsername(); ?>';
	}
  function loadPosts(){
		var last_post_id = $('.post:last').attr('id');
		var noMorePosts = $('.posts_area').find('.noMorePosts').val();
		if ( noMorePosts == 'false' && postRequestResponse) {
			postRequestResponse = false;
			$('#loading').show();
			$('.posts_area').find('.noMorePosts').remove(); 
			$.post("includes/load_profile_posts.php", {last_post_id : last_post_id, name : profileUsername}, function(data){
				$('.posts_area').find('.noMorePostsText').remove();
				$('.posts_area').append(data);
				$('#loading').hide();
				postRequestResponse = true;
			});
		}
  }
  function scrollDown(id){
    var myInterval = false;
    var found = true;
    myInterval = setInterval(AutoScroll, 1500);
    function AutoScroll() {
      if($('#loading').is(':visible') == false){
        var iScroll = $(window).scrollTop();
        iScroll = iScroll + 1500;
        $('html, body').animate({
          scrollTop: iScroll
        }, 1500);
      }
      loadPosts();
    }
    var scrollHandler = function () {
      var iScroll = $(window).scrollTop();
      if (iScroll == 0) {
        myInterval = setInterval(AutoScroll, 1500);
      }
      var last_id = $('.post:last').attr('id');
      if (iScroll + $(window).height() == $(document).height() || last_id < id) {
        clearInterval(myInterval);
        if($('.posts_area').find('.post#'+id).length > 0){
          $('html, body').animate({ scrollTop: $('#'+id).offset().top-80 }, 2000);
        } else {
          $('html, body').animate({ scrollTop: $('#'+last_id).offset().top-80 }, 2000);
          found = false;
        }
        $(window).unbind('scroll');
        if(!found){
          alert('no found');
        }
        $(window).scroll(function(){
          if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
            loadPosts();
          }
        });
      }
    }
    $(window).scroll(scrollHandler);
  }
  function deletePost(obj){
    bootbox.confirm({
      message: "Delete the post .Are you Sure?",
      buttons: {
        confirm: { label: 'Yes', className: 'btn-success' },
        cancel: { label: 'No', className: 'btn-danger' }
      },
      callback: function (result) {
        if(result){
          $.post("includes/delete_post.php", {post_id : obj.id}, function(data){
            var element = '.post#';
            $(element.concat(obj.id)).fadeOut();
            loadPosts();
          });
        }
      }
    });
  }
	$(document).ready(function() {
		$('#loading').show();
		postRequestResponse = false;
		$.post("includes/load_profile_posts.php", {last_post_id : 0, name : '<?php echo $profile_user->getUsername(); ?>'}, function(data){
			$('#loading').hide();
			$('.posts_area').html(data);
			postRequestResponse = true;
			<?php if(isset($_REQUEST['post_id'])) { echo "scrollDown(" . $_REQUEST['post_id'] . ");";} ?>
		});
		$(window).scroll(function() {
			if ( (window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
				loadPosts();
			}
		});
	});
  $(document).ready(function(){
   $image_crop = $('#uploadedImageDemo').croppie({
      enableExif: true,
      viewport: { width:200, height:200, type:'square' },
      boundary:{ width:300, height:300 }
    });
  $('#postImage').on('change', function(){
      var fileExtension = ['jpeg', 'jpg', 'png', 'gif'];
      if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        alert("Only formats are allowed : "+fileExtension.join(', '));
      } else {
        var reader = new FileReader();
        reader.onload = function (event) {
          $image_crop.croppie('bind', {
            url: event.target.result
          }).then(function(){
            console.log('jQuery bind complete');
          });
        }
        reader.readAsDataURL(this.files[0]);
        $('#myUploadImageModel').modal('show');
      }
    });
    $('#cropImage').click(function(event){
      $image_crop.croppie('result', { type: 'canvas', size: 'original' }).then(function(response){
        $.post("upload.php",{image : response, targetDir : "assets/images/post_pics/"}, function(data){
          $('#myUploadImageModel').modal('hide');
          $('#imageLocation').val(data);
        });
      })
    });
  });
  $('#sendPostBtn').click(function(event){
    if($('#postBody').val().trim().length || $('#imageLocation').val()){
      $.post("includes/save_post.php", { userTo : $('#userTo').val(), postBody : $('#postBody').val(), imageLocation : $('#imageLocation').val()} , function(data) {
          location.href = 'profile.php?profile_username=' + profileUsername;
      })
    }
  });
</script>