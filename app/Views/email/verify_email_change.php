    <?= $this->include('email/email_header') ?>



        <h1 style="color:#181C32; font-size:16px; font-weight:500; margin:0 0 6px 0; font-family:Arial,Helvetica,sans-serif;">Bula <?= esc($name) ?>,</h1>
        <p style="color:#7E8299; font-size:13px; margin:0; font-family:Arial,Helvetica,sans-serif;">
            You requested to change your email address from
            <strong style="color:#181C32;"><?= esc($old_email) ?></strong>
            to
            <strong style="color:#50CD89;"><?= esc($new_email) ?></strong>.
        </p>
    </td>
</tr>

<!-- CTA BUTTON ROW -->
<tr>
    <td align="center" style="padding:15px 40px 10px 40px;">
        <p style="color:#5E6278; font-size:13px; margin:0 0 12px 0; font-family:Arial,Helvetica,sans-serif;">Click the button below to confirm this change.</p>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center" style="background-color:#50CD89; border-radius:6px;">
                    <a href="<?= $verify_url ?>" target="_blank"
                       style="display:inline-block; background-color:#50CD89; color:#ffffff; font-size:14px; font-weight:600; padding:11px 30px; border-radius:6px; text-decoration:none; font-family:Arial,Helvetica,sans-serif;">
                        &#10003; &nbsp;Verify New Email
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>

<!-- OR DIVIDER ROW -->
<tr>
    <td align="center" style="padding:15px 40px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="border-bottom:1px solid #E4E6EF; width:45%;"></td>
                <td align="center" style="color:#A1A5B7; font-size:11px; font-weight:600; font-family:Arial,Helvetica,sans-serif; white-space:nowrap; padding:0 10px; width:10%;">OR</td>
                <td style="border-bottom:1px solid #E4E6EF; width:45%;"></td>
            </tr>
        </table>
    </td>
</tr>

<!-- URL COPY ROW -->
<tr>
    <td style="padding:0 40px 15px 40px;">
        <p style="color:#5E6278; font-size:12px; font-weight:600; margin:0 0 6px 0; font-family:Arial,Helvetica,sans-serif;">
            &#128279; &nbsp;Button not working? Copy and paste this link into your browser:
        </p>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1FAFF; border:1px solid #B4D9F5; border-radius:6px;">
            <tr>
                <td style="padding:10px 12px; word-break:break-all;">
                    <a href="<?= $verify_url ?>" target="_blank"
                       style="color:#009EF7; font-size:11px; font-weight:500; text-decoration:none; line-height:1.5; font-family:'Courier New',monospace;">
                        <?= $verify_url ?>
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>

<!-- SECURITY + EXPIRY ROW -->
<tr>
    <td align="center" style="padding:0 40px 20px 40px;">
        <p style="color:#A1A5B7; font-size:11px; margin:0 0 5px 0; font-family:Arial,Helvetica,sans-serif;">
            If you did not request this change, ignore this email or contact
            <a href="mailto:info@navulifiji.com" style="color:#A1A5B7; font-weight:700; text-decoration:none;">info@navulifiji.com</a>.
        </p>
        <p style="color:#A1A5B7; font-size:11px; margin:0; font-family:Arial,Helvetica,sans-serif;">
            &#128336; &nbsp;Expires on <strong style="color:#5E6278;"><?= esc($expiry) ?></strong>
        </p>
    </td>
</tr>

<?= $this->include('email/email_footer') ?>

                