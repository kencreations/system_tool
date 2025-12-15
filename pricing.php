<?php $pageTitle = "Pricing Builder"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { overflow: hidden; height: 100vh; display: flex; }
        .workspace { flex-grow: 1; background: #fff; overflow-y: auto; padding: 40px; }
        
        /* PRICING CSS */
        .pricing-grid { display: flex; gap: 30px; justify-content: center; align-items: center; max-width: 1000px; margin: 0 auto; flex-wrap: wrap; }
        .pricing-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 30px;
            width: 300px; text-align: center; transition: all 0.3s; position: relative;
        }
        .pricing-card.popular {
            border-color: var(--primary); box-shadow: 0 10px 30px -10px rgba(59,130,246,0.3); transform: scale(1.05); z-index: 10;
        }
        .badge-popular {
            position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
            background: var(--primary); color: #fff; font-size: 11px; font-weight: 700;
            padding: 4px 12px; border-radius: 99px; text-transform: uppercase;
        }
        
        .plan-name { font-size: 16px; font-weight: 600; color: #64748b; margin-bottom: 10px; }
        .plan-price { font-size: 36px; font-weight: 800; color: #1e293b; margin-bottom: 5px; }
        .plan-period { font-size: 13px; color: #94a3b8; }
        
        .feature-list { list-style: none; padding: 0; margin: 30px 0; text-align: left; }
        .feature-list li { margin-bottom: 12px; font-size: 14px; color: #334155; display: flex; gap: 10px; }
        .feature-list i { color: #10b981; }
        
        .btn-plan { width: 100%; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer; border: 1px solid #e2e8f0; background: #fff; color: #334155; }
        .pricing-card.popular .btn-plan { background: var(--primary); color: #fff; border-color: var(--primary); }
    </style>
</head>
<body>

    <?php include 'components/sidebar.php'; ?>

    <main class="workspace">
        <div style="text-align:center; margin-bottom:40px;">
            <h2 style="font-size:28px; margin-bottom:10px;">Simple, Transparent Pricing</h2>
            <p style="color:#64748b;">Choose the plan that fits your needs.</p>
        </div>
        
        <div class="pricing-grid">
            
            <div class="pricing-card">
                <div class="plan-name">Basic</div>
                <div class="plan-price">$0</div>
                <div class="plan-period">Free Forever</div>
                <ul class="feature-list">
                    <li><i class="ri-checkbox-circle-fill"></i> 1 User Account</li>
                    <li><i class="ri-checkbox-circle-fill"></i> 5 Projects</li>
                    <li><i class="ri-checkbox-circle-fill"></i> Community Support</li>
                </ul>
                <button class="btn-plan">Get Started</button>
            </div>

            <div class="pricing-card popular">
                <div class="badge-popular">Most Popular</div>
                <div class="plan-name">Pro</div>
                <div class="plan-price">$29</div>
                <div class="plan-period">per month</div>
                <ul class="feature-list">
                    <li><i class="ri-checkbox-circle-fill"></i> 5 User Accounts</li>
                    <li><i class="ri-checkbox-circle-fill"></i> Unlimited Projects</li>
                    <li><i class="ri-checkbox-circle-fill"></i> Priority Support</li>
                    <li><i class="ri-checkbox-circle-fill"></i> Analytics</li>
                </ul>
                <button class="btn-plan">Try Free</button>
            </div>

            <div class="pricing-card">
                <div class="plan-name">Enterprise</div>
                <div class="plan-price">$99</div>
                <div class="plan-period">per month</div>
                <ul class="feature-list">
                    <li><i class="ri-checkbox-circle-fill"></i> Unlimited Users</li>
                    <li><i class="ri-checkbox-circle-fill"></i> Custom Integrations</li>
                    <li><i class="ri-checkbox-circle-fill"></i> 24/7 Dedicated Support</li>
                    <li><i class="ri-checkbox-circle-fill"></i> SSO & Security</li>
                </ul>
                <button class="btn-plan">Contact Sales</button>
            </div>

        </div>
    </main>
</body>
</html>