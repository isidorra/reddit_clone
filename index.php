<?php 
    ob_start();
    require_once("inc/header.php");
    require_once("app/models/Topic.php");
    require_once("app/models/Discussion.php");
    require_once("inc/converts/datetime.php");


    $topics = new Topic();
    $topics = $topics->get_all();

    $topic_id_filter = isset($_GET['topic_id']) ? $_GET['topic_id'] : null;

    $discussions = new Discussion();
    $discussions = $topic_id_filter ? $discussions->get_all_by_topic($topic_id_filter) : $discussions->get_all();


?>
    <!-- Search input for smaller devices -->
    <form class="w-11/12 mx-auto flex items-center gap-2 bg-gray rounded-full py-2 px-4 mt-3 visible md:invisible">
            <img src="public/assets/icons/magnifying_glass.svg" alt="Search"/>
            <input placeholder="Search Discussify" class="bg-gray block w-full outline-none"/>
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
                              class="mt-5 w-full mx-auto md:w-96 md:mx-0 flex items-start gap-1" id="create_discussion_form">

                            <div>
                                <input name="subject" id="subject" placeholder="Your thoughts..." 
                                    class="w-full bg-gray rounded-full outline-none py-2 px-4" required/>
                                <p class="text-red" id="subject_error"></p>
                            </div>

                            <select name="topic_id" class="w-auto bg-gray rounded-full outline-none py-2 px-1" required>
                                <option selected disabled value="">Topic</option>
                                <?php foreach($topics as $topic): ?>
                                    <option value="<?php echo $topic['topic_id'] ?>"><?php echo $topic['name'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" class="border-2 border-gray py-2 px-4 rounded-full ml-2 hover:bg-blue duration-100 ease-in">
                                Discuss
                            </button>
                        </form>
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
                                    <a href="discussion.php?discussion_id=<?php echo $discussion["discussion_id"] ?>" 
                                        class="bg-primary px-2 py-1 md:py-2 md:px-4 text-base md:text-lg rounded-full hover:bg-blue duration-100 ease-in">
                                        Enter
                                    </a>
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="public/js/create-discussion-validation.js"></script>

<?php 
    require_once("inc/footer.php");
?>
