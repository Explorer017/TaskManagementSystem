<ul class="nav nav-pills flex-column mb-auto overflow-y-auto">
        <li class="hstack gap-3 pb-3">
            <?php require 'components/NewListPopup.php'?>
            <button class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#NewListModal">New List</button>
        </li>
        <?php
            foreach (getListsForUser() as $listID):
                if($listID[0] == $_GET['listid']):?>
                    <li class="hstack gap-3 nav-link active text-white">
                        <div class="">
                            <?php echo getListNameFromID($listID[0])[0][0]?>
                        </div>
                        <button class="btn btn-danger ms-auto" onclick="location.href='listAction.php?action=delete&listid=<?php echo $_GET["listid"]?>'"><i class="bi bi-trash"></i></button>
                        <button class="btn btn-secondary"><i class="bi bi-three-dots"></i></button>
                    </li>
                <?php else:?>
                    <li class="hstack gap-3">
                        <div class="nav-link text-white">
                            <a class="navbarList" href="<?php echo "index.php?listid=$listID[0]"?>">
                                <?php echo getListNameFromID($listID[0])[0][0]?>
                            </a>
                        </div>
                        <button class="btn btn-outline-danger ms-auto" onclick="location.href='listAction.php?action=delete&listid=<?php echo $_GET["listid"]?>'"><i class="bi bi-trash"></i></button>
                        <button class="btn btn-outline-secondary"><i class="bi bi-three-dots"></i></button>
                    </li>
                <?php endif;?>
            <?php endforeach;?>
        </ul>