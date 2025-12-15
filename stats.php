<?php $pageTitle = "Stats Widget Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #f1f5f9; display: flex; flex-direction: column; }
        .preview-area { flex-grow: 1; display: flex; align-items: center; justify-content: center; padding: 40px; }
        
        /* THE WIDGET CSS */
        .stat-card {
            background: #fff; border-radius: 12px; padding: 24px; width: 300px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;
            display: flex; flex-direction: column; gap: 10px; transition: transform 0.2s;
        }
        .stat-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .stat-label { font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-icon { 
            width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;
            font-size: 20px; 
        }
        .stat-value { font-size: 28px; font-weight: 700; color: #1e293b; letter-spacing: -0.5px; }
        .stat-footer { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; }
        .trend-up { color: #10b981; } .trend-down { color: #ef4444; } .trend-neutral { color: #64748b; }
        
        /* PROPERTIES PANEL */
        .properties-panel { width: 320px; background: #fff; border-left: 1px solid #cbd5e1; padding: 20px; overflow-y: auto; }
        .form-input, .form-select { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 10px; font-size:13px; }
        
        .code-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 50; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 60%; background: #1e1e1e; color: #ccc; padding: 20px; border-radius: 8px; font-family: monospace; }
        .code-text { width:100%; height:85%; background:transparent; border:none; color:inherit; resize:none; outline:none; font-size:13px; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div style="height:60px; background:#fff; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; padding:0 20px;">
            <h3>Stats Builder</h3>
            <button onclick="document.getElementById('codeModal').style.display='flex'" class="btn btn-outline" style="font-size:0.8rem;">View Code</button>
        </div>
        <div class="preview-area" id="renderArea">
            </div>
    </main>

    <aside class="properties-panel">
        <h4>Content</h4>
        <label style="font-size:12px; font-weight:600;">Label</label>
        <input type="text" id="optLabel" class="form-input" value="Total Revenue" oninput="render()">
        
        <label style="font-size:12px; font-weight:600;">Value</label>
        <input type="text" id="optValue" class="form-input" value="$45,231.89" oninput="render()">
        
        <label style="font-size:12px; font-weight:600;">Icon Class (RemixIcon)</label>
        <input type="text" id="optIcon" class="form-input" value="ri-money-dollar-circle-line" oninput="render()">

        <h4 style="margin-top:20px;">Trend</h4>
        <label style="font-size:12px; font-weight:600;">Trend Text</label>
        <input type="text" id="optTrend" class="form-input" value="+20.1% from last month" oninput="render()">
        
        <label style="font-size:12px; font-weight:600;">Trend Type</label>
        <select id="optTrendType" class="form-select" onchange="render()">
            <option value="up">Positive (Green)</option>
            <option value="down">Negative (Red)</option>
            <option value="neutral">Neutral (Gray)</option>
        </select>

        <h4 style="margin-top:20px;">Style</h4>
        <label style="font-size:12px; font-weight:600;">Icon Color</label>
        <select id="optColor" class="form-select" onchange="render()">
            <option value="blue">Blue</option>
            <option value="green">Green</option>
            <option value="purple">Purple</option>
            <option value="orange">Orange</option>
        </select>
    </aside>

    <div id="codeModal" class="code-modal">
        <div class="code-window">
            <div style="text-align:right; margin-bottom:10px;"><button onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="btn btn-danger">Close</button></div>
            <textarea class="code-text" id="codeOutput" spellcheck="false"></textarea>
        </div>
    </div>

    <script>
        function render() {
            const label = document.getElementById('optLabel').value;
            const val = document.getElementById('optValue').value;
            const icon = document.getElementById('optIcon').value;
            const trend = document.getElementById('optTrend').value;
            const trendType = document.getElementById('optTrendType').value;
            const color = document.getElementById('optColor').value;

            // Map colors
            const colors = {
                blue: { bg: '#eff6ff', text: '#3b82f6' },
                green: { bg: '#f0fdf4', text: '#22c55e' },
                purple: { bg: '#f5f3ff', text: '#8b5cf6' },
                orange: { bg: '#fffbeb', text: '#f59e0b' }
            };
            const c = colors[color];

            // Trend Icon
            let trendIcon = trendType === 'up' ? 'ri-arrow-up-line' : (trendType === 'down' ? 'ri-arrow-down-line' : 'ri-subtract-line');

            const html = `
<div class="stat-card">
    <div class="stat-header">
        <div>
            <div class="stat-label">${label}</div>
            <div class="stat-value">${val}</div>
        </div>
        <div class="stat-icon" style="background:${c.bg}; color:${c.text};">
            <i class="${icon}"></i>
        </div>
    </div>
    <div class="stat-footer trend-${trendType}">
        <i class="${trendIcon}"></i>
        <span>${trend}</span>
    </div>
</div>`;

            document.getElementById('renderArea').innerHTML = html;
            
            // Add CSS to code output for easy copy
            const css = `
<style>
.stat-card { background:#fff; border-radius:12px; padding:24px; border:1px solid #e2e8f0; display:flex; flex-direction:column; gap:10px; }
.stat-header { display:flex; justify-content:space-between; align-items:flex-start; }
.stat-label { font-size:13px; font-weight:600; color:#64748b; text-transform:uppercase; }
.stat-value { font-size:28px; font-weight:700; color:#1e293b; }
.stat-icon { width:40px; height:40px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:20px; }
.stat-footer { display:flex; align-items:center; gap:6px; font-size:13px; font-weight:500; }
.trend-up { color:#10b981; } .trend-down { color:#ef4444; }
</style>
`;
            document.getElementById('codeOutput').value = css + html;
        }
        render();
    </script>
</body>
</html>