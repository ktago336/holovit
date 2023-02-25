<div id="user_ch" style="width: 100%; height: 250px;"></div>
<script>
<?php if($dayCount == 365): ?>
   $(function () {
    $('#user_ch').highcharts({
        title: { text: ''},
        xAxis: { categories: [<?php echo $catArray;?>] },
        yAxis: {
            title: { text: 'Number of Customers' },
            gridLineWidth: 1,
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        exporting: { enabled: false },
        credits: { enabled: false},
        legend: { enabled: false},
        series: [{
            name: 'Customers',
            data: [<?php echo $finalArray;?>]
        }]
    });
}); 
<?php else: ?>
    $(function () {
    $('#user_ch').highcharts({
        title: { text: ''},
        xAxis: { type: 'datetime', dateTimeLabelFormats: { day: '%e %b'}},
        yAxis: {
            title: { text: 'Number of Customers' },
            gridLineWidth: 1,
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        exporting: { enabled: false },
        credits: { enabled: false},
        legend: { enabled: false},
        series: [{
            name: 'Customers',
            data: [ <?php echo e($finalArray); ?>]
        }]
    });   
});
<?php endif; ?>
$("#user_chart_loader").hide();
</script>



<?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/elements/admin/chart.blade.php ENDPATH**/ ?>