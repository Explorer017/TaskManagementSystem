<?php require "../css/css.php"?>
<?php require "components/pagetop.php"?>

    <div class="d-flex justify-content-center align-items-center background h-100" id="login-box">
        <div class="w-50">
            <h1 class="text-center">Task Management System</h1>
            <h3 class="text-center">Register</h3>
            <form>
                <div class="mb-3">
                    <label for="usernameInput">Username</label>
                    <input type="text" class="form-control" id="usernameInput"/>
                </div>
                <div class="mb-3">
                    <label for="emailInput">Email</label>
                    <input type="text" class="form-control" id="emailInput"/>
                </div>
                <div class="mb-3">
                    <label for="passwordInput">Password</label>
                    <input type="password" class="form-control" id="passwordInput"/>
                </div>
                <div class="mb-3">
                    <label for="confirmPasswordInput">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPasswordInput"/>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary text-center">Submit</button>
                </div>
            </form>
            <hr/>
            <p class="text-center">Already have an account?</p>
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-secondary text-center">Log In</button>
            </div>

        </div>
    </div>

<?php require "components/pagebottom.php"?>