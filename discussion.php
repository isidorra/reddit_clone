<?php
    //ob_start();
    require_once("inc/header.php");
    require_once("app/models/Discussion.php");
    require_once("app/models/Comment.php");
    require_once("app/models/Reply.php");
    require_once("inc/converts/datetime.php");

    $discussion = new Discussion();
    $discussion = $discussion->get_by_id($_GET["discussion_id"]);

   

    $comments = new Comment();
    $comments = $comments->get_all_by_discussion_id($_GET["discussion_id"]);

    

    
?>

<div class="max-container p-5 md:p-10">
    <!-- Discussion Title -->
    <div class="border-b border-gray py-2 md:py-7">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="profile.php?user_id=<?php echo $discussion['host_id'] ?>" class="flex items-center gap-2">
                    <img src="public/assets/profile-photos/<?php echo $discussion['profile_photo'] ?>"
                        class="w-5 md:w-8"/>
                        <p class="text-base md:text-lg"><?php echo $discussion['host_username'] ?></p>
                </a>

                <div class="hidden md:block md:px-1 md:py-1 opacity-60">|</div>

                <p class="hidden md:block text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($discussion['created_at'], $full = false)?></p>

                <div class="hidden md:block md:px-1 md:py-1 opacity-60">|</div>

                <p class="hidden md:block">
                    <span class="text-sm md:text-base opacity-60 font-thin">Topic: </span>
                        <span class="bg-gray text-sm md:text-base text-primary px-2 py-1 md:px-4 rounded-full md:py-2 ml-2">
                        <?php echo $discussion['topic_name'] ?>
                    </span>
                </p>
            </div>

            <div>
                <?php if($user->is_logged() && ($discussion["host_id"] == $_SESSION["user_id"])): ?>
                    <form action="delete_discussion.php" method="POST">
                            <input type="hidden" name="discussion_id" value="<?php echo $discussion["discussion_id"] ?>"/>
                            <button class="border border-red text-red rounded-lg py-1 px-2">
                                    Delete
                            </button>
                    </form>

                <?php endif; ?>
            </div>
        </div>

        <div class="flex items-center gap-2 mt-4">
                                <p class="visible md:hidden text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($discussion['created_at'], $full = false)?></p>

                                <div class="visible md:hidden md:px-1 md:py-1 opacity-60">|</div>

                                <p class="visible md:hidden">
                                    <span class="text-sm md:text-base opacity-60 font-thin">Topic: </span>
                                    <span class="bg-gray text-sm md:text-base text-primary px-2 py-1 md:px-4 rounded-full md:py-2 ml-2">
                                        <?php echo $discussion['topic_name'] ?>
                                    </span>
                                </p>
        </div>
                                
        <h3 class="text-xl md:text-2xl mt-6"><?php echo $discussion['subject']; ?></h3>

            <div class="flex items-center gap-4 mt-7">
                <?php if($user->is_logged()): ?>
                    <form method="POST" action="like_discussion.php">
                        <input type="hidden" name="discussion_id" value="<?php echo $discussion["discussion_id"]; ?>">
                        <button class="flex items-center gap-2 opacity-50">
                            <?php 
                                $disc = new Discussion();
                                if($user->is_logged() && $disc->is_liked($discussion["discussion_id"], $_SESSION["user_id"])): ?>
                                    <img src="public/assets/icons/filled-like.svg"/>
                                <?php else: ?>
                                    <img src="public/assets/icons/empty-like.svg"/>
                                <?php endif; ?>
                                    <p>
                                        <?php 
                                            $disc_likes = new Discussion; 
                                            $disc_likes = $disc_likes->get_likes_number($discussion["discussion_id"]);
                                            echo $disc_likes;
                                        ?>
                                    </p>
                        </button>
                    </form>
                <?php else: ?>
                    <form>
                        <button class="flex items-center gap-2 opacity-50" disabled>
                            <?php 
                                $disc = new Discussion();
                                if($user->is_logged() && $disc->is_liked($discussion["discussion_id"], $_SESSION["user_id"])): ?>
                                    <img src="public/assets/icons/filled-like.svg"/>
                                <?php else: ?>
                                    <img src="public/assets/icons/empty-like.svg"/>
                                <?php endif; ?>
                                    <p>
                                        <?php 
                                            $disc_likes = new Discussion; 
                                            $disc_likes = $disc_likes->get_likes_number($discussion["discussion_id"]);
                                            echo $disc_likes;
                                        ?>
                                    </p>
                        </button>
                    </form>

                <?php endif; ?>
                <div class="flex items-center gap-2 opacity-50">
                    <img src="public/assets/icons/user.svg"/>
                    <p></p>
                        <?php 
                            $participants_number = new Discussion();
                            $disc = new Discussion();
                            $participants_number = $disc->get_participants_number($discussion["discussion_id"]);
                            echo isset($participants_number) ? $participants_number : 0;
                                                    
                        ?>
                    </p>
                </div>
                    <div class="flex items-center gap-2 opacity-50">
                    <img src="public/assets/icons/comment.svg"/>
                    <p>
                        <?php 
                            $comments_number = new Discussion();
                            $disc = new Discussion();
                            $comments_number = $disc->get_comments_number($discussion["discussion_id"]); 
                            echo isset($comments_number["total_count"]) ? $comments_number["total_count"] : 0;
                        ?>
                    </p>
                </div>
            </div>
                                
    </div>

    <div class="comments-container">
        <form action="create_comment.php?discussion_id=<?php echo $discussion['discussion_id']; ?>" method="POST" class="flex items-end gap-2 mt-5">

            <textarea placeholder="Your Opinion" 
                class="resize-none bg-gray outline-none w-full rounded-lg py-3 px-4" required name="content"></textarea>
            <button type="submit" class="bg-primary rounded-lg py-2 px-3 text-sm md:text-base md:py-2 md:px-4 hover:bg-blue duration-100 ease-in">
                Post
            </button>
        </form>

        <div class="mt-10">
            <?php foreach($comments as $comment): ?>
                <!-- Comment -->
                <div class="border border-gray p-5 rounded-lg mb-5">
                
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <a href="profile.php?user_id=<?php echo $comment['user_id'] ?>" class="flex items-center gap-2">
                                <img src="public/assets/profile-photos/<?php echo $comment['profile_photo'] ?>"
                                    class="w-5 md:w-8"/>
                                <p class="text-base md:text-lg"><?php echo $comment['username'] ?></p>
                            </a>
                            <div class="hidden md:block md:px-1 md:py-1 opacity-60">|</div>
                            <p class="hidden md:block text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($comment['created_at'], $full = false)?></p>
                        </div>
                        <?php if($user->is_logged() && $comment['user_id'] == $_SESSION["user_id"]): ?>
                            <form method="POST" action="delete_comment.php?discussion_id=<?php echo $_GET["discussion_id"] ?>">
                                <input type="hidden" name="comment_id" value="<?php echo $comment["comment_id"] ?>"/>
                                <button class="border border-red rounded-lg py-1 px-2 text-red">Remove</button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <p class="visible md:hidden mt-2 text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($comment['created_at'], $full = false)?></p>
                    <p class="mt-5"><?php echo $comment["content"] ?></p>

                    <div class="flex items-center gap-5 mt-5">

                    
                        <form method="POST" action="like_comment.php?discussion_id=<?php echo $_GET["discussion_id"] ?>">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment["comment_id"]; ?>">
                                    <button class="flex items-center gap-2 opacity-50">
                                        <?php 
                                        $comm = new Comment();
                                        if($user->is_logged() && $comm->is_liked($comment["comment_id"], $_SESSION["user_id"])):?>
                                            <img src="public/assets/icons/filled-like.svg"/>
                                        <?php else: ?>
                                            <img src="public/assets/icons/empty-like.svg"/>
                                        <?php endif; ?>
                                        <p>
                                            <?php 
                                                $comm_likes = new Comment(); 
                                                $comm_likes = $comm_likes->get_likes_number($comment["comment_id"]);
                                                echo $comm_likes;
                                            ?>
                                        </p>
                                    </button>
                        </form>
                        <?php $com = new Comment(); ?>
                        <?php if($com->has_replies($comment["comment_id"]) > 0): ?>
                        
                            <button class="opacity-80 border border-gray px-2 py-1 show-replies-btn">Show all replies</button>
                        <?php endif; ?>
                    </div>

                </div>
                <!-- Replies -->
                <div class="flex flex-col items-end hidden replies">
                    <?php 
                        $replies = new Reply();
                        $replies = $replies->get_all_by_comment_id($comment["comment_id"]);
                    ?>
                    <?php foreach($replies as $reply): ?>
                        <div class="border border-gray p-3 rounded-lg mb-2 md:mb-5 w-11/12">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <a href="profile.php?user_id=<?php echo $reply['user_id'] ?>" class="flex items-center gap-2">
                                        <img src="public/assets/profile-photos/<?php echo $reply['profile_photo'] ?>"
                                            class="w-5 md:w-8"/>
                                        <p class="text-base md:text-lg"><?php echo $reply['username'] ?></p>
                                    </a>
                                    <div class="md:px-1 md:py-1 opacity-60">|</div>
                                    <p class="text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($reply['created_at'], $full = false)?></p>
                                </div>
                                <?php if($user->is_logged() && $reply['user_id'] == $_SESSION["user_id"]): ?>
                                    <form method="POST" action="delete_reply.php?discussion_id=<?php echo $_GET["discussion_id"] ?>">
                                        <input type="hidden" name="reply_id" value="<?php echo $reply["reply_id"] ?>"/>
                                        <button class="border border-red rounded-lg py-1 px-2 text-red">Remove</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p class="mt-3"><?php echo $reply["content"] ?></p>

                            <form method="POST" action="like_reply.php?discussion_id=<?php echo $_GET["discussion_id"] ?>" class="mt-5">
                                    <input type="hidden" name="reply_id" value="<?php echo $reply["reply_id"] ?>">
                                    <button class="flex items-center gap-2 opacity-50">
                                        <?php 
                                        $rep = new Reply();
                                        if($user->is_logged() && $rep->is_liked($reply["reply_id"], $_SESSION["user_id"])):?>
                                            <img src="public/assets/icons/filled-like.svg"/>
                                        <?php else: ?>
                                            <img src="public/assets/icons/empty-like.svg"/>
                                        <?php endif; ?>
                                        <p>
                                            <?php 
                                                $rep_likes = new Reply(); 
                                                $rep_likes = $rep_likes->get_likes_number($reply["reply_id"]);
                                                echo $rep_likes;
                                            ?>
                                        </p>
                                    </button>
                            </form>


                        </div>
                        
                    <?php endforeach; ?>

                    <button class="mt-1 mb-10 opacity-80 border border-gray px-2 py-1 hide-replies-btn">Hide replies</button>

                </div>

                <!-- Post Reply -->
                <div class="flex flex-col items-end">
                    <form action="create_reply.php?comment_id=<?php echo $comment['comment_id']; ?>&discussion_id=<?php echo $_GET["discussion_id"] ?>" method="POST" 
                            class="flex items-center gap-2 mb-10 w-11/12">

                        <textarea placeholder="Reply" 
                            class="resize-none bg-gray outline-none w-full rounded-lg py-3 px-4" required name="content"></textarea>
                        <button type="submit" class="bg-primary rounded-lg py-2 px-3 text-sm md:text-base md:py-2 md:px-4 hover:bg-blue duration-100 ease-in">
                            Post
                        </button>
                    </form>
                </div>

            <?php endforeach; ?>
        </div>


    </div>
</div>

<script src="public/js/show_replies.js"></script>