<div class="reportDiv">
    <div class="top">
        <h3 class="text-center">Relatório de Vendas</h3>
        <ul class="nav nav-pills mb-3 justify-content-center" id="sales-tab" role="tablist">
            <li class="nav-item pr-2" role="presentation">
                <button class="nav-link active salesBtn" id="pills-sales-table-tab" data-toggle="pill"
                    data-target="#salesTableTab" style="border-radius: 14px;" type="button" role="tab"
                    aria-controls="pills-sales-table-tab" aria-selected="true">Tabela</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link salesBtn" id="pills-sales-chart-tab" data-toggle="pill"
                    data-target="#salesChartTab" style="border-radius: 14px;" type="button" role="tab"
                    aria-controls="pills-sales-chart-tab" aria-selected="false">Gráfico</button>
            </li>
        </ul>
    </div>
    <div class="tab-content botton">
        <div class="tab-pane fade show active reportTableDiv" id="salesTableTab" role="tabpanel"
            aria-labelledby="pills-sales-table-tab">
            <div class="table-responsive">
                <table id="salesTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Data</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade p-2" id="salesChartTab" role="tabpanel" aria-labelledby="pills-sales-chart-tab"
            style="width: 100%; height: 100%;">
            <canvas id="saleChart" />
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const dynamicColors = function() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    };

    const buildChart = function(data) {
        const chartData = {
            labels: data.map(d => d.data),
            datasets: [{
                data: data.map(d => Math.round(d.total * 100) / 100),
                backgroundColor: data.map(d => dynamicColors())
            }]
        }

        new Chart($('#saleChart'), {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        display: false,
                    }
                },
            },
        });
    };

    $.ajax({
        type: 'GET',
        url: '<?= BASE_URL . '/report/sales' ?>',
        success: function(data) {
            parsedData = JSON.parse(data);

            const formatter = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });

            const table = $("#salesTable tbody");
            $.each(parsedData, function(idx, elem) {
                table.append(
                    "<tr><td>" + elem.data +
                    "</td><td>" + formatter.format(elem.total) +
                    "</td></tr>");
            });

            $('#salesTable').DataTable({
                responsive: true
            });
            $('.dataTables_length').addClass('bs-select');

            buildChart(parsedData);
        }
    });
});
</script>