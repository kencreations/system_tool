<?php $pageTitle = "Chart Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #f8fafc; display: flex; flex-direction: column; }
        .preview-area { flex-grow: 1; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .chart-container { width: 600px; background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; }
        
        .properties-panel { width: 320px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; overflow-y: auto; }
        .form-select { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 15px; }
        
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 50; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 60%; background: #1e1e1e; color: #ccc; padding: 20px; border-radius: 8px; font-family: monospace; }
        .code-text { width:100%; height:85%; background:transparent; border:none; color:inherit; resize:none; outline:none; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div style="height:60px; background:#fff; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; padding:0 20px;">
            <h3>Chart Builder</h3>
            <button onclick="showCode()" class="btn btn-outline">View JS Code</button>
        </div>
        <div class="preview-area">
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </main>

    <aside class="properties-panel">
        <h4>Configuration</h4>
        <label>Chart Type</label>
        <select id="chartType" class="form-select" onchange="renderChart()">
            <option value="line">Line Chart</option>
            <option value="bar">Bar Chart</option>
            <option value="doughnut">Doughnut</option>
            <option value="pie">Pie</option>
        </select>

        <label>Theme Color</label>
        <select id="chartColor" class="form-select" onchange="renderChart()">
            <option value="#3b82f6">Blue</option>
            <option value="#10b981">Green</option>
            <option value="#8b5cf6">Purple</option>
            <option value="#f59e0b">Orange</option>
        </select>
        
        <div style="font-size:12px; color:#64748b; margin-top:10px;">
            <i class="ri-information-line"></i> This tool generates a Chart.js configuration.
        </div>
    </aside>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <div style="text-align:right;"><button onclick="document.getElementById('codeModal').style.display='none'" class="btn btn-danger">Close</button></div>
            <textarea class="code-text" id="codeOutput" spellcheck="false"></textarea>
        </div>
    </div>

    <script>
        let myChart = null;

        function renderChart() {
            const type = document.getElementById('chartType').value;
            const color = document.getElementById('chartColor').value;
            const ctx = document.getElementById('myChart').getContext('2d');

            if(myChart) myChart.destroy();

            // Config Data
            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const data = [12, 19, 3, 5, 2, 3];
            const bg = type === 'line' ? 'transparent' : color;
            const border = color;

            myChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales Data',
                        data: data,
                        backgroundColor: type === 'doughnut' || type === 'pie' ? 
                            ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6366f1'] : 
                            (type === 'line' ? color : color + '80'), // Add transparency for bars
                        borderColor: border,
                        borderWidth: 2,
                        tension: 0.4 // Curve for lines
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        function showCode() {
            const type = document.getElementById('chartType').value;
            const js = `
<script src="https://cdn.jsdelivr.net/npm/chart.js"><\/script>

<div style="width: 600px;"><canvas id="myChart"></canvas></div>

<script>
const ctx = document.getElementById('myChart').getContext('2d');
new Chart(ctx, {
    type: '${type}',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Dataset 1',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: '${document.getElementById('chartColor').value}',
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});
<\/script>`;
            document.getElementById('codeOutput').value = js;
            document.getElementById('codeModal').style.display = 'flex';
        }

        renderChart();
    </script>
</body>
</html>