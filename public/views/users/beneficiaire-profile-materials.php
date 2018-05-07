<table class="data table table-striped no-margin">
    <thead>
        <tr>
            <th>#</th>
            <th>Matériel</th>
            <th>Statut</th>
            <th>Du</th>
            <th>Au</th>
        </tr>
    </thead>
    <tbody>
    <?php 
        if( isset( $datas->emprunts ) )
        {
            foreach( $datas->emprunts as $e => $emprunt )
            { 
            ?>
            <tr class="<?php echo ( $emprunt->empruntsToLate ) ? 'danger' : ''; ?>">
            <td><?php echo ( $e + 1 ); ?></td>
            <td><?php echo $emprunt->NomArticle.' ('.$emprunt->NomType.') <br />'.$emprunt->NomCategorie; ?></td>
            <td><?php echo $emprunt->StatutEmpruntName; ?></td>
            <td><span style="white-space:nowrap;"><?php echo $emprunt->DebutEmprunt; ?></span></td>
            <td><span style="white-space:nowrap;"><?php echo $emprunt->FinEmprunt; ?></span></td>
            </tr>
            <?php
            }
        }
        else 
        {
            ?>
            <tr>
                <td colspan="5">Aucun matériel n'a été suivie.</td>
            </tr>
            <?php
        }
    ?>
    </tbody>
</table>