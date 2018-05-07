<header class="clearfix">
    <div class="title_left">
        <h3>Questions interventions</h3>
    </div>
</header>

<div class="row">
    <div class="col-md-12">


        <?php
        self::_render('components/window-modal', [
            'idname' => 'delete',
            'title' => 'Suppression de module',
            'content' => 'Etes-vous sûr de vouloir supprimer ce module ?',
            'submitbtn' => 'Supprimer'
        ]);
        ?>

        <section class="profile clearfix">
            <?php
            self::_render('components/section-toolsheader', [
                'title' => 'Question de la demande d\'intervention',
                'tool-add-right' => 'add',
                'tool-update' => false,
                'tool-delete' => false,
                'tool-minified' => true,
                'rightpage' => 'users',
                'alertbox-display' => false
            ]);
            ?>

            <div class="minified">
                <table id="table" class="table table-striped responsive-utilities jambo_table datatable<?php echo ( isset($datas->table['class']) ) ? ' ' . $datas->table['class'] : ''; ?>">
           
                        <?php self::_render('components/table-head', $datas); ?>
                        <?php
                        foreach ($datas->QuestionsByCategory->{"1"} as $question) {
                            //Ne fonctionne pas voir avec ODO self::_render('components/table-cell', [ 'content' => $question->Question]);
                            ?>
                        <tr class="cell-h1">
                            <td colspan="9">
                                <?php echo ( $question->Question ) ?>
                            </td>
                        </tr>
                        <tr data-level="0">
                            
                        </tr>
                    <?php } ?>
        
                </table>
            </div>
        </section>
        <section class="profile clearfix">
            <?php
            self::_render('components/section-toolsheader', [
                'title' => 'Question du processus d\'intervention',
                'tool-add-right' => 'add',
                'tool-update' => false,
                'tool-delete' => false,
                'tool-minified' => true,
                'rightpage' => 'users',
                'alertbox-display' => false
            ]);
            ?>
            <div class="minified">
                <table id="table" class="table table-striped responsive-utilities jambo_table datatable<?php echo ( isset($datas->table['class']) ) ? ' ' . $datas->table['class'] : ''; ?>">
                    <tr class="cell-h1">
                        <td colspan="9"><?php ?> Question 1</td>
                    </tr>
                    <td>test2</td>
                </table>
            </div>
        </section>
        <section class="profile clearfix">
            <?php
            self::_render('components/section-toolsheader', [
                'title' => 'Question d\'évaluation de la prestation ',
                'tool-add-right' => 'add',
                'tool-update' => false,
                'tool-delete' => false,
                'tool-minified' => true,
                'rightpage' => 'users',
                'alertbox-display' => false
            ]);
            ?>

        </section>
    </div>
</div>