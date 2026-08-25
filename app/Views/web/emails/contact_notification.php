<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <h2 style="color:#005B96;">New Website Enquiry</h2>
    <table style="width:100%; border-collapse: collapse;">
        <tr><td style="padding:6px 0; font-weight:bold; width:140px;">Name</td><td><?= esc($name) ?></td></tr>
        <tr><td style="padding:6px 0; font-weight:bold;">Email</td><td><?= esc($email) ?></td></tr>
        <tr><td style="padding:6px 0; font-weight:bold;">Phone</td><td><?= esc($phone ?: '—') ?></td></tr>
        <tr><td style="padding:6px 0; font-weight:bold;">School</td><td><?= esc($school_name ?: '—') ?></td></tr>
        <tr><td style="padding:6px 0; font-weight:bold;">Subject</td><td><?= esc($subject ?: '—') ?></td></tr>
    </table>
    <p style="font-weight:bold; margin-top:16px;">Message</p>
    <p style="white-space: pre-wrap;"><?= esc($message) ?></p>
</div>
