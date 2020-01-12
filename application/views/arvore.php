<?php
if ( isset($cao)):
//    echo $cao;
    ?>
<style>
table, th, td {
  border: 1px solid black;
}
</style>
<table>
    <tr>
        <td rowspan="4"><?php echo $cao;?></td>
        <td rowspan="2"><?php echo $pai;?></td>
        <td><?php echo $pai_pai;?></td>
    </tr>
    <tr>
        <td><?php echo $pai_mae;?></td>
    </tr>
    <tr>
        <td rowspan="2"><?php echo $mae;?></td>
        <td><?php echo $mae_pai;?></td>
    </tr>
    <tr>
        <td><?php echo $mae_mae;?></td>
    </tr>
</table>
    <?php
endif;
