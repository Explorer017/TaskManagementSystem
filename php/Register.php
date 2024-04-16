<?php require "../css/css.php"?>
<?php require "components/pagetop.php"?>

<?php
// Include LoginFunctions file
require "functions/LoginFunctions.php";
// Start session for storing credentials
session_start();
// Define variables used in form, as well as error variables
$isFormValid = true;
$firstName = $lastName = $username = $email = $password = $confirm_password = "";
$firstNameError = $lastNameError = $usernameError = $emailError = $passwordError = $confirmPasswordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["firstName"])) {
        $firstNameError = "First Name is required";
        $isFormValid = false;
    } else {
        $firstName = htmlspecialchars($_POST["firstName"]);
    }
    if (empty($_POST["lastName"])) {
        $lastNameError = "Last Name is required";
        $isFormValid = false;
    } else {
        $lastName = htmlspecialchars($_POST["lastName"]);
    }
    if (empty($_POST["username"])) {
        $usernameError = "Username is Required";
        $isFormValid = false;
    } else {
        $username = htmlspecialchars($_POST["username"]);
    }
    if (empty($_POST["email"])) {
        $emailError = "Email is Required";
        $isFormValid = false;
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }
    if (empty($_POST["password"])) {
        $passwordError = "Password is Required";
        $isFormValid = false;
    } elseif ($_POST["password"] != $_POST["confirm_password"]) {
        $passwordError = "Password does not match";
        $isFormValid = false;
    }else {
        $password = htmlspecialchars($_POST["password"]);
    }
    if (empty($_POST["confirm_password"])) {
        $confirm_passwordError = "Confirm Password is Required";
        $isFormValid = false;
    } else {
        $confirm_password = htmlspecialchars($_POST["confirm_password"]);
    }

    // Check response against database
    if ($isFormValid){
        $signedIn = SignUp($firstName, $lastName, $username, $email, $password);
        if (!$signedIn){
            $loginError = "That username may already be taken!";
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
            <h3 class="text-center">Register</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post">
                <div class="row">
                    <div class="mb-3 col">
                        <label for="firstnameInput">First Name</label>
                        <input type="text" class="form-control" id="firstnameInput" name="firstName"/>
                        <p class="text-danger"><?php echo $firstNameError?></p>
                    </div>
                    <div class="mb-3 col">
                        <label for="lastnameInput">Last Name</label>
                        <input type="text" class="form-control" id="lastnameInput" name="lastName"/>
                        <p class="text-danger"><?php echo $lastNameError?></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="usernameInput">Username</label>
                    <input type="text" class="form-control" id="usernameInput" name="username"/>
                    <p class="text-danger"><?php echo $usernameError?></p>
                </div>
                <div class="mb-3">
                    <label for="emailInput">Email</label>
                    <input type="text" class="form-control" id="emailInput" name="email"/>
                    <p class="text-danger"><?php echo $usernameError?></p>
                </div>
                <div class="mb-3">
                    <label for="passwordInput">Password</label>
                    <input type="password" class="form-control" id="passwordInput" name="password"/>
                    <p class="text-danger"><?php echo $usernameError?></p>
                </div>
                <div class="mb-3">
                    <label for="confirmPasswordInput">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPasswordInput" name="confirm_password"/>
                    <p class="text-danger"><?php echo $usernameError?></p>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary text-center">Submit</button>
                </div>
            </form>
            <hr/>
            <p class="text-center">Already have an account?</p>
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-secondary text-center" onclick="location.href='LogIn.php'" class="buttonSubmit">Log In</button>
            </div>

        </div>
    </div>

<?php require "components/pagebottom.php"?>