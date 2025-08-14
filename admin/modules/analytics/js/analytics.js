$(function(){
    var ctx = document.getElementById('analyticsChart').getContext('2d');
    var chart;

    function loadData(){
        var params = {
            start: $('#start_date').val(),
            end: $('#end_date').val(),
            category: $('#category').val(),
            channel: $('#channel').val()
        };
        $.getJSON('/admin/modules/analytics/bin/data.php', params, function(res){
            if(chart){ chart.destroy(); }
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: res.labels,
                    datasets: [
                        { label: 'Bezoeken', data: res.visits, backgroundColor: 'rgba(54, 162, 235, 0.5)' },
                        { label: 'Omzet', data: res.revenue, backgroundColor: 'rgba(75, 192, 192, 0.5)' }
                    ]
                }
            });
        });
    }

    $('#filter').on('click', loadData);
    $('#export_csv').on('click', function(){
        var params = $.param({
            start: $('#start_date').val(),
            end: $('#end_date').val(),
            category: $('#category').val(),
            channel: $('#channel').val()
        });
        window.location = '/admin/modules/analytics/bin/export.php?' + params;
    });

    loadData();
});
