<?php include 'viewer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Web Builder Dashboard</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

    <aside class="sidebar">
        <div class="brand">
            <i class="ri-code-box-line"></i> WebBuilder
        </div>
        <nav class="menu">
            <p class="text-sm uppercase tracking-wide text-secondary mb-2 mt-4 pl-2">Tools</p>
            <a href="#" class="active"><i class="ri-layout-grid-line mr-2"></i> Dashboard</a>
            <a href="#"><i class="ri-palette-line mr-2"></i> Theme Builder</a>
            
            <p class="text-sm uppercase tracking-wide text-secondary mb-2 mt-4 pl-2">Library</p>
            <a href="#nav"><i class="ri-navigation-line mr-2"></i> Navbar</a>
            <a href="#cards"><i class="ri-bank-card-line mr-2"></i> Cards</a>
            <a href="#forms"><i class="ri-input-method-line mr-2"></i> Forms</a>
        </nav>
        
        <div class="p-4 border-t border-gray-100">
            <label class="text-sm font-bold block mb-2">Theme Color</label>
            <input type="color" id="colorPicker" value="#3b82f6" class="w-full h-8 cursor-pointer rounded">
        </div>
    </aside>

    <main class="main-wrapper">
        
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-dark">Welcome Back</h1>
                <p class="text-secondary">Here are your tools for today.</p>
            </div>
            <button class="btn bg-dark text-white rounded px-4 py-2">
                <i class="ri-download-cloud-line"></i> Export Project
            </button>
        </header>

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="stat-card">
                <div>
                    <div class="stat-title">Components</div>
                    <div class="stat-value">24</div>
                </div>
                <div class="icon-box bg-blue-100 text-primary"><i class="ri-stack-line text-xl"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-title">Projects</div>
                    <div class="stat-value">3</div>
                </div>
                <div class="icon-box bg-green-100 text-success"><i class="ri-folder-line text-xl"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-title">Database</div>
                    <div class="stat-value">Connected</div>
                </div>
                <div class="icon-box bg-purple-100 text-secondary"><i class="ri-database-2-line text-xl"></i></div>
            </div>
        </div>

        <div class="flex flex-col gap-8">
            
            <div id="nav" class="card">
                <div class="card-header">Navigation Bar</div>
                <?php renderComponent("Standard Navbar", "components/navbar.php"); ?>
            </div>

            <div id="cards" class="card">
                <div class="card-header">Cards & Containers</div>
                <p class="text-secondary mb-4">Sample card layouts for your system.</p>
                <div class="p-4 border border-dashed rounded bg-light text-center text-secondary">
                    Add components/card-sample.php to see preview
                </div>
            </div>

        </div>
        <div id="forms" class="card mt-8"> <div class="card-header">
        <i class="ri-input-method-line mr-2"></i> Form Elements
    </div>
    <p class="text-secondary mb-4 text-sm">
        Standard inputs, selects, and grid layouts for data entry.
    </p>
    
    <?php renderComponent("General Form Layout", "components/forms.php"); ?>
</div>

    </main>

    <script>
        const colorPicker = document.getElementById('colorPicker');
        colorPicker.addEventListener('input', (e) => {
            document.documentElement.style.setProperty('--primary', e.target.value);
            // Optional: You can save this to localStorage so it remembers your choice
            localStorage.setItem('themeColor', e.target.value);
        });

        // Load saved color on refresh
        const savedColor = localStorage.getItem('themeColor');
        if(savedColor) {
            document.documentElement.style.setProperty('--primary', savedColor);
            colorPicker.value = savedColor;
        }

        // Copy Function
        function copyCode(btn) {
            const code = btn.parentElement.nextElementSibling.innerText;
            navigator.clipboard.writeText(code);
            const originalText = btn.innerText;
            btn.innerText = "Copied!";
            setTimeout(() => btn.innerText = originalText, 2000);
        }
    </script>
</body>
</html>