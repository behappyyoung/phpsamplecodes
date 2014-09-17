

<?php
class Student{
    private $full_name = '';
    private $score = 0;
    private $grades = array();

    public function __construct($full_name, $score, $grades)
    {
        $this->full_name = $full_name;
        $this->grades = $grades;
        $this->score = $score;
    }

    public function show()
    {
        echo $this->full_name;
    }
}

class StudentWith{
    private $full_name = '';
    private $score = 0;
    private $grades = array();

    public function __construct($full_name, $score, $grades)
    {
        $this->full_name = $full_name;
        $this->grades = $grades;
        $this->score = $score;
    }

    public function show()
    {
        echo $this->full_name;
    }
    function __sleep()
    {
        echo 'Going to sleep...';
        return array('full_name',  'score');
    }
    function __wakeup()
    {
        echo 'Waking up...';
    }
}

$student = new Student('first last', 'a', array('a' => 90, 'b' => 100));
echo " <br /> Serialize  : <br />";
$s = serialize($student);
echo $s ."<br />\n";
echo " <br /> unSerialize : <br />";
$a = unserialize($s);

echo '<hr />';

$student = new StudentWith('first last', 'a', array('a' => 90, 'b' => 100));
echo " <br /> Serialize :  <br />";
$s = serialize($student);
echo $s ."<br />\n";
echo " <br /> unSerialize :  <br />";
$a = unserialize($s);


echo 'END<br /><br /><br /> ========== source file =================';
echo substr(highlight_file(__FILE__, true), 0 , strpos(highlight_file(__FILE__, true), 'END')-47);
echo '?><br /><br /><hr /><br />';
?>

