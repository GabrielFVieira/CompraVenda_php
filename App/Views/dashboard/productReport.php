<div class="reportDiv">
    <div class="top">
        <h3 class="text-center">Relatório de Estoque</h3>
        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item pr-2" role="presentation">
                <button class="nav-link active inventoryBtn" id="pills-home-tab" data-toggle="pill"
                    data-target="#pills-home" style="border-radius: 14px;" type="button" role="tab"
                    aria-controls="pills-home" aria-selected="true">Tabela</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link inventoryBtn" id="pills-profile-tab" data-toggle="pill"
                    data-target="#pills-profile" style="border-radius: 14px;" type="button" role="tab"
                    aria-controls="pills-profile" aria-selected="false">Gráfico</button>
            </li>
        </ul>
    </div>
    <div class="tab-content botton">
        <div class="tab-pane fade show active reportTableDiv" id="pills-home" role="tabpanel"
            aria-labelledby="pills-home-tab">
            <div class="table-responsive">
                <table id="inventoryTable" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Produto</th>
                            <th scope="col">Quantidade</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade p-2" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
            style="width: 100%; height: 100%;">
            <canvas id="productChart" />
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
            labels: data.map(d => d.nome),
            datasets: [{
                data: data.map(d => d.quantidadeDisponivel),
                backgroundColor: data.map(d => dynamicColors())
            }]
        }

        new Chart($('#productChart'), {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 18,
                            }
                        },
                    },
                    title: {
                        display: false,
                    },
                },
            },
        });
    };

    $.ajax({
        type: 'GET',
        url: '<?= BASE_URL . '/report/products' ?>',
        success: function(data) {
            parsedData = JSON.parse(data);

            const table = $("#inventoryTable tbody");
            $.each(parsedData, function(idx, elem) {
                table.append("<tr><td>" + elem.nome + "</td><td>" + elem
                    .quantidadeDisponivel + "</td></tr>");
            });

            $('#inventoryTable').DataTable({
                responsive: true,
                scrollX: false,
                scrollY: false,
                paging: false,
                info: false
            });
            $('.dataTables_length').addClass('bs-select');

            buildChart(parsedData);
        }
    });

    $('.inventoryBtn').on('shown.bs.tab', function(e) {
        $('#inventoryTable').DataTable().columns.adjust();
    });
});
</script>