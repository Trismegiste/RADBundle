<?php
function dumpCalling($method, $signature) {
    $compilParam = array();
    foreach ($signature as $argName => $argInfo) {
        $compilParam[] = '$' . $argName;

        if (strlen($argInfo['type'])) { ?>
            $<?= $argName ?> = $this->getMock('<?= $argInfo['type'] ?>'
            <?php if (!empty($argInfo['call'])) : ?>
                , array(<?php echo implode(array_map(function($val) { return "'$val'"; } , array_keys($argInfo['call']))) ?>)
            <?php else : ?>
                , array()
            <?php endif ?>, array(), '', true, true, false);
        <?php } else { ?>
            $<?= $argName ?> = 42;
        <?php }
    }
    foreach ($signature as $argName => $argInfo) : ?>
        <?php if (!empty($argInfo['call'])) : ?>
            <?php foreach($argInfo['call'] as $oneStub => $cpt) : ?>
                $<?= $argName ?>->expects($this->exactly(<?= $cpt ?>))->method('<?= $oneStub ?>');
            <?php endforeach ?>
        <?php endif ?>
    <?php endforeach;

    return $compilParam;
}