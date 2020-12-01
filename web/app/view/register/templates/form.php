<div class="container">
   <div class="row">
    <div class="col-md-offset-3 col-md-6">
        <form method="POST" class="form-horizontal">
            <span class="heading">Log In</span>
            <div class="form-group">
                 <input type="email" class="form-control" id="inputEmail" name="login" placeholder="E-mail">
                 <i class="fa fa-user"></i>
            </div>
            <div class="form-group help">
                 <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                 <i class="fa fa-lock"></i>
            </div>
            <div class="form-group">
                 <button type="submit" name="Log In" class="btn btn-default login-btn">Log In</button>
                 <button type="submit" name="Register" class="btn btn-default register-btn">Rerister</button>
            </div>
            <div class="display_errors">
                <span class="error"><?=$errors?></span>
            </div>
        </form>   
    </div>
   </div>
  </div>