<?php
    ob_start();
    require_once("inc/header.php");
    require_once("app/models/Discussion.php");
    require_once("app/models/Comment.php");
    require_once("inc/converts/datetime.php");
    require_once("inc/converts/datetime.php");

    $discussion = new Discussion();
    $discussion = $discussion->get_by_id($_GET["discussion_id"]);

    $comments = new Comment();
    $comments = $comments->get_all_by_discussion_id($_GET["discussion_id"]);
?>

<div class="max-container p-10">
    <!-- Discussion Title -->
    <div class="border-b border-gray py-7">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <a href="profile.php?user_id=<?php echo $discussion['host_id'] ?>" class="flex items-center gap-2">
                    <img src="public/assets/profile-photos/<?php echo $discussion['profile_photo'] ?>"
                        class="w-5 md:w-8"/>
                        <p class="text-base md:text-lg"><?php echo $discussion['host_username'] ?></p>
                </a>

                <div class="md:px-1 md:py-1 opacity-60">|</div>

                <p class="text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($discussion['created_at'], $full = false)?></p>

                <div class="md:px-1 md:py-1 opacity-60">|</div>

                <p>
                    <span class="text-sm md:text-base opacity-60 font-thin">Topic: </span>
                        <span class="bg-gray text-sm md:text-base text-primary px-2 py-1 md:px-4 rounded-full md:py-2 ml-2">
                        <?php echo $discussion['topic_name'] ?>
                    </span>
                </p>
            </div>

            <div>
                <?php if($user->is_logged() && ($discussion["host_id"] == $_SESSION["user_id"])): ?>
                    <button class="bg-bgColor text-red border border-red hover:border-primary hover:text-primary px-2 py-1 md:py-2 md:px-4 text-base md:text-lg rounded-full">
                        Delete
                    </button>

                <?php endif; ?>
            </div>
        </div>
                                
        <h3 class="text-xl md:text-2xl mt-6"><?php echo $discussion['subject']; ?></h3>

            <div class="flex items-center gap-4 mt-7">
                <div class="flex items-center gap-2 opacity-50">
                    <img src="public/assets/icons/thumbs-up.svg"/>
                    <p>0</p>
                </div>
                <div class="flex items-center gap-2 opacity-50">
                    <img src="public/assets/icons/thumbs-down.svg"/>
                    <p>0</p>
                </div>
                    <div class="flex items-center gap-2 opacity-50">
                    <img src="public/assets/icons/comment.svg"/>
                    <p>0</p>
                </div>
            </div>
                                
    </div>

    <div class="comments-container">
    <form action="create_comment.php?discussion_id=<?php echo $discussion['discussion_id']; ?>" method="POST" class="flex items-center gap-2 mt-5">

            <textarea placeholder="Your Opinion" 
                class="resize-none bg-gray outline-none w-full rounded-lg py-3 px-4" required name="content"></textarea>
            <button type="submit" class="bg-primary rounded-lg py-3 px-5">Post</button>
        </form>

        <div class="mt-10">
            <?php foreach($comments as $comment): ?>
                <div class="border border-gray p-5 rounded-lg mb-5">
                
                    <div class="flex items-center gap-4">
                        <a href="profile.php?user_id=<?php echo $comment['user_id'] ?>" class="flex items-center gap-2">
                            <img src="public/assets/profile-photos/<?php echo $comment['profile_photo'] ?>"
                                class="w-5 md:w-8"/>
                            <p class="text-base md:text-lg"><?php echo $comment['username'] ?></p>
                        </a>
                        <div class="md:px-1 md:py-1 opacity-60">|</div>
                        <p class="text-sm md:text-base opacity-60 font-thin"><?php echo time_elapsed_since_now($comment['created_at'], $full = false)?></p>
                    </div>
                    <p class="mt-5"><?php echo $comment["content"] ?></p>
                </div>
            <?php endforeach; ?>
        </div>


    </div>
</div>