<?= $this->Html->css('topic_pdf', ['fullBase' => true]) ?>
<div class="topic-form">
    <div class="header">
        Feladat-kiíró lap <?= $thesisTopic->is_thesis ? 'szakdolgozathoz' : 'diplomamunkához'?>
    </div>
    <div class="data-group student-data">
        <div class="title">Hallgató adatai</div>
        <table>
            <tr>
                <td><span class="field">Név:</span>&nbsp;<?= $thesisTopic->has('student') ? h($thesisTopic->student->name) : ''?></td>
                <td><span class="field">Neptun kód:</span>&nbsp;<?= $thesisTopic->has('student') ? h($thesisTopic->student->neptun) : ''?></td>
            </tr>
            <tr>
                <td><span class="field">Szak:</span>&nbsp;<?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? h($thesisTopic->student->course_level->name) : '') : ''?></td>
                <td></td>
            </tr>
            <tr>
                <td><span class="field">Specializáció:</span>&nbsp;<?= $thesisTopic->has('student') ? (empty($thesisTopic->student->specialisation) ? '-' : $thesisTopic->student->specialisation) : ''?></td>
                <td><span class="field">Tagozat:</span>&nbsp;<?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? h($thesisTopic->student->course_type->name) : '') : ''?></td>
            </tr>
        </table>
    </div>
    <div class="data-group thesis-data">
        <div class="title">A szakdolgozat adatai</div>
        <table>
            <tr>
                <td><span class="field">Kezdő tanév és félév:</span>&nbsp;<?= ($thesisTopic->has('year') ? h($thesisTopic->year->year) : '') . '/' .  ($thesisTopic->starting_semester == 0 ? '1' : '2') ?></td>
            </tr>
            <tr>
                <td><span class="field">Nyelv:</span>&nbsp;<?= h($thesisTopic->language) ?></td>
            </tr>
            <tr>
                <td><span class="field">Típus:</span>&nbsp;<?= $thesisTopic->encrypted ? 'titkos' : 'nyilvános' ?></td>
            </tr>
        </table>
        <div class="thesis-title">
            <?= h($thesisTopic->title) ?>
        </div>
        <div class="thesis-description">
            Feladatok részletes leírása:<br/><br/>
            <div class="text-justify">
                <?= h($thesisTopic->description) ?>
            </div>
        </div>
    </div>
    <div class="data-group consultant-data">
        <!-- Ha van külső konzulens -->
        <?php if($thesisTopic->cause_of_no_external_consultant === null){ ?>            
            <table>
                <tr>
                    <td><div class="title">Belső konzulens adatai</div></td>
                    <td><div class="title">Külső konzulens adatai</div></td>
                </tr>
                <tr>
                    <td><span class="field">Név:</span>&nbsp;<?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?></td>
                    <td><span class="field">Név:</span>&nbsp;<?= h($thesisTopic->external_consultant_name) ?></td>
                </tr>
                <tr>
                    <td><span class="field">Tanszék:</span>&nbsp;<?= $thesisTopic->has('internal_consultant') ? ($thesisTopic->internal_consultant->has('department') ? h($thesisTopic->internal_consultant->department->name) : '') : '' ?></td>
                    <td><span class="field">Munkahely:</span>&nbsp;<?= h($thesisTopic->external_consultant_workplace) ?></td>
                </tr>
                <tr>
                    <td><span class="field">Beosztás:</span>&nbsp;<?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->position) : '' ?></td>
                    <td><span class="field">Beosztás:</span>&nbsp;<?= h($thesisTopic->external_consultant_position) ?></td>
                </tr>
            </table>
        <?php }else{ ?>
            <table>
                <tr>
                    <td><div class="title">Belső konzulens adatai</div></td>
                </tr>
                <tr>
                    <td><span class="field">Név:</span>&nbsp;<?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->name) : '' ?></td>
                </tr>
                <tr>
                    <td><span class="field">Tanszék:</span>&nbsp;<?= $thesisTopic->has('internal_consultant') ? ($thesisTopic->internal_consultant->has('department') ? h($thesisTopic->internal_consultant->department->name) : '') : '' ?></td>
                </tr>
                <tr>
                    <td><span class="field">Beosztás:</span>&nbsp;<?= $thesisTopic->has('internal_consultant') ? h($thesisTopic->internal_consultant->position) : '' ?></td>
                </tr>
            </table>
            <div class="no-external-consultant">
                <span class="title">Külső konzulens kijelölésétől való eltekintés indoklása:</span><br/><br/>
                <div class="text-justify">
                    <?= h($thesisTopic->cause_of_no_external_consultant) ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="footer">
        <?php $hun_months = ["január", "február", "március", "április", "május", "június",
                             "július", "augusztus", "szeptember", "október", "november", "december"];?>
        Győr, <?= date('Y') . '. ' . $hun_months[intval(date('n')) - 1] . ' ' . date('j') . '.'?>
        <div class="signatures">
            <table>
                <tr>
                    <td><div class="signature">belső konzulens</div></td>
                    <td><div class="signature">külső konzulens</div></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="signature">
                            <?= $thesisTopic->has('internal_consultant') ? ($thesisTopic->internal_consultant->has('department') ? (h($thesisTopic->internal_consultant->department->name) . ',<br/>') : '') : '' ?>
                            <?= $thesisTopic->has('internal_consultant') ? ($thesisTopic->internal_consultant->has('department') ? h($thesisTopic->internal_consultant->department->head_of_department) : '') : '' ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>