<?php require __DIR__ . '/BuildCallingMethod.php' ?>
<?php echo '<?php' ?>

namespace <?php echo implode('\\',$info['namespace']) ?>;

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

                if (strlen($argInfo['type'])) { ?>
                    $<?php echo $argName ?> = $this->getMock('<?php echo $argInfo['type'] ?>'
                    <?php if (!isset($argInfo['call'])) : ?>
                        , array(<?php echo implode(array_map(function($val) { return "'$val'"; } , $argInfo['call'])) ?>
                    <?php else : ?>
                        , array()
                    <?php endif ?>, array(), '', true, true, false);
                <?php } else { ?>
                    $<?php echo $argName ?> = 42;
                <?php }
            }
        } ?>
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

    <?php foreach($info['method'] as $method => $signature) {
        if ($method == '__construct') { continue; } ?>
     /**
     * Testing call for <?= $method . PHP_EOL ?>
     */
    public function testCalling<?= ucfirst($method) ?>()
    {
        <?php $compilParam = dumpCalling($method, $signature) ?>
        $this->assertNotNull($this->instance);
        $this->instance-><?php printf("%s(%s)", $method, implode(',', $compilParam)) ?>;
    }
    <?php } ?>

}

