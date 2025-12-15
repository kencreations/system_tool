<?php $pageTitle = "Navigation Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        /* PAGE LAYOUT */
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #e2e8f0; display: flex; flex-direction: column; position: relative; min-width: 0; }
        .workspace-header { height: 60px; background: #fff; border-bottom: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; flex-shrink: 0; }
        
        /* PREVIEW AREA */
        .preview-scroll-area { flex-grow: 1; overflow: hidden; padding: 40px; display: flex; align-items: center; justify-content: center; }
        
        .browser-mockup {
            width: 90%; height: 90%; background: #fff; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); 
            border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;
            border: 1px solid #cbd5e1;
        }

        .browser-header {
            height: 30px; background: #f1f5f9; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; padding-left: 10px; gap: 6px; flex-shrink: 0;
        }
        .browser-dot { width: 10px; height: 10px; border-radius: 50%; }
        .bg-red { background: #ef4444; } .bg-yellow { background: #f59e0b; } .bg-green { background: #10b981; }

        /* THE ACTUAL LAYOUT PREVIEW */
        .layout-preview { flex-grow: 1; height: 100%; overflow: hidden; position: relative; }
        
        /* Sidebar Preview Styles */
        .preview-sidebar { transition: width 0.3s; padding: 15px; border-right: 1px solid rgba(0,0,0,0.05); }
        
        /* Preview Component Classes */
        .p-nav-category {
            font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
            margin-top: 20px; margin-bottom: 10px; padding-left: 10px; opacity: 0.6;
        }
        
        .p-nav-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 12px; margin-bottom: 4px; border-radius: 6px; font-size: 14px; 
            cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .p-nav-item:hover { background: rgba(255,255,255,0.05); }
        
        .p-submenu { margin-left: 20px; padding-left: 10px; border-left: 1px solid rgba(255,255,255,0.1); display: none; }
        .p-submenu.open { display: block; }
        .p-sub-item {
            display: block; padding: 8px 10px; font-size: 13px; color: inherit; opacity: 0.8; text-decoration: none;
        }
        .p-sub-item:hover { opacity: 1; }

        /* Navbar Specific Preview Styles */
        .p-search-box {
            display: flex; align-items: center; background: #f1f5f9; 
            border-radius: 6px; padding: 6px 12px; width: 250px;
        }
        .p-badge {
            position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; 
            background: #ef4444; border-radius: 50%; border: 1px solid #fff;
        }
        
        /* User Profile Dropdown Styles */
        .p-profile-wrapper { position: relative; border-left: 1px solid #e2e8f0; padding-left: 15px; margin-left: 10px; }
        .p-profile-trigger {
            display: flex; align-items: center; gap: 10px; cursor: pointer;
        }
        .p-avatar {
            width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; 
            display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b; font-size: 12px;
        }
        .p-profile-dropdown {
            position: absolute; top: 100%; right: 0; width: 180px; 
            background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            border-radius: 8px; padding: 6px 0; display: none; z-index: 20; margin-top: 10px;
        }
        .p-profile-wrapper:hover .p-profile-dropdown { display: block; }
        
        .p-profile-item {
            display: flex; align-items: center; gap: 10px; padding: 8px 16px; 
            font-size: 13px; color: #334155; text-decoration: none; transition: background 0.2s;
        }
        .p-profile-item:hover { background: #f8fafc; color: var(--primary); }

        /* RIGHT PANEL */
        .properties-panel { width: 360px; min-width: 360px; background: #fff; border-left: 1px solid #cbd5e1; display: flex; flex-direction: column; z-index: 40; flex-shrink: 0; }
        .tabs { display: flex; border-bottom: 1px solid #e2e8f0; }
        .tab { flex: 1; padding: 12px; text-align: center; cursor: pointer; font-size: 13px; font-weight: 600; color: #64748b; background: #f8fafc; }
        .tab.active { background: #fff; color: var(--primary); border-bottom: 2px solid var(--primary); }

        .panel-content { flex-grow: 1; overflow-y: auto; padding: 20px; }
        .section-title { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; margin-top: 20px; display: flex; justify-content: space-between; align-items: center; }
        
        /* Field Item Styling */
        .nav-field-item {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 10px;
        }
        .nav-field-item.type-category { border-left: 3px solid #f59e0b; background: #fffbeb; }
        .nav-field-item.type-dropdown { border-left: 3px solid #8b5cf6; background: #f5f3ff; }
        .nav-field-item.type-footer { border-left: 3px solid #ef4444; background: #fef2f2; }
        
        .field-row { display: flex; gap: 8px; margin-bottom: 8px; align-items: center; }
        .form-input { width: 100%; padding: 6px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 4px; }
        
        /* Toggle Switch Styling */
        .toggle-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; padding: 8px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0; }
        .toggle-label { font-size: 13px; font-weight: 500; color: #334155; display: flex; align-items: center; gap: 8px; }
        input[type="checkbox"] { transform: scale(1.2); cursor: pointer; }

        /* Color Input Fix */
        .color-wrapper {
            display: flex; align-items: center; gap: 10px; 
            border: 1px solid #e2e8f0; padding: 5px 10px; border-radius: 6px;
        }
        input[type="color"] { border: none; width: 24px; height: 24px; cursor: pointer; padding: 0; background: none; }
        
        /* Code Modal */
        .code-modal { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; }
        .code-window { width: 80%; height: 80%; background: #1e1e1e; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; }
        .code-content { flex-grow: 1; padding: 20px; color: #d4d4d4; overflow: auto; font-family: 'Consolas', monospace; }
        .window-header { background: #2d2d2d; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <header class="workspace-header">
            <h3 style="margin:0; font-size:16px; font-weight: 600; color: #1e293b;">Navigation Builder</h3>
            <div class="flex items-center gap-2">
                <button onclick="toggleCode(true)" class="btn btn-outline" style="font-size: 0.85rem;">View Code</button>
                <button class="btn btn-primary" style="font-size: 0.85rem;">Save Layout</button>
            </div>
        </header>

        <div class="preview-scroll-area">
            <div class="browser-mockup">
                <div class="browser-header">
                    <div class="browser-dot bg-red"></div>
                    <div class="browser-dot bg-yellow"></div>
                    <div class="browser-dot bg-green"></div>
                </div>
                <div id="layoutRenderArea" class="layout-preview"></div>
            </div>
        </div>
        
        <div id="codeModal" class="code-modal">
            <div class="code-window">
                <div class="window-header">
                    <span style="color:#9ca3af; font-size: 13px;">layout.html</span>
                    <button onclick="toggleCode(false)" class="btn btn-danger" style="padding:4px 12px; font-size:12px;">Close</button>
                </div>
                <pre class="code-content"><code id="codeOutput"></code></pre>
            </div>
        </div>
    </main>

    <aside class="properties-panel">
        <div class="tabs">
            <div class="tab active" onclick="switchTab('sidebar')">SIDEBAR</div>
            <div class="tab" onclick="switchTab('navbar')">NAVBAR</div>
        </div>

        <div id="panel-sidebar" class="panel-content">
            <div class="section-title" style="margin-top:0;">Colors & Brand</div>
            <div class="field-row"><input type="text" id="brandName" class="form-input" value="DevStudio" placeholder="Brand Name" oninput="updateSettings()"></div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                <div class="color-wrapper" title="Sidebar Background"><input type="color" id="sbBg" value="#1e293b" oninput="updateSettings()"> <span style="font-size:11px; color:#64748b;">Bg</span></div>
                <div class="color-wrapper" title="Text Color"><input type="color" id="sbText" value="#cbd5e1" oninput="updateSettings()"> <span style="font-size:11px; color:#64748b;">Text</span></div>
                <div class="color-wrapper" title="Active Item Background"><input type="color" id="sbActiveBg" value="#3b82f6" oninput="updateSettings()"> <span style="font-size:11px; color:#64748b;">Active Bg</span></div>
                <div class="color-wrapper" title="Active Item Text"><input type="color" id="sbActiveText" value="#ffffff" oninput="updateSettings()"> <span style="font-size:11px; color:#64748b;">Active Txt</span></div>
            </div>

            <div class="section-title">
                <span>Menu Items</span>
                <div style="display:flex; gap:5px;">
                    <button onclick="addItem('link')" style="background:#eff6ff; border:1px solid #bfdbfe; color:var(--primary); cursor:pointer; font-size:10px; padding:2px 6px; border-radius:4px;">+ LINK</button>
                    <button onclick="addItem('dropdown')" style="background:#f5f3ff; border:1px solid #ddd6fe; color:#7c3aed; cursor:pointer; font-size:10px; padding:2px 6px; border-radius:4px;">+ SUB</button>
                    <button onclick="addItem('category')" style="background:#fffbeb; border:1px solid #fde68a; color:#d97706; cursor:pointer; font-size:10px; padding:2px 6px; border-radius:4px;">+ CAT</button>
                </div>
            </div>
            <div id="sidebarList"></div>

            <div class="section-title" style="margin-top: 25px; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                <span>Footer / Bottom Items</span>
                <button onclick="addFooterItem()" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; cursor:pointer; font-size:10px; padding:2px 6px; border-radius:4px;">+ BTN</button>
            </div>
            <div id="footerList"></div>
        </div>

        <div id="panel-navbar" class="panel-content" style="display:none;">
            <div class="section-title" style="margin-top:0;">Appearance</div>
            <div class="color-wrapper" style="margin-bottom:10px;">
                <input type="color" id="navBg" value="#ffffff" oninput="updateSettings()">
                <span style="font-size:12px; color:#333;">Navbar Background</span>
            </div>

            <div class="section-title">Features</div>
            <div class="toggle-row">
                <span class="toggle-label"><i class="ri-search-line"></i> Global Search</span>
                <input type="checkbox" id="navSearch" onchange="updateSettings()" checked>
            </div>
            <div class="toggle-row">
                <span class="toggle-label"><i class="ri-notification-badge-line"></i> Notifications</span>
                <input type="checkbox" id="navNotify" onchange="updateSettings()" checked>
            </div>
            
            <div class="section-title" style="margin-top: 20px; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                <span>User Profile Widget</span>
                <div class="toggle-row" style="margin-top:5px;">
                    <span class="toggle-label">Enable Profile</span>
                    <input type="checkbox" id="navProfile" onchange="updateSettings()" checked>
                </div>
            </div>
            
            <div id="userProfileConfig" style="background:#f8fafc; padding:10px; border-radius:6px; border:1px solid #e2e8f0; margin-bottom:20px;">
                <div class="field-row">
                    <input type="text" id="userName" class="form-input" value="Admin User" oninput="updateSettings()" placeholder="Display Name">
                    <input type="text" id="userInitials" class="form-input" style="width:50px;" value="AD" oninput="updateSettings()" placeholder="Initials">
                </div>
                
                <div class="section-title" style="margin-top:10px; font-size:10px; display:flex; justify-content:space-between;">
                    <span>DROPDOWN MENU</span>
                    <button onclick="addUserLink()" style="font-size:9px; background:var(--primary); color:#fff; border:none; padding:2px 6px; border-radius:3px; cursor:pointer;">+ ADD</button>
                </div>
                <div id="userMenuList"></div>
            </div>

            <div class="section-title">Custom Links</div>
            <button onclick="addNavLink()" class="btn btn-outline w-100" style="font-size:12px;">+ Add Navbar Link</button>
            <div id="navbarList" style="margin-top:10px;"></div>
        </div>
    </aside>
<script>
    // --- 1. STATE ---
    let config = {
        brand: 'DevStudio',
        sidebar: {
            bg: '#1e293b', text: '#cbd5e1', activeBg: '#3b82f6', activeText: '#ffffff',
            items: [
                { type: 'category', label: 'Core' },
                { type: 'link', label: 'Dashboard', icon: 'ri-home-line', href: 'index.php', active: true },
                { type: 'dropdown', label: 'Reports', icon: 'ri-file-chart-line', href: '#', children: [
                    { label: 'Sales', href: 'sales.php' }, { label: 'Traffic', href: 'traffic.php' }
                ]},
                { type: 'category', label: 'Settings' },
                { type: 'link', label: 'Users', icon: 'ri-user-line', href: 'users.php', active: false }
            ],
            footerItems: [
                { label: 'Logout', icon: 'ri-logout-box-line', href: 'logout.php' }
            ]
        },
        navbar: {
            bg: '#ffffff', text: '#334155',
            showSearch: true, showNotify: true, showProfile: true,
            userProfile: {
                name: 'Admin User', initials: 'AD',
                menu: [
                    { label: 'My Profile', icon: 'ri-user-line', href: '#' },
                    { label: 'Account', icon: 'ri-settings-3-line', href: '#' },
                    { label: 'Logout', icon: 'ri-logout-box-line', href: '#' }
                ]
            },
            links: []
        }
    };

    // --- 2. HELPERS ---
    function switchTab(tab) {
        document.getElementById('panel-sidebar').style.display = tab === 'sidebar' ? 'block' : 'none';
        document.getElementById('panel-navbar').style.display = tab === 'navbar' ? 'block' : 'none';
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        event.target.classList.add('active');
    }

    function updateSettings() {
        // Sidebar
        config.brand = document.getElementById('brandName').value;
        config.sidebar.bg = document.getElementById('sbBg').value;
        config.sidebar.text = document.getElementById('sbText').value;
        config.sidebar.activeBg = document.getElementById('sbActiveBg').value;
        config.sidebar.activeText = document.getElementById('sbActiveText').value;
        
        // Navbar
        config.navbar.bg = document.getElementById('navBg').value;
        config.navbar.showSearch = document.getElementById('navSearch').checked;
        config.navbar.showNotify = document.getElementById('navNotify').checked;
        config.navbar.showProfile = document.getElementById('navProfile').checked;
        
        // User Profile
        config.navbar.userProfile.name = document.getElementById('userName').value;
        config.navbar.userProfile.initials = document.getElementById('userInitials').value;
        document.getElementById('userProfileConfig').style.display = config.navbar.showProfile ? 'block' : 'none';
        
        renderLayout();
    }

    // --- 3. RENDER CONTROLS (Left as is for brevity, assume renderControls logic exists) ---
    function renderControls() {
        const list = document.getElementById('sidebarList');
        list.innerHTML = '';
        
        config.sidebar.items.forEach((item, i) => {
            let div = document.createElement('div');
            div.className = `nav-field-item type-${item.type}`;
            
            let header = `
                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                    <span style="font-size:10px; font-weight:bold; color:#64748b; text-transform:uppercase;">${item.type}</span>
                    <div>
                         <i class="ri-arrow-up-s-line" style="cursor:pointer; color:#94a3b8; margin-right:5px;" onclick="moveItem(${i}, -1)"></i>
                         <i class="ri-arrow-down-s-line" style="cursor:pointer; color:#94a3b8; margin-right:5px;" onclick="moveItem(${i}, 1)"></i>
                         <i class="ri-delete-bin-line" style="cursor:pointer; color:#ef4444;" onclick="removeItem(${i})"></i>
                    </div>
                </div>
            `;

            let body = '';
            if (item.type === 'category') body = `<input type="text" class="form-input" value="${item.label}" oninput="updateItem(${i}, 'label', this.value)" placeholder="Category Name">`;
            else if (item.type === 'link') body = `<div class="field-row"><input type="text" class="form-input" style="width:30%" value="${item.icon}" oninput="updateItem(${i}, 'icon', this.value)" placeholder="Icon"><input type="text" class="form-input" value="${item.label}" oninput="updateItem(${i}, 'label', this.value)" placeholder="Label"></div><input type="text" class="form-input" value="${item.href}" oninput="updateItem(${i}, 'href', this.value)" placeholder="URL"> <label style="font-size:11px; color:#64748b; margin-top:5px; display:block;"><input type="radio" name="activeItem" ${item.active ? 'checked' : ''} onchange="setActive(${i})"> Active</label>`;
            else if (item.type === 'dropdown') {
                let subHtml = item.children.map((child, ci) => `<div style="display:flex; gap:5px; margin-top:5px;"><input type="text" class="form-input" value="${child.label}" oninput="updateSubItem(${i}, ${ci}, 'label', this.value)"><input type="text" class="form-input" value="${child.href}" oninput="updateSubItem(${i}, ${ci}, 'href', this.value)"><i class="ri-close-circle-fill" style="color:#cbd5e1; cursor:pointer;" onclick="removeSubItem(${i}, ${ci})"></i></div>`).join('');
                body = `<div class="field-row"><input type="text" class="form-input" style="width:30%" value="${item.icon}" oninput="updateItem(${i}, 'icon', this.value)"><input type="text" class="form-input" value="${item.label}" oninput="updateItem(${i}, 'label', this.value)"></div><div style="background:#f8fafc; padding:5px;">${subHtml}<button onclick="addSubItem(${i})" style="font-size:9px; margin-top:5px;">+ Sub</button></div>`;
            }
            div.innerHTML = header + body;
            list.appendChild(div);
        });

        const footerList = document.getElementById('footerList');
        footerList.innerHTML = '';
        config.sidebar.footerItems.forEach((item, i) => {
            let div = document.createElement('div');
            div.className = `nav-field-item type-footer`;
            div.innerHTML = `<div style="display:flex; justify-content:space-between; margin-bottom:5px;"><span style="font-size:10px; font-weight:bold; color:#ef4444; text-transform:uppercase;">Bottom Btn</span><i class="ri-delete-bin-line" style="cursor:pointer; color:#ef4444;" onclick="removeFooterItem(${i})"></i></div><div class="field-row"><input type="text" class="form-input" style="width:30%" value="${item.icon}" oninput="updateFooterItem(${i}, 'icon', this.value)"><input type="text" class="form-input" value="${item.label}" oninput="updateFooterItem(${i}, 'label', this.value)"></div><input type="text" class="form-input" value="${item.href}" oninput="updateFooterItem(${i}, 'href', this.value)" placeholder="URL">`;
            footerList.appendChild(div);
        });
        
        const userList = document.getElementById('userMenuList');
        userList.innerHTML = '';
        config.navbar.userProfile.menu.forEach((link, i) => {
            let div = document.createElement('div');
            div.style.marginBottom = '5px';
            div.innerHTML = `<div class="field-row"><input type="text" class="form-input" style="width:25%" value="${link.icon}" oninput="updateUserLink(${i}, 'icon', this.value)"><input type="text" class="form-input" value="${link.label}" oninput="updateUserLink(${i}, 'label', this.value)"><i class="ri-delete-bin-line" style="color:#ef4444; cursor:pointer;" onclick="removeUserLink(${i})"></i></div><input type="text" class="form-input" value="${link.href}" oninput="updateUserLink(${i}, 'href', this.value)" placeholder="URL">`;
            userList.appendChild(div);
        });
        
        const navList = document.getElementById('navbarList');
        navList.innerHTML = '';
        config.navbar.links.forEach((link, i) => {
             let div = document.createElement('div');
             div.className = 'field-row';
             div.innerHTML = `<div style="flex-grow:1"><div style="display:flex; gap:5px; margin-bottom:5px;"><input type="text" class="form-input" style="width:30%" value="${link.icon}" oninput="updateNavLink(${i}, 'icon', this.value)" placeholder="Icon"><input type="text" class="form-input" value="${link.label}" oninput="updateNavLink(${i}, 'label', this.value)" placeholder="Label"></div><input type="text" class="form-input" value="${link.href}" oninput="updateNavLink(${i}, 'href', this.value)" placeholder="URL"></div><i class="ri-delete-bin-line" style="color:#ef4444; cursor:pointer;" onclick="removeNavLink(${i})"></i>`;
             navList.appendChild(div);
        });
    }

    // --- 4. RENDER PREVIEW (HTML GENERATION) ---
    function renderLayout() {
        // --- VISUAL PREVIEW (Uses 'p-' classes for builder display) ---
        const area = document.getElementById('layoutRenderArea');
        let sbHtml = '';
        config.sidebar.items.forEach(item => {
            if(item.type === 'category') sbHtml += `<div class="p-nav-category" style="color:${config.sidebar.text};">${item.label}</div>`;
            else if(item.type === 'link') {
                let active = item.active ? `background:${config.sidebar.activeBg}; color:${config.sidebar.activeText};` : `color:${config.sidebar.text};`;
                sbHtml += `<a href="${item.href}" class="p-nav-item" style="${active}"><div style="display:flex; align-items:center; gap:10px;"><i class="${item.icon}" style="font-size:18px;"></i> ${item.label}</div></a>`;
            }
            else if(item.type === 'dropdown') {
                let sub = item.children.map(c => `<a href="${c.href}" class="p-sub-item" style="color:${config.sidebar.text};">${c.label}</a>`).join('');
                sbHtml += `<div class="p-nav-item" style="color:${config.sidebar.text};" onclick="this.nextElementSibling.classList.toggle('open')"><div style="display:flex; align-items:center; gap:10px;"><i class="${item.icon}" style="font-size:18px;"></i> ${item.label}</div><i class="ri-arrow-down-s-line"></i></div><div class="p-submenu open">${sub}</div>`;
            }
        });
        
        let footerHtml = '';
        if(config.sidebar.footerItems.length > 0) {
            footerHtml = `<div style="margin-top:auto; padding-top:20px; border-top:1px solid rgba(255,255,255,0.05);">`;
            config.sidebar.footerItems.forEach(item => { footerHtml += `<a href="${item.href}" class="p-nav-item" style="color:${config.sidebar.text};"><div style="display:flex; align-items:center; gap:10px;"><i class="${item.icon}" style="font-size:18px;"></i> ${item.label}</div></a>`; });
            footerHtml += `</div>`;
        }

        let navRight = '';
        if(config.navbar.showNotify) navRight += `<div style="position:relative; margin-right:15px;"><i class="ri-notification-3-line" style="font-size:20px; color:#64748b;"></i><div class="p-badge"></div></div>`;
        if(config.navbar.showProfile) {
            let uMenu = config.navbar.userProfile.menu.map(l => `<a href="${l.href}" class="p-profile-item"><i class="${l.icon}"></i> ${l.label}</a>`).join('');
            navRight += `<div class="p-profile-wrapper"><div class="p-profile-trigger"><div class="p-avatar">${config.navbar.userProfile.initials}</div><div style="font-size:13px; font-weight:600; color:#334155;">${config.navbar.userProfile.name}</div><i class="ri-arrow-down-s-line" style="color:#94a3b8;"></i></div><div class="p-profile-dropdown">${uMenu}</div></div>`;
        }

        let previewHtml = `
            <div style="display:flex; height:100%; overflow:hidden;">
                <aside style="width:240px; background:${config.sidebar.bg}; padding:20px; display:flex; flex-direction:column;">
                    <div style="font-size:20px; font-weight:bold; color:#fff; margin-bottom:30px; display:flex; align-items:center; gap:10px;"><i class="ri-command-line"></i> ${config.brand}</div>
                    <nav style="flex:1; display:flex; flex-direction:column;">${sbHtml}${footerHtml}</nav>
                </aside>
                <div style="flex:1; background:#f8fafc; display:flex; flex-direction:column;">
                    <header style="height:60px; background:${config.navbar.bg}; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; padding:0 30px;">
                        <div><div style="font-weight:600; color:#334155;">Page Title</div></div>
                        <div style="display:flex; align-items:center;">${navRight}</div>
                    </header>
                    <main style="padding:30px; flex:1;"><div style="border:2px dashed #e2e8f0; height:100%; display:flex; align-items:center; justify-content:center; color:#94a3b8;">Page Content...</div></main>
                </div>
            </div>`;
        area.innerHTML = previewHtml;

        // --- PRODUCTION CODE GENERATION (Uses real .app- classes) ---
        let srcSb = '';
        config.sidebar.items.forEach(item => {
            if(item.type === 'category') srcSb += `\n            <div class="nav-category" style="color:${config.sidebar.text};">${item.label}</div>`;
            else if(item.type === 'link') {
                let activeClass = item.active ? 'active' : '';
                // Note: We keep inline styles for dynamic colors chosen by the user
                let activeStyle = item.active ? `background:${config.sidebar.activeBg}; color:${config.sidebar.activeText};` : `color:${config.sidebar.text};`;
                srcSb += `\n            <a href="${item.href}" class="nav-link ${activeClass}" style="${activeStyle}">\n                <i class="${item.icon}"></i> ${item.label}\n            </a>`;
            }
            else if(item.type === 'dropdown') {
                let subs = item.children.map(c => `\n                <a href="${c.href}" class="nav-link" style="color:${config.sidebar.text};">${c.label}</a>`).join('');
                srcSb += `\n            <div class="nav-link nav-dropdown-trigger" style="color:${config.sidebar.text};" onclick="this.nextElementSibling.classList.toggle('open')">\n                <div style="display:flex; gap:10px; align-items:center;"><i class="${item.icon}"></i> ${item.label}</div>\n                <i class="ri-arrow-down-s-line"></i>\n            </div>\n            <div class="nav-submenu">${subs}\n            </div>`;
            }
        });

        let srcFooter = '';
        if(config.sidebar.footerItems.length > 0) {
            config.sidebar.footerItems.forEach(item => {
                srcFooter += `\n            <a href="${item.href}" class="nav-link" style="color:${config.sidebar.text};">\n                <i class="${item.icon}"></i> ${item.label}\n            </a>`;
            });
        }

        // NAVBAR PRODUCTION HTML
        let srcNavRight = '';
        
        if(config.navbar.showSearch) {
            srcNavRight += `
            <div class="header-search">
                <i class="ri-search-line"></i>
                <input type="text" placeholder="Search...">
            </div>`;
        }
        
        if(config.navbar.showNotify) {
            srcNavRight += `
            <div class="notification-btn">
                <i class="ri-notification-3-line"></i>
                <span class="badge-dot"></span>
            </div>`;
        }
        
        if(config.navbar.showProfile) {
             let uMenu = config.navbar.userProfile.menu.map(l => `\n                    <a href="${l.href}" class="dropdown-item"><i class="${l.icon}"></i> ${l.label}</a>`).join('');
             srcNavRight += `
            <div class="user-dropdown">
                <div class="user-trigger">
                    <div class="user-avatar">${config.navbar.userProfile.initials}</div>
                    <span class="user-name">${config.navbar.userProfile.name}</span>
                    <i class="ri-arrow-down-s-line"></i>
                </div>
                <div class="dropdown-menu">${uMenu}
                </div>
            </div>`;
        }

        const sourceHtml = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${config.brand} Dashboard</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

<div class="app-shell">
    <aside class="app-sidebar" style="background:${config.sidebar.bg}; color:${config.sidebar.text};">
        <div class="sidebar-header" style="color:#fff;">
            <i class="ri-command-line"></i> ${config.brand}
        </div>
        
        <div class="sidebar-content">
            <nav>${srcSb}
            </nav>
        </div>
        
        <div class="sidebar-footer">${srcFooter}
        </div>
    </aside>

    <div class="app-main">
        <header class="app-header" style="background:${config.navbar.bg}; color:${config.navbar.text};">
            <div class="header-left">
                <h3 style="margin:0; font-size:1.25rem; font-weight:700;">Page Title</h3>
            </div>
            <div class="header-right">${srcNavRight}
            </div>
        </header>

        <main class="app-content">
            <div class="card">
                <h3>Welcome Back!</h3>
                <p>Start building your dashboard here.</p>
            </div>
        </main>
    </div>
</div>

</body>
</html>`;

        document.getElementById('codeOutput').textContent = sourceHtml;
    }

    // --- 5. LOGIC CONTROLLERS ---
    function addItem(type) { config.sidebar.items.push(type === 'dropdown' ? { type, label:'Menu', icon:'ri-folder-line', href:'#', children:[{label:'Sub Link', href:'#'}] } : type === 'category' ? { type, label:'Section' } : { type, label:'Link', icon:'ri-link', href:'#', active:false }); renderControls(); renderLayout(); }
    function removeItem(i) { config.sidebar.items.splice(i, 1); renderControls(); renderLayout(); }
    function moveItem(i, dir) { let n = i + dir; if(n >= 0 && n < config.sidebar.items.length) { [config.sidebar.items[i], config.sidebar.items[n]] = [config.sidebar.items[n], config.sidebar.items[i]]; renderControls(); renderLayout(); } }
    function updateItem(i, k, v) { config.sidebar.items[i][k] = v; renderLayout(); }
    function setActive(i) { config.sidebar.items.forEach(it => { if(it.type==='link') it.active = false; }); config.sidebar.items[i].active = true; renderLayout(); }
    
    function addSubItem(i) { config.sidebar.items[i].children.push({label:'New Sub', href:'#'}); renderControls(); renderLayout(); }
    function removeSubItem(i, ci) { config.sidebar.items[i].children.splice(ci, 1); renderControls(); renderLayout(); }
    function updateSubItem(i, ci, k, v) { config.sidebar.items[i].children[ci][k] = v; renderLayout(); }

    function addUserLink() { config.navbar.userProfile.menu.push({label:'New Link', icon:'ri-link', href:'#'}); renderControls(); renderLayout(); }
    function removeUserLink(i) { config.navbar.userProfile.menu.splice(i, 1); renderControls(); renderLayout(); }
    function updateUserLink(i, k, v) { config.navbar.userProfile.menu[i][k] = v; renderLayout(); }

    function addFooterItem() { config.sidebar.footerItems.push({label:'Link', icon:'ri-link', href: '#'}); renderControls(); renderLayout(); }
    function removeFooterItem(i) { config.sidebar.footerItems.splice(i, 1); renderControls(); renderLayout(); }
    function updateFooterItem(i, k, v) { config.sidebar.footerItems[i][k] = v; renderLayout(); }

    function addNavLink() { config.navbar.links.push({label:'Link', icon:'ri-link', href: '#'}); renderControls(); renderLayout(); }
    function removeNavLink(i) { config.navbar.links.splice(i, 1); renderControls(); renderLayout(); }
    function updateNavLink(i, k, v) { config.navbar.links[i][k] = v; renderLayout(); }

    function toggleCode(show) { document.getElementById('codeModal').style.display = show ? 'flex' : 'none'; }

    // Init
    renderControls(); renderLayout();
</script>
</body>
</html>