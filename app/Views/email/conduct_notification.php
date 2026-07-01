<?= $this->include('email/email_header') ?>

<tr>
    <td style="padding:30px 40px;">
        <p style="margin:0 0 16px; font-size:16px; color:#1a1a2e; font-weight:600;">
            Hi <?= esc($parentName) ?>,
        </p>
        <p style="margin:0 0 20px; font-size:14px; color:#555; line-height:1.7;">
            A conduct incident has been logged for <strong><?= esc($studentName) ?></strong>.
        </p>

        <!--begin::Details box-->
        <table width="100%" cellpadding="0" cellspacing="0"
               style="background:#f8faff; border:1px solid #dce8ff; border-radius:8px;
                      margin-bottom:24px;">
            <tr>
                <td style="padding:16px 20px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-size:12px; color:#888; padding-bottom:6px;">
                                <strong style="color:#555;">Type:</strong>
                                <?= esc($incident['type_name'] ?? 'N/A') ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px; color:#888; padding-bottom:6px;">
                                <strong style="color:#555;">Date:</strong>
                                <?= esc($incident['incident_date'] ?? '') ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px; color:#888;">
                                <strong style="color:#555;">Notes:</strong>
                                <?= esc($message) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--end::Details box-->

        <p style="margin:0; font-size:13px; color:#888;">
            Please log in to your Navuli Fiji account for further details.
        </p>
    </td>
</tr>

<?= $this->include('email/email_footer') ?>
