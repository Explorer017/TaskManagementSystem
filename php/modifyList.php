<?php
    require 'components/pagetop.php';
    require 'functions/LoginFunctions.php';
    require 'functions/TaskFunctions.php';
    require 'functions/DatabaseFunctions.php';
    require 'functions/ListFunctions.php';

    if(!isset($_GET['listid'])){
        echo '<h1>Something has gone wrong!</h1>';
    }
    $list_id = $_GET['listid'];
    $list_name = getListName($list_id);

    $collabUsernameError="";
    $is_form_valid = true;
    $collabUsername = $addOrRemove = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($_POST["collabUsername"])){
            $collabUsernameError = "Username of collaborator is required";
            $is_form_valid = false;
        } else {
            $collabUsername = htmlspecialchars($_POST["collabUsername"]);
        }
        if (empty($_POST["addOrRemove"]) || ($_POST["addOrRemove"] != "add" && $_POST["addOrRemove"] != "remove")){
            // this would only happen if the user edited the hidden form element in inspect
            $collabUsernameError = "An Error Occurred";
            $is_form_valid = false;
        } else {
            $addOrRemove = htmlspecialchars($_POST["addOrRemove"]);
        }

        if($is_form_valid){
            $uid = LookupUIDFromName($collabUsername);
            echo $collabUsername;
            echo ' ';
            echo $uid;
            echo ' ';
            echo $list_id;
            if(!$uid){
                //TODO: ADD HANDLING HERE
                echo 'error';
            }
            //TODO: FIX?
            addUserToListAsCollaborator($list_id, $uid);
        }

    }

    ?>

<h1>Modify <?php echo $list_name?></h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?listid=".$list_id;?>" method="post">
    <h3>Add Collaborator</h3>
    <label for="collabUsername">Collaborator Username</label>
    <input type="text" id="collabUsername" class="form-control" name="collabUsername"/>
    <input type="hidden" name="addOrRemove" value="add"/>
    <div id="collabUsernameError" class="invalid-feedback"><?php echo $collabUsernameError?></div>
    <input type="submit" value="Add collaborator"/>
</form>


<button>remove collaborator</button>

