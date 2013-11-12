<!--Use this with sortAnswers.php-->

<!--style tag contents should be moved to the style folder 
at a later time after all experimenting is done directly with this file--> 
 <style>
    td.questionItem {
        margin-left: -1em;
        margin-top: 0.55em;
        width: 500px;
        height: 206px;
        color:white
    }
    label.answer
    {
        position:relative;
        color: blue;
    }
    .stars
    {
        color: yellow;
    }
    .rating
    {
        margin-left: 1em;
    }
    </style>
    <?php
    $responseObj = getUserInfo(true);

$answerInfo = getAnswerInfo($responseObj['id']);
     ?>
<div id="mainContent">
            <h1>MY ANSWERS</h1>
            <?php 
            var_dump(getAnswerInfo($_SESSION['user_id']));
            
            class Answer
            {
                public $id;
                public $cat;
                public $subcat;
                public $rating;
                public $desc;
                public $pay;
                public $datestr;
                public $answer;
                public $imgurl;
            }
            $answers = array();
            $qdata = getAnswerInfo($_SESSION['user_id']);
            /*start outer question method loop commenting here
            for ($x = 0, $k = 0; $x < 150; $x++){
                $qdata = getQuestionInfo($x);
                /*for ($y = 0, $numAns = count($qdata["answers"]); $y < $numAns; $y++){
                    $ansdata = $qdata["answers"][$y];
                    if ($ansdata['tutor_id'] === $_SESSION['user_id'])
                    {
                        end loop commenting here */
                for ($y = 0, $numAns = count($qdata["answers"]); $y < $numAns; $y++){
                        $ansdata = $qdata["answers"][$y];
                        $answers[$k] = new Answer();
                        $answers[$k]->id = $qdata['questions'][$y]['id'];
                        $answers[$k]->pay = rand(1,10) * 10;
                        $answers[$k]->datestr = $qdata["date_created"];
                        $answers[$k]->cat = $qdata['questions'][$y]["category"];
                        $answers[$k]->subcat = $qdata['questions'][$y]["subcategory"];
                        $answers[$k]->rating = rand(1,5);
                        $answers[$k]->desc = $qdata['questions'][$y]['description'];
                        $answers[$k]->answer = $ansdata['text'];
                        $answers[$k]->imgurl = $qdata["image_url"];
                        $k++;
                        /*$answers[$k] = new Answer();
                        $answers[$k]->id = $qdata['id'];
                        $answers[$k]->pay = rand(1,10) * 10;
                        $answers[$k]->datestr = $qdata["date_created"];
                        $answers[$k]->cat = $qdata["category"];
                        $answers[$k]->cat = $qdata["subcategory"];
                        $answers[$k]->rating = rand(1,5);
                        $answers[$k]->desc = $qdata['description'];
                        $answers[$k]->answer = $ansdata['text'];
                        $answers[$k]->imgurl = $qdata["image_url"];
                        $k++;*/
                    }
                    //}
               // }
           // }    
            function recent($a, $b)
            {
                return strcmp($b->datestr, $a->datestr);
            }
            function ratinghigh($a, $b)
            {
                return $b->rating - $a->rating;
            }
            function ratinglow($a, $b)
            {
                return -1 * ($b->rating - $a->rating);
            }
            function payhigh($a, $b)
            {
                return $b->pay - $a->pay;
            }
            function paylow($b, $a)
            {
                return $b->pay - $a->pay;
            }
            function select($lab)
            {
                if ($_POST['order'] === $lab)
                        echo 'selected'; 
            }
            ?>
           <form id='sort' action="<?=$PHP_SELF?>" method='post'>
            <label for="order">Sort By:</label>
            <select name="order">
            <option <?php select('Most Recent First');?>>Most Recent First</option>
            <option <?php select('Highest Pay First');?>>Highest Pay First</option>
            <option <?php select('Lowest Pay First');?>>Lowest Pay First</option>
            <option <?php select('Highest Rating First');?>>Highest Rating First</option>
            <option <?php select('Lowest Rating First');?>>Lowest Rating First</option>
            </select>
            <input type='submit' value='submit'/>
            </form>
            <table>
            <?php
            function stars($numStars)
            {
               echo '<span class="rating">Rating: </span>
               <span class="stars">';
               if (!$numStars){
                echo '&#9733';
                return;
               }
               for ($count = 0; $count < $numStars; $count++)
                    echo '&#9733';
                echo '</span>';
            }
            
            if (!$_POST['order'] || $_POST['order'] === 'Most Recent First')
            {
                usort($answers, "recent");
            }
            else if ($_POST['order'] === 'Highest Rating First')
            {
                usort($answers, "ratinghigh");
            }
            else if ($_POST['order'] === 'Lowest Rating First')
            {
                usort($answers, "ratinglow");
            }
            else if ($_POST['order'] === 'Highest Pay First')
            {
                usort($answers, "payhigh");
            }
            else if ($_POST['order'] === 'Lowest Pay First')
            {
                usort($answers, "paylow");
            }
                for ($y = 0, $max = count($answers); $y < $max; $y++){
            ?>
                <tr>
                <td>
                <div class="questionItem" style="display: inline-block; opacity: 1;">
                <img class="questionImage" src=<?php echo $answers[$y]->imgurl ?> >
                <label><?php echo $answers[$y]->cat ?></label><label><?php echo $answers[$y]->subcat ?></label>
                <label><?php echo $answers[$y]->datestr ?></label>
                </div>
                </td>
                <td class="questionItem">
                <div id="view-question-right">
        			
					<label>Your Pay: 
					<?php echo $answers[$y]->pay; stars($answers[$y]->rating); ?>
                    </label>
					<label class="answer">Question: </label>
					<label class="text"><?php echo $answers[$y]->desc ?></label>
					
					<label class="answer">Answer:</label>
					<label class="text"><?php echo $answers[$y]->answer ?></label>
				</div>
                </td>
                </tr>
                
            <?php
            }
            ?>
                
                </table>
			
		</div>