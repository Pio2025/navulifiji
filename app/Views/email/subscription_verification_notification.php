<?= $this->include('email/email_header') ?>

<tr>
    <td style="padding:30px 40px;">
        <p style="margin:0 0 16px; font-size:16px; color:#1a1a2e; font-weight:600;">
            Bula Vinaka <?= esc($schoolName) ?>!
        </p>
        <p style="margin:0 0 20px; font-size:14px; color:#555; line-height:1.7;">
            Good news — our team has reviewed and verified your school's subscription
            request. Your subscription is now <strong>Pending Payment</strong>. Please
            find the attached invoice (<?= esc($invoiceNumber) ?>) for payment details,
            including the available payment methods.
        </p>

        <!--begin::Summary box-->
        <table width="100%" cellpadding="0" cellspacing="0"
               style="background:#f8faff; border:1px solid #dce8ff; border-radius:8px;
                      margin-bottom:24px;">
            <tr>
                <td style="padding:16px 20px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-size:13px; color:#888; padding-bottom:8px;">
                                <strong style="color:#555;">Invoice Number:</strong>
                                <?= esc($invoiceNumber) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px; color:#888; padding-bottom:8px;">
                                <strong style="color:#555;">Plan:</strong>
                                <?= esc($planName) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px; color:#888;">
                                <strong style="color:#555;">Amount Due:</strong>
                                FJD <?= number_format((float) $amount, 2) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--end::Summary box-->

        <p style="margin:0 0 8px; font-size:13px; color:#888; line-height:1.6;">
            Once payment is received and confirmed, your subscription will be activated
            and your school account fully unlocked. If you have any questions about the
            invoice or payment methods, just reply to this email or contact us below.
        </p>
    </td>
</tr>

<?= $this->include('email/email_footer') ?>
