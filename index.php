<?php 
    ob_start();
    require_once("inc/header.php");
    require_once("app/models/Topic.php");
    require_once("app/models/Discussion.php");
    require_once("inc/converts/datetime.php");


    $topics = new Topic();
    $topics = $topics->get_all();
    
    
    $search_query = isset($_GET['query']) ? $_GET['query'] : null;

    $discussions = new Discussion();
    if ($search_query) {
        $discussions = $discussions->search($search_query);
    } else {
        $topic_id_filter = isset($_GET['topic_id']) ? $_GET['topic_id'] : null;
        $discussions = $topic_id_filter ? $discussions->get_all_by_topic($topic_id_filter) : $discussions->get_all();
    }


?>
    <!-- Search input for smaller devices -->
    <form method="GET" action="index.php" class="w-11/12 mx-auto flex items-center gap-2 bg-gray rounded-full py-2 px-4 mt-3 visible md:invisible">
            <img src="public/assets/icons/magnifying_glass.svg" alt="Search"/>
            <input placeholder="Search Discussify" name="query" class="bg-gray block w-full outline-none"/>

            <button type="submit" class="hidden">Search</button>
    </form>


    
    <div class="max-container px-4">
        <div class="flex flex-col w-11/12 mx-auto md:w-auto md:flex-row md:gap-5">
            <!-- Topics -->
            <div class="border-b border-gray pb-5 md:border-r md:border-b-0 md:pr-16">
                <h2 class="uppercase text-xs md:text-sm opacity-80 mt-5 md:mt-0">Topics</h2>
                <div class="flex items-center gap-5 mt-3 md:flex-col md:gap-6 md:items-start snap-x overflow-x-auto">
                    <a href="index.php" class="flex items-center gap-2 opacity-80 mt-2 md:mt-0 mb-5 md:mb-0">
                            <img src="public/assets/icons/all-topics.svg" alt="All Topics"/>
                            <p class="uppercase text-sm md:text-base">All</p>
                            <div class="md:px-1 md:py-1 opacity-60 md:hidden">|</div>
                    </a>
                    <?php foreach($topics as $topic): ?>
                        <a href="index.php?topic_id=<?php echo $topic["topic_id"] ?>"
                            class="flex items-center gap-2 opacity-80 mt-2 md:mt-0 mb-5 md:mb-0">
                            <?php echo $topic['icon'] ?>
                            <p class="uppercase text-sm md:text-base"><?php echo $topic['name'] ?></p>
                            <div class="md:px-1 md:py-1 opacity-60 md:hidden">|</div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Feed -->
            <div class="w-full">
                <div class="md:w-11/12 mx-auto">
                
                    <?php if($user->is_logged()): ?>
                        <form action="create_discussion.php" method="POST" 
                              class="mt-5 w-full mx-auto md:w-96 md:mx-0 md:flex md:items-start md:gap-1" id="create_discussion_form">

                            <div>
                                <input name="subject" id="subject" placeholder="Your thoughts..." 
                                    class="w-full bg-gray rounded-full outline-none py-3 px-4" required/>
                                <p class="text-red" id="subject_error"></p>
                            </div>
                            <div class="mt-2 md:mt-0 flex items-center justify-between">
                                <select name="topic_id" class="w-full md:w-auto bg-gray rounded-full outline-none py-3 px-1" required>
                                    <option selected disabled value="">Topic</option>
                                    <?php foreach($topics as $topic): ?>
                                        <option value="<?php echo $topic['topic_id'] ?>"><?php echo $topic['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <button type="submit" class="border-2 border-gray py-1 px-2 md:py-2 md:px-4 rounded-full ml-2 hover:bg-blue duration-100 ease-in">
                                    Discuss
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                    <?php if($search_query): ?>
                        <h2 class="text-2xl mt-7">Search Results for <span class="text-blue"><?php echo $search_query ?></span></h2>
                    <?php endif; ?>
                    <?php foreach($discussions as $discussion): ?>
                        <!-- Discussion -->
                        <div class="border-b border-gray py-7">
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

                                <div class="flex items-center gap-2">
                                    <a href="discussion.php?discussion_id=<?php echo $discussion["discussion_id"] ?>" 
                                        class="bg-primary py-2 px-4 text-sm md:text-base rounded-lg hover:bg-blue duration-100 ease-in">
                                        Enter
                                    </a>
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
                                <form method="POST" action="like_discussion.php">
                                    <input type="hidden" name="discussion_id" value="<?php echo $discussion["discussion_id"]; ?>">
                                    <button class="flex items-center gap-2 opacity-50">
                                        <?php 
                                        $disc = new Discussion();
                                        if($disc->is_liked($discussion["discussion_id"], $discussion["host_id"])):?>
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
                                <div class="flex items-center gap-2 opacity-50">
                                    <img src="public/assets/icons/user.svg"/>
                                    <p>
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="public/js/create-discussion-validation.js"></script>
    <script src="public/js/like-dislike.js"></script>

<?php 
    require_once("inc/footer.php");
?>
