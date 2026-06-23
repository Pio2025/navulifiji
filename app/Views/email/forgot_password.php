<?= $this->include('email/email_header') ?>

<tr>
    <td style="padding:30px 40px;">
        <p style="margin:0 0 16px; font-size:16px; color:#1a1a2e; font-weight:600;">
            Hi <?= esc($name) ?>,
        </p>
        <p style="margin:0 0 24px; font-size:14px; color:#555; line-height:1.7;">
            We received a request to reset the password for your Navuli Fiji account
            associated with <strong><?= esc($email) ?></strong>.
        </p>
        <p style="margin:0 0 24px; font-size:14px; color:#555; line-height:1.7;">
            Click the button below to set a new password. This link will expire in
            <strong><?= esc($expiry) ?></strong>.
        </p>

        <!--begin::Reset Button-->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
            <tr>
                <td align="center">
                    <a href="<?= esc($resetUrl) ?>"
                       style="display:inline-block; background:#1a56db; color:#ffffff;
                              font-size:15px; font-weight:700; padding:14px 36px;
                              border-radius:8px; text-decoration:none; letter-spacing:0.3px;">
                        Reset My Password
                    </a>
                </td>
            </tr>
        </table>
        <!--end::Reset Button-->

        <!--begin::Fallback URL-->
        <p style="margin:0 0 8px; font-size:12px; color:#888;">
            If the button doesn't work, copy and paste this link into your browser:
        </p>
        <p style="margin:0 0 24px; font-size:11px; word-break:break-all;">
            <a href="<?= esc($resetUrl) ?>" style="color:#1a56db;">
                <?= esc($resetUrl) ?>
            </a>
        </p>
        <!--end::Fallback URL-->

        <!--begin::Security note-->
        <table width="100%" cellpadding="0" cellspacing="0"
               style="background:#fff8f0; border:1px dashed #f6a723; border-radius:8px;
                      margin-bottom:20px;">
            <tr>
                <td style="padding:16px 20px;">
                    <p style="margin:0 0 6px; font-size:13px; font-weight:700; color:#e67e22;">
                        Security Notice
                    </p>
                    <p style="margin:0; font-size:12px; color:#888; line-height:1.6;">
                        If you did not request a password reset, please ignore this email.
                        Your password will not be changed. For security, this link expires
                        in <?= esc($expiry) ?> and can only be used once.
                    </p>
                </td>
            </tr>
        </table>
        <!--end::Security note-->

    </td>
</tr>

<?= $this->include('email/email_footer') ?>