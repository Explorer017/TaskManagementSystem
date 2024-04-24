<?php
    $listid = "";
    if (array_key_exists('listid', $_GET)){
        $listid = $_GET['listid'];
    }
    require 'functions/ListFunctions.php';
    $isFormValid = true;
    $listName = $listNameError = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST" and $_GET['type'] == 'newList') {
        if (empty($_POST["ListName"]) or $_POST["ListName"] == "none") {
            $listNameError = "Task Name is required";
            $isFormValid = false;
        } else {
            $listName = htmlspecialchars($_POST["ListName"]);
        }

        if ($isFormValid) {
            $list = newList($listName);
            if ($list != false){
                header("Location: index.php?listid=".$list);
            }
        }
    }
?>

<div class="modal fade" id="NewListModal" tabindex="-1" aria-labelledby="NewListTitle" aria-hidden="true" style="color:black">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="NewListTitle">New List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?listid=".$listid."&type=newList";?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ListName" class="form-label">List Name</label>
                        <input type="text" class="form-control" id="ListName" name="ListName"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>