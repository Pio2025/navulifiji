<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Navuli Fiji - Subscription Invoice</title>
</head>
<body style="margin:0;padding:0;background-color:#f7f7f7;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f7f7f7;">
        <tr>
            <td align="center" style="padding:20px 0;">
                <!-- Main Container -->
                <table role="presentation" width="650" cellpadding="0" cellspacing="0" border="0" style="max-width:650px;background-color:#ffffff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding:40px 40px 30px 40px;background:linear-gradient(135deg,#2e3192 0%,#00aeef 100%);">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="60%" style="vertical-align:middle;">
                                        <img src="https://navulifiji.com/navuli_logo_white.png" alt="Navuli Fiji" width="180" style="max-width:180px;height:auto;display:block;">
                                    </td>
                                    <td width="40%" style="text-align:right;vertical-align:middle;">
                                        <div style="color:#ffffff;font-size:24px;font-weight:700;margin-bottom:5px;">INVOICE</div>
                                        <div style="color:#e0f2fe;font-size:14px;">Invoice #<?php echo time() ?? 'INV-' . date('Ymd') . '-' . str_pad($schoolId ?? '001', 4, '0', STR_PAD_LEFT); ?></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Invoice Details Header -->
                    <tr>
                        <td style="padding:30px 40px 20px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <!-- Bill To -->
                                    <td width="50%" style="vertical-align:top;">
                                        <div style="color:#6b7280;font-size:12px;font-weight:600;text-transform:uppercase;margin-bottom:10px;">Bill To:</div>
                                        <div style="color:#111827;font-size:16px;font-weight:600;margin-bottom:5px;"><?php echo htmlspecialchars($schoolName, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div style="color:#4b5563;font-size:14px;line-height:1.6;">
                                            <?php echo htmlspecialchars($schoolAddress ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?><br>
                                            <?php echo htmlspecialchars($email ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?><br>
                                            <?php echo htmlspecialchars($schoolPhone ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Invoice Info -->
                                    <td width="50%" style="vertical-align:top;text-align:right;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding:5px 0;color:#6b7280;font-size:13px;text-align:right;">Invoice Date:</td>
                                                <td style="padding:5px 0 5px 10px;color:#111827;font-size:13px;font-weight:600;text-align:right;"><?php echo date('F d, Y'); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;color:#6b7280;font-size:13px;text-align:right;">Start Date:</td>
                                                <td style="padding:5px 0 5px 10px;color:#111827;font-size:13px;font-weight:600;text-align:right;"><?php echo date('F d, Y', strtotime($subscriptionStart ?? 'now')); ?></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;color:#6b7280;font-size:13px;text-align:right;">End Date:</td>
                                                <td style="padding:5px 0 5px 10px;color:#111827;font-size:13px;font-weight:600;text-align:right;"><?php echo $end; ?></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px 0;color:#6b7280;font-size:13px;text-align:right;">Payment Method:</td>
                                                <td style="padding:5px 0 5px 10px;color:#111827;font-size:13px;font-weight:600;text-align:right;"><?php echo htmlspecialchars($paymentMode ?? 'Cash', ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Subscription Details Table -->
                    <tr>
                        <td style="padding:20px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
                                <!-- Table Header -->
                                <thead>
                                    <tr style="background-color:#f3f4f6;">
                                        <th style="padding:15px;text-align:left;color:#374151;font-size:13px;font-weight:600;border-bottom:2px solid #e5e7eb;">Description</th>
                                        <th style="padding:15px;text-align:center;color:#374151;font-size:13px;font-weight:600;border-bottom:2px solid #e5e7eb;width:100px;">Term</th>
                                        <th style="padding:15px;text-align:right;color:#374151;font-size:13px;font-weight:600;border-bottom:2px solid #e5e7eb;width:120px;">Amount</th>
                                    </tr>
                                </thead>
                                
                                <!-- Table Body -->
                                <tbody>
                                    <tr>
                                        <td style="padding:20px 15px;border-bottom:1px solid #e5e7eb;">
                                            <div style="color:#111827;font-size:15px;font-weight:600;margin-bottom:5px;">
                                                <?php echo htmlspecialchars($planName ?? 'Navuli Standard Plan', ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                            <div style="color:#6b7280;font-size:13px;line-height:1.6;">
                                                <?php echo htmlspecialchars($planDescription ?? 'Complete school management system with all features included', ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                        </td>
                                        <td style="padding:20px 15px;border-bottom:1px solid #e5e7eb;text-align:center;color:#374151;font-size:14px;">
                                            <?php echo $subscriptionTerm ?? '12'; ?> month<?php echo ($subscriptionTerm ?? 12) > 1 ? 's' : ''; ?>
                                        </td>
                                        <td style="padding:20px 15px;border-bottom:1px solid #e5e7eb;text-align:right;color:#111827;font-size:15px;font-weight:600;">
                                            FJ$<?php echo number_format($planPrice ?? 0, 2); ?>
                                        </td>
                                    </tr>
                                    
                                    <!-- Subtotal -->
                                    <tr>
                                        <td colspan="2" style="padding:15px;text-align:right;color:#6b7280;font-size:14px;font-weight:600;">
                                            Subtotal:
                                        </td>
                                        <td style="padding:15px;text-align:right;color:#111827;font-size:15px;font-weight:600;">
                                            FJ$<?php echo number_format($planPrice ?? 0, 2); ?>
                                        </td>
                                    </tr>
                                    
                                    <!-- VAT (if applicable) -->
                                    <?php 
                                    $vatRate = 15; // Set to 0 if no VAT, or 15 for 15% VAT
                                    $vatAmount = ($planPrice ?? 0) * ($vatRate / 100);
                                    if ($vatRate > 0): 
                                    ?>
                                    <tr>
                                        <td colspan="2" style="padding:10px 15px;text-align:right;color:#6b7280;font-size:14px;">
                                            VAT (<?php echo $vatRate; ?>%):
                                        </td>
                                        <td style="padding:10px 15px;text-align:right;color:#111827;font-size:14px;">
                                            FJ$<?php echo number_format($vatAmount, 2); ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <!-- Total -->
                                    <tr style="background-color:#f8fafc;">
                                        <td colspan="2" style="padding:20px 15px;text-align:right;color:#111827;font-size:18px;font-weight:700;">
                                            Total Amount:
                                        </td>
                                        <td style="padding:20px 15px;text-align:right;color:#2e3192;font-size:20px;font-weight:700;">
                                            FJ$<?php echo number_format(($planPrice ?? 0) + $vatAmount, 2); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Payment Status Notice -->
                    <tr>
                        <td style="padding:30px 40px;">
                            <div style="background-color:#fef3c7;border-left:4px solid #f59e0b;padding:20px;border-radius:8px;">
                                <div style="color:#92400e;font-size:15px;font-weight:600;margin-bottom:10px;">
                                    ⚠️ Payment Verification Required
                                </div>
                                <div style="color:#78350f;font-size:14px;line-height:1.6;">
                                    Your Navuli Fiji account subscription will be activated once payment is verified. This typically takes 1-2 business days depending on your payment method.
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Payment Instructions -->
                    <tr>
                        <td style="padding:0 40px 30px 40px;">
                            <div style="background-color:#f8fafc;padding:25px;border-radius:8px;border:1px solid #e5e7eb;">
                                <div style="color:#111827;font-size:16px;font-weight:600;margin-bottom:15px;">
                                    📋 Payment Instructions
                                </div>
                                <div style="color:#4b5563;font-size:14px;line-height:1.8;">
                                    <?php 
                                    $payment = $paymentMode ?? 'Cash';
                                    if ($payment === 'Bank Transfer'): 
                                    ?>
                                        <strong>Bank Transfer Details:</strong><br>
                                        Bank Name: ANZ Fiji<br>
                                        Account Name: Navuli Fiji Ltd<br>
                                        Account Number: 1234567890<br>
                                        Reference: INV-<?php echo date('Ymd'); ?>-<?php echo str_pad($schoolId ?? '001', 4, '0', STR_PAD_LEFT); ?><br>
                                        <br>
                                        Please email proof of payment to: <a href="mailto:accounts@navulifiji.com" style="color:#00aeef;text-decoration:none;">accounts@navulifiji.com</a>
                                    <?php elseif ($payment === 'MPaisa' || $payment === 'MyCash'): ?>
                                        <strong><?php echo $payment; ?> Payment:</strong><br>
                                        Send payment to: 9896700<br>
                                        Reference: <?php echo htmlspecialchars($schoolName, ENT_QUOTES, 'UTF-8'); ?><br>
                                        <br>
                                        Please keep your transaction ID and email it to: <a href="mailto:accounts@navulifiji.com" style="color:#00aeef;text-decoration:none;">accounts@navulifiji.com</a>
                                    <?php elseif ($payment === 'Check' || $payment === 'Cheque'): ?>
                                        <strong>Cheque Payment:</strong><br>
                                        Make payable to: Navuli Fiji Ltd<br>
                                        Mail to: PO Box 123, Suva, Fiji<br>
                                        Or deliver to: 123 Victoria Parade, Suva<br>
                                        <br>
                                        Please write invoice number on the back of the cheque.
                                    <?php elseif ($payment === 'Master Card' || $payment === 'Credit Card'): ?>
                                        <strong>Credit Card Payment:</strong><br>
                                        Please contact our office to process your card payment securely.<br>
                                        Call: <a href="tel:+6799896700" style="color:#00aeef;text-decoration:none;">+679 9896700</a><br>
                                        Email: <a href="mailto:accounts@navulifiji.com" style="color:#00aeef;text-decoration:none;">accounts@navulifiji.com</a>
                                    <?php else: ?>
                                        <strong>Cash Payment:</strong><br>
                                        Please visit our office during business hours:<br>
                                        Address: 123 Victoria Parade, Suva, Fiji<br>
                                        Hours: Monday-Friday, 8:00 AM - 4:00 PM<br>
                                        <br>
                                        Request an official receipt upon payment.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Welcome Message -->
                    <tr>
                        <td style="padding:0 40px 40px 40px;">
                            <div style="background-color:#eff6ff;padding:30px;border-radius:8px;border-left:4px solid #00aeef;">
                                <h2 style="color:#1e40af;margin:0 0 15px 0;font-size:20px;">Welcome to Navuli Fiji! 🎉</h2>
                                <p style="color:#1e3a8a;font-size:15px;line-height:1.7;margin:0 0 15px 0;">
                                    Thank you for choosing Navuli Fiji as your school management partner. We're committed to providing you with exceptional tools and support to streamline your educational operations.
                                </p>
                                <p style="color:#1e3a8a;font-size:14px;line-height:1.7;margin:0;">
                                    Once your payment is verified, you'll receive:
                                </p>
                                <ul style="color:#1e3a8a;font-size:14px;line-height:1.8;margin:10px 0 0 20px;">
                                    <li>Account activation confirmation email</li>
                                    <li>Login credentials for your school admin panel</li>
                                    <li>Access to our comprehensive training resources</li>
                                    <li>Dedicated support contact information</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Contact Section -->
                    <tr>
                        <td style="padding:0 40px 40px 40px;">
                            <div style="text-align:center;color:#6b7280;font-size:14px;">
                                <div style="font-weight:600;color:#111827;font-size:16px;margin-bottom:15px;">Need Assistance?</div>
                                <div style="margin:8px 0;">
                                    <strong>Phone:</strong> <a href="tel:+6799896700" style="color:#00aeef;text-decoration:none;">+679 9896700</a>
                                </div>
                                <div style="margin:8px 0;">
                                    <strong>Email:</strong> <a href="mailto:info@navulifiji.com" style="color:#00aeef;text-decoration:none;">info@navulifiji.com</a>
                                </div>
                                <div style="margin:8px 0;">
                                    <strong>Business Hours:</strong> Monday-Friday, 8:00 AM - 4:00 PM (FJT)
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#1f2937;color:#9ca3af;padding:40px 40px;text-align:center;">
                            
                            <!-- Social Icons -->
                            <table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 25px auto;">
                                <tr>
                                    <td style="padding:0 8px;">
                                        <a href="https://facebook.com/navulifiji" style="display:block;"><img src="https://cdn-icons-png.flaticon.com/512/5968/5968764.png" alt="Facebook" width="36" height="36" style="width:36px;height:36px;border-radius:50%;display:block;"></a>
                                    </td>
                                    <td style="padding:0 8px;">
                                        <a href="https://youtube.com/navulifiji" style="display:block;"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384060.png" alt="YouTube" width="36" height="36" style="width:36px;height:36px;border-radius:50%;display:block;"></a>
                                    </td>
                                    <td style="padding:0 8px;">
                                        <a href="https://instagram.com/navulifiji" style="display:block;"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram" width="36" height="36" style="width:36px;height:36px;border-radius:50%;display:block;"></a>
                                    </td>
                                    <td style="padding:0 8px;">
                                        <a href="https://twitter.com/navulifiji" style="display:block;"><img src="https://cdn-icons-png.flaticon.com/512/5969/5969020.png" alt="Twitter" width="36" height="36" style="width:36px;height:36px;border-radius:50%;display:block;"></a>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Footer Links -->
                            <div style="margin:20px 0;font-size:13px;">
                                <a href="https://navulifiji.com/privacy" style="color:#9ca3af;text-decoration:none;margin:0 10px;">Privacy Policy</a>
                                <span style="color:#6b7280;">•</span>
                                <a href="https://navulifiji.com/terms" style="color:#9ca3af;text-decoration:none;margin:0 10px;">Terms of Service</a>
                                <span style="color:#6b7280;">•</span>
                                <a href="https://navulifiji.com/support" style="color:#9ca3af;text-decoration:none;margin:0 10px;">Support Center</a>
                            </div>
                            
                            <!-- Copyright -->
                            <div style="font-size:13px;margin-top:20px;color:#9ca3af;line-height:1.6;">
                                © <?php echo date('Y'); ?> Navuli Fiji Ltd. All rights reserved.<br>
                                <small style="font-size:11px;color:#6b7280;">
                                    This is an official invoice for your Navuli Fiji subscription.<br>
                                    Please keep this for your records.
                                </small>
                            </div>
                            
                            <!-- Print Button Hint -->
                            <div style="margin-top:20px;font-size:12px;color:#9ca3af;">
                                💡 Tip: Print this invoice for your records using your browser's print function
                            </div>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
