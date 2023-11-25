        <?php include_once("header.php")?>

<div class="container">
<h2 class="my-3">Register new account</h2>

<!-- Create auction form -->
<form method="POST" action="process_registration.php">
  <div class="form-group row">
    <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="email" placeholder="Email" name="email">
      <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  <div class="form-group row">
    <label for="username" class="col-sm-2 col-form-label text-right">User Name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="username" placeholder="User Name" name="username">
      <small id="username" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" placeholder="Password" name="password">
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
  <div class="form-group row">
  <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
<div class="col-sm-10">
  <input type="password" class="form-control" id="passwordConfirmation" placeholder="Enter password again" name="confirmPassword">
  <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>

  <div class="form-check mb-3">
    <input type="checkbox" class="form-check-input" onclick="showPassword()"> Show Password
  </div>


  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="emailNotifications" name="emailNotifications">
    <label class="form-check-label" for="emailNotifications">Email Notifications (bid updates)</label>
  </div>
</div>

<script>
function showPassword() {
    var passwordInput = document.getElementById("passwordConfirmation");


    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}
</script>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-sm-20 offset-sm-2 mx-auto">
      <button type="submit" class="btn btn-primary btn-lg" style="width: 300px;">Register</button>
    </div>
  </div>
</form>
<div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a></div>

<?php include_once("footer.php")?>
