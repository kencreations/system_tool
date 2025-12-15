<?php $pageTitle = "Advanced Forms"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { display: flex; height: 100vh; overflow: hidden; }
        .workspace { flex: 1; background: #f8fafc; overflow-y: auto; padding: 40px; }
        .card { background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px; max-width: 800px; margin-left: auto; margin-right: auto; }
        h3 { margin-top: 0; margin-bottom: 20px; font-size: 1.1rem; color: #1e293b; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
        
        .code-block { background: #1e1e1e; color: #ccc; padding: 15px; border-radius: 6px; font-family: monospace; font-size: 12px; overflow-x: auto; margin-top: 20px; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        
        <div class="card">
            <h3>Toggle Switches</h3>
            <div style="display:flex; gap: 20px;">
                <label class="form-switch">
                    <input type="checkbox" checked>
                    <span class="switch-track"><span class="switch-thumb"></span></span>
                    <span class="switch-label">Notifications</span>
                </label>
                
                <label class="form-switch">
                    <input type="checkbox">
                    <span class="switch-track"><span class="switch-thumb"></span></span>
                    <span class="switch-label">Dark Mode</span>
                </label>
            </div>
            <div class="code-block">
&lt;label class="form-switch"&gt;
    &lt;input type="checkbox" checked&gt;
    &lt;span class="switch-track"&gt;&lt;span class="switch-thumb"&gt;&lt;/span&gt;&lt;/span&gt;
    &lt;span class="switch-label"&gt;Label&lt;/span&gt;
&lt;/label&gt;</div>
        </div>

        <div class="card">
            <h3>Range Slider</h3>
            <input type="range" class="form-range" min="0" max="100" value="75">
            <div class="code-block">&lt;input type="range" class="form-range" min="0" max="100" value="50"&gt;</div>
        </div>

        <div class="card">
            <h3>File Upload Zone</h3>
            <div class="upload-zone">
                <i class="ri-upload-cloud-2-line"></i>
                <span>Drag and drop files here, or <span class="upload-btn">browse</span></span>
                <input type="file" style="display:none">
            </div>
            <div class="code-block">
&lt;div class="upload-zone"&gt;
    &lt;i class="ri-upload-cloud-2-line"&gt;&lt;/i&gt;
    &lt;span&gt;Drag files here or &lt;span class="upload-btn"&gt;browse&lt;/span&gt;&lt;/span&gt;
&lt;/div&gt;</div>
        </div>

    </main>
</body>
</html>