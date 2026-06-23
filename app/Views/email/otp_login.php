<?= $this->include('email/email_header') ?>

<tr>
    <td style="padding:30px 40px;">
        <p style="margin:0 0 16px; font-size:16px; color:#1a1a2e;">Hi <?= esc($name) ?>,</p>
        <p style="margin:0 0 24px; font-size:14px; color:#555; line-height:1.6;">
            A login attempt was made to your Navuli Fiji account. Use the code below to complete sign in:
        </p>

        <!--begin::OTP Box-->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
            <tr>
                <td align="center">
                    <div style="background:#fff8f0; border:2px dashed #f6a723; border-radius:12px;
                                padding:24px 40px; display:inline-block;">
                        <span style="font-size:40px; font-weight:900; letter-spacing:16px;
                                     color:#e67e22; font-family:monospace;">
                            <?= esc($otp) ?>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        <!--end::OTP Box-->

        <p style="margin:0 0 16px; font-size:13px; color:#888; text-align:center;">
            This code expires in <strong><?= esc($expiry) ?></strong>.
        </p>
        <p style="margin:0 0 8px; font-size:13px; color:#e74c3c; font-weight:bold;">
            Never share this code with anyone.
        </p>
        <p style="margin:0; font-size:13px; color:#888;">
            If you did not attempt to log in, please change your password immediately.
        </p>
    </td>
</tr>

<?= $this->include('email/email_footer') ?>