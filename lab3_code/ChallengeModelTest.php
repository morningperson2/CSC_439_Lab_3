<?php
require_once 'Main.php';

use PHPUnit\Framework\TestCase;

class ChallengeModelTest extends TestCase
{
    public function test_foo(){
        $this->assertSame(true,true);
    }

    public function test_get_succeed_id(){
        $id = "one";
        $i_t = "Hello there";
        $t_a = "Speed";
        $t = "Bear";
        $s_n = "two";
        $f_n = "You lose";
        $e_o = array();
        $char_a_o = array();
        $chal_a_o = array();
        $sut = new ChallengeModel($id, $i_t, $t_a, $t, $s_n, $f_n, $e_o, $char_a_o, $chal_a_o);

        $result = $sut->get_succeed_id();

        $this->assertSame("two", $result);


    }

    public function test_get_fail_id(){
        $id = "one";
        $i_t = "Hello there";
        $t_a = "Speed";
        $t = "Bear";
        $s_n = "two";
        $f_n = "You lose";
        $e_o = array();
        $char_a_o = array();
        $chal_a_o = array();
        $sut = new ChallengeModel($id, $i_t, $t_a, $t, $s_n, $f_n, $e_o, $char_a_o, $chal_a_o);

        $result = $sut->get_fail_id();

        $this->assertSame("You lose", $result);
    }
}

?>