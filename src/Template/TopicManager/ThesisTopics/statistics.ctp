<!-- Chart.js -->
<?= $this->Html->script('Chart.min.js') ?>
<div class="container thesisTopic-statistics">
    <div class="row">
        <div class="col-12 text-center page-title">
            <h4><?= __('Téma kimutatások') ?></h4>
        </div>
        <?= $this->Flash->render() ?>
        <div class="col-12">
            <canvas id="chart_for_courses"></canvas>
        </div>
        <div class="col-12">
            <canvas id="chart_for_course_types"></canvas>
        </div>
        <div class="col-12">
            <canvas id="chart_for_course_levels"></canvas>
        </div>
    </div>
</div>
<script>
    
    /*
     * RGBA színtöbböt generál, fix 0.2-es áttetszőséggel
     * @param {type} number_of_colors - generálandó színek darabszáma
     * @return {Array|generateRGBAColors.colors|Boolean}
     */
    function generateRGBAColors(number_of_colors = 1){
        if(!$.isNumeric(number_of_colors) || number_of_colors < 0) return [];
        
        var colors = [];
        for(var i = 0; i < number_of_colors; i++){
            colors.push('rgba(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ', 0.2)');
        }
        
        return colors;
    }
    
    $(function(){
        //Képzések
        var labels_for_courses = <?= json_encode($labels_for_courses) ?>;
        var data_for_courses = <?= json_encode($data_for_courses) ?>;
        var backgroundColors = generateRGBAColors(labels_for_courses.length);
        
        //canvas a chart-nak
        var ctx = document.getElementById('chart_for_courses').getContext('2d');
        var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: labels_for_courses,
                datasets: [{
                        label: '<?= __('Képzések') ?>',
                        data: data_for_courses,
                        backgroundColor: backgroundColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            },
                        }]				
                    }
                }
        });
        
        //Képzés típusok
        var labels_for_course_types = <?= json_encode($labels_for_course_types) ?>;
        var data_for_course_types = <?= json_encode($data_for_course_types) ?>;
        var backgroundColors = generateRGBAColors(labels_for_course_types.length);
        
        //canvas a chart-nak
        var ctx = document.getElementById('chart_for_course_types').getContext('2d');
        var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: labels_for_course_types,
                datasets: [{
                        label: '<?= __('Képzés típusok') ?>',
                        data: data_for_course_types,
                        backgroundColor: backgroundColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            },
                        }]				
                    }
                }
        });
        
        //Képzés szintek
        var labels_for_course_levels = <?= json_encode($labels_for_course_levels) ?>;
        var data_for_course_levels = <?= json_encode($data_for_course_levels) ?>;
        var backgroundColors = generateRGBAColors(labels_for_course_levels.length);
        
        //canvas a chart-nak
        var ctx = document.getElementById('chart_for_course_levels').getContext('2d');
        var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: labels_for_course_levels,
                datasets: [{
                        label: '<?= __('Képzés szintek') ?>',
                        data: data_for_course_levels,
                        backgroundColor: backgroundColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            },
                        }]				
                    }
                }
        });
    });
</script>