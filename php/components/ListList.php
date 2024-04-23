<?php $ListIDInURL = isset($_GET['listid']) ? $_GET['listid'] : false;?>

<ul class="nav nav-pills flex-column mb-auto overflow-y-auto">
    <li class="hstack gap-3 pb-3">
        <?php require 'components/NewListPopup.php'?>
        <button class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#NewListModal">New List</button>
    </li>
    <hr/>
    <h5>My Lists</h5>
    <?php
    if (getOwnedListsForCurrentUser() != null):
        foreach (getOwnedListsForCurrentUser() as $listID):
            if($listID[0] == $ListIDInURL):?>
                <li class="hstack gap-3 nav-link active text-white">
                    <div class="">
                        <?php echo getListNameFromID($listID[0])[0][0]?>
                    </div>
                    <button class="btn btn-danger ms-auto" onclick="location.href='listAction.php?action=delete&listid=<?php echo $ListIDInURL?>'"><i class="bi bi-trash"></i></button>
                    <button class="btn btn-secondary"  onclick="location.href='modifyList.php?listid=<?php echo $ListIDInURL?>'"><i class="bi bi-three-dots"></i></button>
                </li>
            <?php else:?>
                <li class="hstack gap-3">
                    <div class="nav-link text-white">
                        <a class="navbarList" href="<?php echo "index.php?listid=$listID[0]"?>">
                            <?php echo getListNameFromID($listID[0])[0][0]?>
                        </a>
                    </div>
                    <button class="btn btn-outline-danger ms-auto" onclick="location.href='listAction.php?action=delete&listid=<?php echo $ListIDInURL?>'"><i class="bi bi-trash"></i></button>
                    <button class="btn btn-outline-secondary" onclick="location.href='modifyList.php?listid=<?php echo $ListIDInURL?>'"><i class="bi bi-three-dots"></i></button>
                </li>
            <?php endif;?>
    <?php endforeach;
    else:?>
        You have not created a list! Click the <b>"New List"</b> button to get started!
    <?php endif;?>
    <hr/>
    <h5>Lists I collaborate on</h5>
    <?php
    if (getCollabListsForCurrentUser() != null):
        foreach (getCollabListsForCurrentUser() as $listID):
            if($listID[0] == $ListIDInURL):?>
                <li class="hstack gap-3 nav-link active text-white">
                    <div class="">
                        <?php echo getListNameFromID($listID[0])[0][0]?>
                    </div>
                    <button class="btn btn-danger ms-auto" onclick="location.href='listAction.php?action=leave&listid=<?php echo $ListIDInURL?>'"><i class="bi bi-box-arrow-right"></i></button>
                </li>
            <?php else:?>
                <li class="hstack gap-3">
                    <div class="nav-link text-white">
                        <a class="navbarList" href="<?php echo "index.php?listid=$listID[0]"?>">
                            <?php echo getListNameFromID($listID[0])[0][0]?>
                        </a>
                    </div>
                    <button class="btn btn-outline-danger ms-auto" onclick="location.href='listAction.php?action=leave&listid=<?php echo $ListIDInURL?>'"><i class="bi bi-box-arrow-right"></i></button>
                </li>
            <?php endif;?>
        <?php endforeach;
     else:?>
     No one has shared a list with you!
    <?php endif;?>
    <hr/>
    <h5>Lists I observe</h5>
    <?php if (getObserverListsForCurrentUser() != null):
        foreach (getObserverListsForCurrentUser() as $listID):
        if($listID[0] == $ListIDInURL):?>
            <li class="hstack gap-3 nav-link active text-white">
                <div class="">
                    <?php echo getListNameFromID($listID[0])[0][0]?>
                </div>
                <button class="btn btn-danger ms-auto" onclick="location.href='listAction.php?action=leave&listid=<?php echo $ListIDInURL?>'"><i class="bi bi-box-arrow-right"></i></button>
            </li>
        <?php else:?>
            <li class="hstack gap-3">
                <div class="nav-link text-white">
                    <a class="navbarList" href="<?php echo "index.php?listid=$listID[0]"?>">
                        <?php echo getListNameFromID($listID[0])[0][0]?>
                    </a>
                </div>
                <button class="btn btn-outline-danger ms-auto" onclick="location.href='listAction.php?action=leave&listid=<?php echo $ListIDInURL?>'"><i class="bi bi-box-arrow-right"></i></button>
            </li>
        <?php endif;?>
    <?php endforeach; else:?>
    No one as added you as a list observer!
    <?php endif;?>
</ul>