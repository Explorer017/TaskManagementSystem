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
            if(!$uid){
                //TODO: ADD HANDLING HERE
                echo 'error';
            }
            addUserToListAsCollaborator($list_id, $uid);
        }

    }

    ?>

<div class="container p-5">
    <h1>Modify <?php echo $list_name?></h1>

    <div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-editlistname-tab" data-bs-toggle="pill" data-bs-target="#pills-editlistname" type="button" role="tab" aria-controls="pills-addcollab" aria-selected="true">Edit list name</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link " id="pills-addcollab-tab" data-bs-toggle="pill" data-bs-target="#pills-addcollab" type="button" role="tab" aria-controls="pills-addcollab" aria-selected="true">Add Collaborator</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-removecollab-tab" data-bs-toggle="pill" data-bs-target="#pills-removecollab" type="button" role="tab" aria-controls="pills-removecollab" aria-selected="false">Remove Collaborator</button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-editlistname" role="tabpanel" aria-labelledby="pills-editlistname-tab" tabindex="0">
        </div>
        <div class="tab-pane fade show" id="pills-addcollab" role="tabpanel" aria-labelledby="pills-addcollab-tab" tabindex="0">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?listid=".$list_id;?>" method="post">
                <h3>Add Collaborator</h3>
                <label for="collabUsername">Collaborator Username</label>
                <input type="hidden" name="addOrRemove" value="add"/>
                <div class="row">
                    <div class="col">
                        <input type="text" id="collabUsername" class="form-control" name="collabUsername"/>
                    </div>
                    <div class="col">
                        <input type="submit" value="Add collaborator" class="btn btn-primary"/>
                    </div>
                </div>
                <div id="collabUsernameError" class="invalid-feedback"><?php echo $collabUsernameError?></div>
            </form>
        </div>

        <div class="tab-pane fade show" id="pills-removecollab" role="tabpanel" aria-labelledby="pills-removecollab-tab" tabindex="0">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?listid=".$list_id;?>" method="post">
                <h3>Remove Collaborator</h3>
                <label for="collabUsername">Collaborator Username</label>
                <input type="hidden" name="addOrRemove" value="add"/>
                <div class="row">
                    <div class="col">
                        <input type="text" id="collabUsername" class="form-control" name="collabUsername"/>
                    </div>
                    <div class="col">
                        <input type="submit" value="Remove collaborator" class="btn btn-danger"/>
                    </div>
                </div>
                <div id="collabUsernameError" class="invalid-feedback"><?php echo $collabUsernameError?></div>
            </form>
        </div>
    </div>
</div>