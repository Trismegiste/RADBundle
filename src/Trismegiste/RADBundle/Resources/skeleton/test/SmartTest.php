<?php 
use Trismegiste\RADBundle\Generator\UnitTestGenerator;
echo '<?php' ?>

namespace <?php echo implode('\\', $namespace4Test) ?>;

use <?php echo $fqcnTestedClass ?>;

/**
* Unit testing for <?php echo $info['classname'] . PHP_EOL ?>
*/
class <?php echo $info['classname'] ?>Test extends \PHPUnit_Framework_TestCase
{

/**
* Create an instance of <?php echo $info['classname'] . PHP_EOL ?>
*/
protected function createInstance()
{
<?php
$compilParam = array();
if (array_key_exists('__construct', $info['method'])) {
    $signature = $info['method']['__construct'];
    foreach ($signature as $argName => $argInfo) {
        $compilParam[] = '$' . $argName;

        if (strlen($argInfo['type'])) {
            ?>
            $<?php echo $argName ?> = $this->getMock('<?php echo $argInfo['type'] ?>'
            <?php if (isset($argInfo['call'])) : ?>
                , array(<?php
                echo implode(array_map(function($val) {
                                    return "'$val'";
                                }, $argInfo['call']))
                ?>
            <?php else : ?>
                , array()
            <?php endif ?>, array(), '', true, true, false);
        <?php } else { ?>
            $<?php echo $argName ?> = 42;
            <?php
        }
    }
}
?>
$new<?php echo $info['classname'] ?> = new <?php printf("%s(%s);", $info['classname'], implode(',', $compilParam)) ?>

return $new<?php echo $info['classname'] ?>;
}

protected function setUp()
{
$this->instance = $this->createInstance();
}

protected function tearDown()
{
unset($this->instance);
}

<?php
foreach ($info['method'] as $method => $signature) {
    if ($method == '__construct') {
        continue;
    }
    ?>
    /**
    * Testing call for <?= $method . PHP_EOL ?>
    */
    public function testCalling<?= ucfirst($method) ?>()
    {
    <?php $compilParam = UnitTestGenerator::dumpCalling($method, $signature) ?>
    $this->assertNotNull($this->instance);
    $this->instance-><?php printf("%s(%s)", $method, implode(',', $compilParam)) ?>;
    }
<?php } ?>

<?php foreach ($info['mutator'] as $property) : ?>
    /**
    * Testing mutator for property : <?= $property . PHP_EOL ?>
    */
    public function testProperty<?= ucfirst($property) ?>()
    {
    <?php
    $setter = 'set' . ucfirst($property);
    $getter = 'get' . ucfirst($property);
    $compilParam = UnitTestGenerator::dumpCalling('set' . ucfirst($setter), $info['method'][$setter])
    ?>
    $this->instance-><?php printf("%s(%s)", $setter, implode(',', $compilParam)) ?>;
    $this->assertEquals(<?= $compilParam[0] ?>, $this->instance-><?= $getter ?>());
    $this->assertNotEquals(666, $this->instance-><?= $getter ?>());
    }
<?php endforeach ?>

<?php foreach ($info['throw'] as $method => $stmts) : ?>
    <?php foreach ($stmts as $idx => $oneThrow) : ?>
        /**
        * In method <?= $method . PHP_EOL ?>
        * Cover exception for <?= $oneThrow . PHP_EOL ?>
        *
        * @expectedException \Exception
        */
        public function test<?= $method ?>Throws<?= $idx ?>()
        {
        <?php $compilParam = UnitTestGenerator::dumpCalling($method, $info['method'][$method]) ?>
        // do something
        $this->instance-><?php printf("%s(%s)", $setter, implode(',', $compilParam)) ?>;
        }
    <?php endforeach ?>
<?php endforeach ?>

}

