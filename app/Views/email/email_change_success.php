<?= $this->include('email/email_header') ?>

<!-- GREETING ROW -->
<tr>
    <td style="padding:30px 40px 15px 40px;">
        <h1 style="color:#181C32; font-size:16px; font-weight:500; margin:0 0 6px 0; font-family:Arial,Helvetica,sans-serif;">Bula <?= esc($name) ?>,</h1>
        <p style="color:#7E8299; font-size:13px; margin:0; font-family:Arial,Helvetica,sans-serif;">
            Your email address has been successfully updated on your Navuli Fiji account.
        </p>
    </td>
</tr>

<!-- DETAILS ROW -->
<tr>
    <td style="padding:0 40px 20px 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F9F9F9; border-radius:8px;">
            <tr>
                <td style="padding:8px 20px; border-bottom:1px dashed #E4E6EF;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="color:#5E6278; font-size:12px; font-weight:500; font-family:Arial,Helvetica,sans-serif;">Previous Email</td>
                            <td align="right" style="color:#A1A5B7; font-size:12px; font-weight:600; text-decoration:line-through; font-family:Arial,Helvetica,sans-serif;"><?= esc($old_email) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:8px 20px; border-bottom:1px dashed #E4E6EF;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="color:#5E6278; font-size:12px; font-weight:500; font-family:Arial,Helvetica,sans-serif;">New Email</td>
                            <td align="right" style="color:#50CD89; font-size:12px; font-weight:600; font-family:Arial,Helvetica,sans-serif;"><?= esc($new_email) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:8px 20px;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="color:#5E6278; font-size:12px; font-weight:500; font-family:Arial,Helvetica,sans-serif;">Changed On</td>
                            <td align="right" style="color:#181C32; font-size:12px; font-weight:600; font-family:Arial,Helvetica,sans-serif;"><?= esc($date) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>

<!-- LOGIN CTA ROW -->
<tr>
    <td align="center" style="padding:0 40px 15px 40px;">
        <p style="color:#5E6278; font-size:13px; margin:0 0 12px 0; font-family:Arial,Helvetica,sans-serif;">You can now log in using your new email address.</p>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="background-color:#009EF7; border-radius:6px;">
                    <a href="<?= base_url('auth/login') ?>" target="_blank"
                       style="display:inline-block; background-color:#009EF7; color:#ffffff; font-size:14px; font-weight:600; padding:11px 30px; border-radius:6px; text-decoration:none; font-family:Arial,Helvetica,sans-serif;">
                        &#8594; &nbsp;Go to Login
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>

<!-- SECURITY NOTICE ROW -->
<tr>
    <td style="padding:0 40px 20px 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1FAFF; border-left:4px solid #009EF7; border-radius:6px;">
            <tr>
                <td style="padding:12px 15px;">
                    <p style="color:#0070AD; font-size:12px; font-weight:600; margin:0 0 4px 0; font-family:Arial,Helvetica,sans-serif;">&#128161; Security Tip</p>
                    <p style="color:#0070AD; font-size:12px; margin:0; font-family:Arial,Helvetica,sans-serif;">
                        If you did not make this change, contact us immediately at
                        <a href="mailto:info@navulifiji.com" style="color:#0070AD; font-weight:700; text-decoration:none;">info@navulifiji.com</a>.
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>

<?= $this->include('email/email_footer') ?>