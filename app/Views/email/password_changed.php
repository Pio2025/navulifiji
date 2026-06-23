<?= $this->include('email/email_header') ?>

<tr>
    <td style="padding:30px 40px;">
        <p style="margin:0 0 16px; font-size:16px; color:#1a1a2e; font-weight:600;">
            Hi <?= esc($name) ?>,
        </p>
        <p style="margin:0 0 20px; font-size:14px; color:#555; line-height:1.7;">
            This is a confirmation that the password for your Navuli Fiji account
            <strong><?= esc($email) ?></strong> was successfully changed.
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
                                <strong style="color:#555;">Time:</strong>
                                <?= esc($time) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px; color:#888;">
                                <strong style="color:#555;">IP Address:</strong>
                                <?= esc($ip) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--end::Details box-->

        <!--begin::Warning-->
        <table width="100%" cellpadding="0" cellspacing="0"
               style="background:#fff0f0; border:1px dashed #f1416c; border-radius:8px;
                      margin-bottom:24px;">
            <tr>
                <td style="padding:16px 20px;">
                    <p style="margin:0 0 6px; font-size:13px; font-weight:700; color:#f1416c;">
                        Not you?
                    </p>
                    <p style="margin:0; font-size:12px; color:#888; line-height:1.6;">
                        If you did not make this change, your account may be compromised.
                        Please contact your system administrator immediately or use the
                        forgot password feature to regain access.
                    </p>
                </td>
            </tr>
        </table>
        <!--end::Warning-->

        <p style="margin:0; font-size:13px; color:#888;">
            All active sessions have been signed out for your security.
            Please sign in again with your new password.
        </p>
    </td>
</tr>

<?= $this->include('email/email_footer') ?>