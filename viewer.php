<?php
function renderComponent($title, $filePath) {
    // 1. Check if file exists
    if (!file_exists($filePath)) {
        echo "<div class='card'>Error: Component not found.</div>";
        return;
    }

    // 2. Read the raw code
    $rawCode = file_get_contents($filePath);
    ?>

    <div class="component-wrapper">
        <h3><?php echo $title; ?></h3>
        
        <div class="card preview-box">
            <span class="label">Preview</span>
            <div class="sandbox">
                <?php include $filePath; ?> 
            </div>
        </div>

        <div class="card code-box">
            <div class="flex justify-between">
                <span class="label">Source Code</span>
                <button class="btn" onclick="copyCode(this)">Copy</button>
            </div>
            <pre><code><?php echo htmlspecialchars($rawCode); ?></code></pre>
        </div>
    </div>

    <style>
        .component-wrapper { margin-bottom: 50px; border-bottom: 1px solid #ccc; padding-bottom: 20px; }
        .label { font-size: 12px; text-transform: uppercase; color: #888; display: block; margin-bottom: 5px; }
        .sandbox { border: 2px dashed #e2e8f0; padding: 20px; }
        pre { background: #1e1e1e; color: #fff; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
    <?php
}
?>