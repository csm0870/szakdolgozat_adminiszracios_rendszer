<?= $this->Html->css('consultation_pdf', ['fullBase' => true]) ?>
<div class="consultation-pdf">
    <div class="header">
        Konzultációs lap szakdolgozathoz és diplomamunkához
    </div>
    <div class="body">
        <div class="data-group student-data">
            <div class="title">Hallgató adatai</div>
            <table>
                <tr>
                    <td><span class="field">Név:</span>&nbsp;<?= h($student->name) ?></td>
                    <td><span class="field">Neptun kód:</span>&nbsp;<?= h($student->neptun)?></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="field">Szak:</span>&nbsp;<?= $student->has('course') ? (h($student->course->name) . ($student->has('course_level') ? ('&nbsp;' . h($student->course_level->name)) : '')) : ''?></td>
                </tr>
            </table>
        </div>
        <div class="consultationOccasions">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%"><?= __('Dátum') ?></th>
                        <th style="width: 60%"><?= __('Tevékenység') ?></th>
                        <th style="width: 25%"><?= __('Aláírás') ?></th>
                    </tr>
                    <?php foreach ($consultation->consultation_occasions as $consultationOccasion){ ?>
                    <tr>
                        <td><?= empty($consultationOccasion->date) ? '' : $this->Time->format($consultationOccasion->date, 'yyyy.MM.dd.') ?></td>
                        <td><?= h($consultationOccasion->activity) ?></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                </thead>
            </table>
        </div>
    </div>
    <div class="footer">
        <div class="accpeted">
            A dolgozat a formai követelményeknek <?= $consultation->accepted === true ? 'megfelel' : 'nem felel meg' ?>
        </div>
        <div class="signatures">
            <table>
                <tr>
                    <td><div class="signature">dátum</div></td>
                    <td><div class="signature"><?= h(trim($internalConsultant->name)) . ($internalConsultant->has('internal_consultant_position') ? (empty($internalConsultant->internal_consultant_position->name) ? '' : (', ' . h($internalConsultant->internal_consultant_position->name))) : '') ?></div></td>
                </tr>
            </table>
        </div>
    </div>
</div>