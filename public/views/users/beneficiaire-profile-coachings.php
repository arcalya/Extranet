<table class="data table table-striped no-margin">
    <thead>
        <tr>
            <th>#</th>
            <th>Atelier</th>
            <th>Description</th>
            <th class="hidden-phone">Durée</th>
            <th>Evaluation</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        if( isset( $datas->workshops ) )
        {
            foreach( $datas->workshops as $w => $workshops )
            { 
            ?>
            <tr>
            <td><?php echo ( $w + 1 ); ?></td>
            <td>
                <?php echo $workshops->NomCoaching; ?><br />
                <small><?php echo $workshops->PrenomFormateur.' '.$workshops->NomFormateur; ?></small>
            </td>
            <td>
                <p><strong><?php echo $workshops->Date; ?></strong></p>
                <?php echo ( !empty( $workshops->PrerequisCoaching ) ) ? '<p><strong>Prérequis :</strong> '.$workshops->PrerequisCoaching.'</p>' : ''; ?>
                <?php echo ( !empty( $workshops->DescriptionCoaching ) ) ? '<p><strong>Description :</strong> '.$workshops->DescriptionCoaching.'</p>' : ''; ?>
                <?php echo ( !empty( $workshops->RemarquesCoaching ) ) ? '<p><strong>Prérequis :</strong> '.$workshops->RemarquesCoaching.'</p>' : ''; ?>
            </td>
            <td class="hidden-phone">
                <?php echo $workshops->NbPeriodeCoaching; ?>
            </td>
            <td class="vertical-align-mid">
                <?php if( $workshops->EvalAverage > 0 ){ ?>
                <p class="ratings">
                    <a><?php echo $workshops->EvalAverage; ?></a>
                    <a href="#"><span class="mdi mdi-star<?php echo ( !$workshops->Note1 ) ? '-outline' : ''; ?>"></span></a>
                    <a href="#"><span class="mdi mdi-star<?php echo ( !$workshops->Note2 ) ? '-outline' : ''; ?>"></span></a>
                    <a href="#"><span class="mdi mdi-star<?php echo ( !$workshops->Note3 ) ? '-outline' : ''; ?>"></span></a>
                    <a href="#"><span class="mdi mdi-star<?php echo ( !$workshops->Note4 ) ? '-outline' : ''; ?>"></span></a>
                    <a href="#"><span class="mdi mdi-star<?php echo ( !$workshops->Note5 ) ? '-outline' : ''; ?>"></span></a>
                </p>
                <?php } ?>
            </td>
            </tr>
            <?php
            }
        }
        else 
        {
            ?>
            <tr>
                <td colspan="5">Aucune formation n'a été suivie.</td>
            </tr>
            <?php
        }
    ?>
    </tbody>
</table>