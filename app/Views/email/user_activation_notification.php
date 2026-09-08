<?= $this->include('email/email_header') ?>

<tr>
    <td style="padding:30px 40px;">
        <p style="margin:0 0 16px; font-size:16px; color:#1a1a2e; font-weight:600;">
            Bula Vinaka <?= esc($name) ?>!
        </p>
        <p style="margin:0 0 20px; font-size:14px; color:#555; line-height:1.7;">
            Thank you for registering your school with Navuli Fiji. Your administrator
            account has been created — here are your login details.
        </p>

        <!--begin::Credentials box-->
        <table width="100%" cellpadding="0" cellspacing="0"
               style="background:#f8faff; border:1px solid #dce8ff; border-radius:8px;
                      margin-bottom:24px;">
            <tr>
                <td style="padding:16px 20px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-size:13px; color:#888; padding-bottom:8px;">
                                <strong style="color:#555;">Login Email:</strong>
                                <?= esc($email) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px; color:#888;">
                                <strong style="color:#555;">Password:</strong>
                                <?= esc($password) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--end::Credentials box-->

        <p style="margin:0 0 20px; font-size:13px; color:#888; line-height:1.6;">
            For security, please sign in and change your password after activating your account.
        </p>

        <!--begin::Action-->
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="padding:10px 0 25px 0;">
                    <a href="<?= site_url('account/activate/' . $code) ?>" style="display:inline-block;background:linear-gradient(135deg,#2e3192 0%,#00aeef 100%);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:15px;font-weight:600;">Activate Your Account</a>
                </td>
            </tr>
        </table>
        <!--end::Action-->

        <div style="text-align:center; margin:0 0 10px; font-size:13px; color:#6b7280;">
            <p style="margin:0 0 8px 0;">Or copy and paste this link:</p>
            <div style="word-break:break-all;background-color:#f8f9fa;padding:12px;border-radius:4px;font-size:12px;font-family:monospace;color:#2e3192;"><?= esc(site_url('account/activate/' . $code)) ?></div>
        </div>
    </td>
</tr>

<?= $this->include('email/email_footer') ?>
