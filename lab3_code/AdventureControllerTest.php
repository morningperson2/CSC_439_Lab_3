<?php
require_once 'Main.php';
use PHPUnit\Framework\TestCase;

class AdventureControllerTest extends TestCase
{
    public function setUp() : void{
        $this->bus_challenge = new ChallengeModel(
        "bus_challenge",
        "You are attempting to catch the bus, but you are late, and it is pulling away from the stop.",
        "Speed",
        "HIGH",
        "seat_challenge",
        "_done",
        array(
            array("A"=> "You run as fast as you can, and you almost catch up to the bus.",
                "B"=> "You run as fast as you can, but you can't seem to gain any ground catching up to the bus."
            ),
            array("A"=> "You run with all of your might, and the driver sees you in the mirror waving, and lets you on.",
                "B" => "You step in a puddle and it slows you down.",
            ),
            array("A"=> "The bus pulls over out of pity and lets you on.",
                "B" => "You couldn't catch up, the bus drives away."
            )
        ),
        array(
            array("A"=> "The bus is pulling away, but you are pretty fast and easily catch it.",
                "B"=> "The bus is pulling away, and even though you are pretty fast, you can't seem to catch up."
            ),
            array("A"=> "Although harder than you expected, you put your head down and eventually catch up to the bus, getting on.",
                "B"=>"You are surprised to find you are not as fast as you once were."
            )
        ),
        array(
            array("B"=> "The bus is pulling away, and you are far to slow to really catch it, and it drives out of sight.",
                "A"=> "The bus is pulling away, and even though you are pretty slow, you seem to gain ground."
            ),
            array("B"=> "But try as you might, it eventually gets away.",
                "A"=>"By some miracle, you actually flag down the driver to stop, and get on."
                )
            )
        );
    }
    
    public function test_class_attributes(){
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);
        
        $this->assertEquals("brian", $sut->get_character()->get_name());
        $this->assertEmpty($sut->get_challenge_database());
    }
    
    public function test_add_challenge_unique_id(){
        $this->expectException(InvalidArgumentException::class);
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);
        
        $sut->add_challenge($this->bus_challenge);
        $sut->add_challenge($this->bus_challenge);
    }
    
    public function test_add_challenge_id_check(){
        $this->expectException(InvalidArgumentException::class);
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);
        
        $bad_challenge = new ChallengeModel("_end","","","","","",array(),array(),array());
        $sut->add_challenge($bad_challenge);
    }
    
    public function test_add_challenge_morethan_5(){
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);
        
        $bad_challenge1 = new ChallengeModel("1","","","","","",array(),array(),array());
        $bad_challenge2 = new ChallengeModel("2","","","","","",array(),array(),array());
        $bad_challenge3 = new ChallengeModel("3","","","","","",array(),array(),array());
        $bad_challenge4 = new ChallengeModel("4","","","","","",array(),array(),array());
        $bad_challenge5 = new ChallengeModel("5","","","","","",array(),array(),array());
        $bad_challenge6 = new ChallengeModel("6","","","","","",array(),array(),array());
        
        $sut->add_challenge($bad_challenge1);
        $sut->add_challenge($bad_challenge2);
        $sut->add_challenge($bad_challenge3);
        $sut->add_challenge($bad_challenge4);
        $sut->add_challenge($bad_challenge5);
        $sut->add_challenge($bad_challenge6);
        
        $this->assertEquals(5, count($sut->get_challenge_database()));
    }

    public function test_remove_challenge_no_match(){
        $this->expectException(InvalidArgumentException::class);
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);

        $sut->add_challenge($this->bus_challenge);

        $sut->remove_challenge("bad_ID");
    }

    public function test_remove_challenge_empty_database(){
        $this->expectException(InvalidArgumentException::class);
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);

        $sut->remove_challenge("bad_ID");
    }

    public function test_remove_challenge_null(){
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);

        $bad_challenge1 = new ChallengeModel("1","","","","","",array(),array(),array());
        $bad_challenge2 = new ChallengeModel("2","","","","","",array(),array(),array());
        $bad_challenge3 = new ChallengeModel("3","","","","","",array(),array(),array());
        $bad_challenge4 = new ChallengeModel("4","","","","","",array(),array(),array());
        $bad_challenge5 = new ChallengeModel("5","","","","","",array(),array(),array());
        
        $sut->add_challenge($bad_challenge1);
        $sut->add_challenge($bad_challenge2);
        $sut->add_challenge($bad_challenge3);
        $sut->add_challenge($bad_challenge4);
        $sut->add_challenge($bad_challenge5);

        $sut->remove_challenge();

        $this->assertEmpty($sut->get_challenge_database());
    }

    public function test_remove_challenge(){
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);

        $bad_challenge1 = new ChallengeModel("1","","","","","",array(),array(),array());
        $bad_challenge2 = new ChallengeModel("2","","","","","",array(),array(),array());

        $sut->add_challenge($bad_challenge1);
        $sut->add_challenge($bad_challenge2);

        $sut->remove_challenge("1");

        $this->assertCount(1, $sut->get_challenge_database());
    }

    public function test_validate_challenge_noStart(){
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);

        $bad_challenge1 = new ChallengeModel("1","","","","","",array(),array(),array());
        $sut->add_challenge($bad_challenge1);

        $result = $sut->validate_challenge();

        $this->assertFalse($result);
        
        $bad_challenge2 = new ChallengeModel("_start","","","","","_end",array(),array(),array());
        $sut->add_challenge($bad_challenge2);

        $result2 = $sut->validate_challenge();
        
        $this->assertTrue($result2);

    }

    public function test_validate_challenge_noEnd(){
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);

        $bad_challenge1 = new ChallengeModel("_start","","","","2","",array(),array(),array());
        $bad_challenge2 = new ChallengeModel("2","","","","_end","",array(),array(),array());

        $sut->add_challenge($bad_challenge1);

        $result = $sut->validate_challenge();

        $this->assertFalse($result);
        
        $sut->add_challenge($bad_challenge2);
        
        $result2 = $sut->validate_challenge();
        
        $this->assertTrue($result2);

    }
    
    public function test_validate_challenge_noPath(){
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "brian", 0);
        $database = array();
        $sut = new AdventureController($character, $database);

        $bad_challenge1 = new ChallengeModel("_start","","","","2","",array(),array(),array());
        $bad_challenge2 = new ChallengeModel("2","","","","_end","",array(),array(),array());

        $sut->add_challenge($bad_challenge1);
    }
    
}


?>