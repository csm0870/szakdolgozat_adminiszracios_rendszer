<?= $this->Html->css('topic_pdf', ['fullBase' => true]) ?>
<div class="topic-form">
    <div class="header">
        Adatlap <?= $thesisTopic->is_thesis ? 'szakdolgozat' : 'diplomamunka' ?> téma engedélyezéséhez
    </div>
    <div class="data-group student-data">
        <div class="title">Hallgató adatai</div>
        <table>
            <tr>
                <td><span class="field">Név:</span>&nbsp;<?= $thesisTopic->has('student') ? h($thesisTopic->student->name) : ''?></td>
                <td><span class="field">Neptun kód:</span>&nbsp;<?= $thesisTopic->has('student') ? h($thesisTopic->student->neptun) : ''?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="field">Cím:</span>&nbsp;<?= $thesisTopic->has('student') ? h($thesisTopic->student->address) : ''?></td>
            </tr>
            <tr>
                <td><span class="field">Telefon:</span>&nbsp;<?= $thesisTopic->has('student') ? h($thesisTopic->student->phone_number) : ''?></td>
                <td><span class="field">e-mail:</span>&nbsp;<?= $thesisTopic->has('student') ? h($thesisTopic->student->email) : ''?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="field">Szak:</span>&nbsp;<?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course') ? h($thesisTopic->student->course->name) : '') : ''?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="field">Képzési szint:</span>&nbsp;<?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_level') ? h($thesisTopic->student->course_level->name) : '') : ''?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="field">Tagozat:</span>&nbsp;<?= $thesisTopic->has('student') ? ($thesisTopic->student->has('course_type') ? strtolower(h($thesisTopic->student->course_type->name)) : '') : ''?></td>
            </tr>
        </table>
    </div>
    <div class="data-group thesis-data">
        <div class="title">A szakdolgozat adatai</div>
        <table>
            <tr>
                <td><span class="field">Kezdő tanév és félév:</span>&nbsp;<?= ($thesisTopic->has('starting_year') ? h($thesisTopic->starting_year->year) : '') . '&nbsp;' .  ($thesisTopic->starting_semester == 0 ? 'Ősz' : 'Tavasz') ?></td>
            </tr>
            <tr>
                <td><span class="field">Várható leadás:</span>&nbsp;<?= ($thesisTopic->has('expected_ending_year') ? h($thesisTopic->expected_ending_year->year) : '') . '&nbsp;' .  ($thesisTopic->expected_ending_semester == 0 ? 'Ősz' : 'Tavasz') ?></td>
            </tr>
            <tr>
                <td><span class="field">Cím:</span>&nbsp;<?= h($thesisTopic->title) ?></td>
            </tr>
            <tr>
                <td><span class="field">Nyelv:</span>&nbsp;<?= $thesisTopic->has('language') ? h($thesisTopic->language->name) : '' ?></td>
            </tr>
            <tr>
                <td><span class="field">Típus:</span>&nbsp;<?= $thesisTopic->confidential === true ? 'titkos' : 'nyilvános' ?></td>
            </tr>
            <tr>
                <td><span class="field">Rövid leírás, részfeladatok:</span></td>
            </tr>
        </table>
        <div class="thesis-description text-justify">
            <?= h($thesisTopic->description) ?>
        </div>
    </div>
    <div class="data-group consultant-data">
        <table class="internal-consultant">
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
                <td><span class="field">Beosztás:</span>&nbsp;<?= $thesisTopic->has('internal_consultant') ? ($thesisTopic->internal_consultant->has('internal_consultant_position') ? h($thesisTopic->internal_consultant->internal_consultant_position->name) : '') : '' ?></td>
            </tr>
        </table>
        <!-- Ha van külső konzulens -->
        <?php if($thesisTopic->cause_of_no_external_consultant === null){ ?>            
            <table>
                <tr>
                    <td colspan="2"><div class="title">Külső konzulens adatai</div></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="field">Név:</span>&nbsp;<?= h($thesisTopic->external_consultant_name) ?></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="field">Munkahely:</span>&nbsp;<?= h($thesisTopic->external_consultant_workplace) ?></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="field">Beosztás:</span>&nbsp;<?= h($thesisTopic->external_consultant_position) ?></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="field">Cím:</span>&nbsp;<?= h($thesisTopic->external_consultant_address) ?></td>
                </tr>
                <tr>
                    <td><span class="field">Telefonszám:</span>&nbsp;<?= h($thesisTopic->external_consultant_phone_number) ?></td>
                    <td><span class="field">e-mail:</span>&nbsp;<?= h($thesisTopic->external_consultant_email) ?></td>
                </tr>
            </table>
        <?php }else{ ?>
            <div class="no-external-consultant">
                <span class="title">Külső konzulens kijelölésétől való eltekintés indoklása:</span><br/>
                <div class="description text-justify">
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
                    <td><div class="signature"></div></td>
                    <td><div class="signature"></div></td>
                    <td><div class="signature"></div></td>
                </tr>
                <tr>
                    <td>Belső konzulens</td>
                    <td>Külső konzulens</td>
                    <td>Tanszékvezető</td>
                </tr>
            </table>
        </div>
    </div>
</div>