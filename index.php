<?php 
    require_once("inc/header.php");
    require_once("app/models/Topic.php");
    require_once("app/models/Discussion.php");

    $topics = new Topic();
    $topics = $topics->get_all();

    $discussions = new Discussion();
    $discussions = $discussions->get_all();
?>
    <!-- Search input for smaller devices -->
    <form class="w-11/12 mx-auto flex items-center gap-2 bg-gray rounded-full py-2 px-4 mt-3 visible md:invisible">
            <img src="public/assets/icons/magnifying_glass.svg" alt="Search"/>
            <input placeholder="Search Discussify" class="bg-gray block w-full outline-none"/>
    </form>


    
    <div class="max-container px-4">
        <div class="flex flex-col md:flex-row md:gap-5">
            <!-- Topics -->
            <div class="border-b border-gray md:border-r md:border-b-0 md:pr-6">
                <h2 class="uppercase text-sm opacity-80 mt-5 md:mt-0">Topics</h2>
                <div class="flex items-center gap-7 mt-3 md:flex-col md:gap-3 md:items-start">
                    <?php foreach($topics as $topic): ?>
                        <div class="flex items-center gap-2">
                            <?php echo $topic['icon'] ?>
                            <p><?php echo $topic['name'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Feed -->
            <div class="w-full">
                <div class="w-11/12 mx-auto">

                    <?php if($user->is_logged()): ?>
                        <form action="" method="">
                            <input placeholder="Start new Discussion..." class="bg-gray rounded-full outline-none py-2 px-4"/>
                            <button class="border-2 border-gray py-2 px-4 rounded-full ml-2 hover:bg-blue duration-100 ease-in">Discuss</button>
                        </form>
                    <?php endif; ?>

                    <?php foreach($discussions as $discussion): ?>
                        <!-- Discussion -->
                        <div class="border-b border-gray py-7">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="public/assets/profile-photos/<?php echo $discussion['profile_photo'] ?>"
                                         class="w-8"/>
                                    <p><?php echo $discussion['host_username'] ?></p>

                                    <div class="px-1 py-1 rounded-full bg-white"></div>

                                    <p><?php echo $discussion['created_at']?></p>

                                    <div class="px-1 py-1 rounded-full bg-white"></div>

                                    <p>Topic: <?php echo $discussion['topic_name'] ?></p>
                                </div>

                                <button class="bg-primary py-2 px-4 rounded-full text-w hover:bg-blue duration-100 ease-in">Join</button>
                            </div>
                            
                            <h3 class="text-2xl mt-6"><?php echo $discussion['subject']; ?></h3>

                            <p class="mt-3">LIKE 0 COMMENTS 10</p>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    


<?php 
    require_once("inc/footer.php");
?>
