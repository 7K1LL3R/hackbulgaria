<?php

$words = array('javascript', 'python');
$text = 'Programming in PHP, JavaScript and Python is fun!';

$words2 = array('yesterday', 'Dog', 'food', 'walk');
$text2 = 'Yesterday, I took my dog for a walk.\n It was crazy! My dog wanted only food.';

maskOutWords($words, $text);
maskOutWords($words2, $text2);

function maskOutWords($words, $text)
{
    if(count($words) > 0)
    {
        foreach($words as $word)
        {
            if(stripos($text, $word) !== false)
            {
                $text = str_ireplace($word, str_repeat('*', strlen($word)), $text);
            }
        }
    }
    
    echo $text . '<br>';
}