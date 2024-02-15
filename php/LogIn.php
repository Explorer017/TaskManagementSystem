<?php require "../css/css.php"?>
<?php require "components/pagetop.php"?>
<!-- Function for handling the form -->
<?php
    // Start session for storing credentials
    session_start();
    // Define variables used in form, as well as error variables
    $username = $password = "";
    $usernameError = $passwordError = $loginError = "";
    $isFormValid = true;

    // Check if form has been submitted, and then check if each item is present
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (empty($_POST["usernameInput"])){
            $usernameError = "Username is Required";
            $isFormValid = false;
        } else {
            $username = htmlspecialchars($_POST["email"]);
        }

        if (empty($_POST["passwordInput"])){
            $passwordError = "Password is Required";
            $isFormValid = false;
        } else {
            $password = htmlspecialchars($_POST["password"]);
        }

        // Check response against database
        if ($isFormValid){
            $signedIn = SignIn($username, $password);
            if (!$signedIn){
                $loginError = "Incorrect Email or Password";
            }
            else {
                $_SESSION["credentials"] = $signedIn;
                header("Location: index.php");
            }
        }
    }
?>

<div class="d-flex justify-content-center align-items-center background h-100" id="login-box">
    <div class="w-50">
        <h1 class="text-center">Task Management System</h1>
        <h3 class="text-center">Login</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <p class="text-danger"><?php echo $loginError?></p>
            <div class="mb-3">
                <label for="usernameInput">Username</label>
                <input type="text" class="form-control" id="usernameInput"/>
                <p class="text-danger"><?php echo $usernameError?></p>
            </div>
            <div class="mb-3">
                <label for="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput"/>
                <p class="text-danger"><?php echo $passwordError?></p>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <button type="submit" class="btn btn-primary text-center">Submit</button>
            </div>
        </form>
        <hr/>
        <p class="text-center">OR</p>
        <div class="d-flex justify-content-center align-items-center">
            <button class="btn btn-secondary text-center">Register</button>
        </div>
        
    </div>
</div>

<?php require "components/pagebottom.php"?>